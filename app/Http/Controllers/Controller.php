<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Auth, Cache, Input;
use App\Models\Project\Project,
    App\Models\Project\Section,
	App\Models\Project\Supervision,
    App\Models\User\User;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public $user;
    public $user_section;

    public function __construct()
    {
        $this->user = $this->getUserInfo();
        if($this->user){
            $this->user_section = $this->getSectionByUser();
        }
    }

    /*
    *获取用户信息
    *
    */
    protected function getUserInfo(){
        if(Auth::user()){
            /*if(Cache::get('user_'.Auth::user()->id.'_info')){
                return Cache::get('user_'.Auth::user()->id.'_info');
            }*/

            $user = User::with(['project'=>function($query){
                                    $query->select(['id']);
                                }])
                              ->find(Auth::user()->id);
            foreach ($user->project as $key => $value) {
                $user->project[$key] = $value['id'];
            }

            Cache::put('user_'.$user->id.'_info', $user, Config()->get('common.cache_expire'));
            return $user;
        }
    }

    /*
    *根据用户获取标段信息
    *
    */
    protected function getSectionByUser(){
        if(Cache::get('user_'.$this->user->id.'_section')){
            return Cache::get('user_'.$this->user->id.'_section');
        }
        
        $section_list = [];
        $section_where = '';
        if($this->user->role == 5){   //标段用户
            $section_list = Section::select(['id','name'])
                                    ->where('id', $this->user->section_id)
                                    ->orderByRaw('id asc')
                                    ->get()
                                    ->toArray();
            $section_where = $this->user->section_id;
        }elseif($this->user->role == 4){   //监理用户
            //监理拥有的标段
            $sup = Supervision::select(['id','name'])
                              ->where('id', $this->user->supervision_id)
                              ->with(['sec'=>function($query){
                                    $query->select(['id','name'])
                                          ->orderByRaw('id asc');
                                }])
                              ->first()
                              ->toArray();
            $section_list = $sup['sec'];
            if($section_list){
                foreach ($section_list as $key => $value) {
                    $section[$key] = $value['id'];
                }
            }else{
                $section = [];
            }
            $section_where = $section;
        }elseif($this->user->role == 3){    //项目用户
            $section_list = Section::select(['id','name'])
                                    ->where('project_id', $this->user->project[0])
                                    ->orderByRaw('id asc')
                                    ->get()
                                    ->toArray();
            $section_where = '';
        }elseif($this->user->role == 2 || $this->user->role == 1){
            $section_list = Section::select(['id','name'])
                                    ->whereIn('project_id', $this->user->project)
                                    ->orderByRaw('id asc')
                                    ->get()
                                    ->toArray();
            $section_where = '';
        }
        $section = ['section_list'=>$section_list, 'section_where'=>$section_where];
        Cache::put('user_'.$this->user->id.'_section', $section, Config()->get('common.cache_expire'));
        return $section;
    }

    /*
    *根据用户获取监理信息
    *
    */
    protected function getSupByUser(){
        if(Cache::get('user_'.$this->user->id.'_supervision')){
            return Cache::get('user_'.$this->user->id.'_supervision');
        }
        
        $supervision_list = [];
        if($this->user->role == 5){   //标段用户
            $supervision_list[0] = $this->user->supervision_id;
        }elseif($this->user->role == 4){   //监理用户
            $supervision_list[0] = $this->user->supervision_id;
        }elseif($this->user->role == 3){    //项目用户
            $supervision = Supervision::select(['id'])
                                            ->where('project_id', $this->user->project[0])
                                            ->orderByRaw('id asc')
                                            ->get()
                                            ->toArray();
            foreach ($supervision as $key => $value) {
                $supervision_list[$key] = $value['id']; 
            }
        }elseif($this->user->role == 2 || $this->user->role == 1){
            $supervision = Supervision::select(['id'])
                                            ->whereIn('project_id', $this->user->project)
                                            ->orderByRaw('id asc')
                                            ->get()
                                            ->toArray();
            foreach ($supervision as $key => $value) {
                $supervision_list[$key] = $value['id']; 
            }
        }
        Cache::put('user_'.$this->user->id.'_supervision', $supervision_list, Config()->get('common.cache_expire'));
        return $supervision_list;
    }

    protected function getTreeAllData($has_device=0, $device_cat=''){
        $project_list = '';
        $query = Project::select(['id','name']);
        //筛选项目信息
        if($this->user->role != 1 && $this->user->role != 2){
            $query = $query->where('id', '=', $this->user->project[0]);
        }else{
            $query = $query->whereIn('id', $this->user->project);
        }
        //筛选监理
        $sup = '';
        if($this->user->role == 4 || $this->user->role == 5){
            $sup = $this->user->supervision_id;
        }
        //筛选标段
        $sec = '';
        if($this->user->role == 5){
            $sec = $this->user->section_id;
        }
        $query = $query->with(['sup'=>function($query) use ($sup, $sec, $has_device, $device_cat){
                            $query->select(['id', 'project_id', 'name']);
                            if($sup){
                                $query->where('id', '=', $sup);
                            }
                            $query->with(['sec'=>function($query) use ($sec, $has_device, $device_cat){
                                $query->select(['id', 'name']);
                                if($sec){
                                    $query->where('id', '=', $sec);
                                }
                                if($has_device){
                                    $query->with(['device'=>function($query) use ($device_cat){
                                        $query->select(['id', 'name', 'section_id', 'dcode', 'model', 'cat_id']);
                                        if(is_array($device_cat)){
                                            $query->whereIn('cat_id', $device_cat);
                                        }else{
                                            $query->where('cat_id', '=', $device_cat);
                                        }
                                        $query->with('category');
                                    }]);
                                }
                            }]);
                        }]);

        $list = $query->orderByRaw('id desc')
                      ->get()
                      ->toArray();
        //树形结构内容
        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id']; 
            $data[$key]['name'] = $value['name'];
            $data[$key]['key'] = 'pro_id'; 
            if($key == 0){
                $data[$key]['open'] = true; 
            }else{
                $data[$key]['open'] = false;
            }
            if($value['sup']){
                foreach ($value['sup'] as $k => $v) {
                    $data[$key]['children'][$k]['id'] = $v['id'];
                    $data[$key]['children'][$k]['name'] = $v['name'];
                    $data[$key]['children'][$k]['key'] = 'sup_id';
                    if($v['sec']){
                        foreach ($v['sec'] as $kk => $vv) {
                            $data[$key]['children'][$k]['children'][$kk]['id'] = $vv['id'];
                            $data[$key]['children'][$k]['children'][$kk]['name'] = $vv['name'];
                            $data[$key]['children'][$k]['children'][$kk]['key'] = 'sec_id';
                            if($has_device && $vv['device']){
                                $data[$key]['children'][$k]['children'][$kk]['isParent'] = true;
                                foreach ($vv['device'] as $kkk => $vvv) {
                                    $data[$key]['children'][$k]['children'][$kk]['children'][$kkk]['id'] = $vvv['id'];
                                    if(is_array($device_cat) && in_array(1, $device_cat)){
                                        $data[$key]['children'][$k]['children'][$kk]['children'][$kkk]['name'] = $vvv['model'].$vvv['category']['name'];
                                    }else{
                                        $data[$key]['children'][$k]['children'][$kk]['children'][$kkk]['name'] = $vvv['name'];
                                    }
                                    $data[$key]['children'][$k]['children'][$kk]['children'][$kkk]['key'] = 'device_id';
                                }
                            }
                        }
                    }else{
                        $data[$key]['children'][$k]['isParent'] = true; 
                    }
                }
            }else{
                $data[$key]['isParent'] = true; 
            }
        }
        return $data;
    }

    protected function getTreeProjectData(){
        $query = Project::select(['id','name']);
        //筛选项目信息
        if($this->user->role != 1 && $this->user->role != 2){
            $query = $query->where('id', '=', $this->user->project[0]);
        }else{
            $query = $query->whereIn('id', $this->user->project);
        }
        $list = $query->orderByRaw('id desc')
                      ->get()
                      ->toArray();
        /*if($this->user->role == 1 || $this->user->role == 2){
            array_unshift($list, ['id'=>0, 'name'=>'全部']);
        }*/
        return $list;
    }

    protected function judgePermission(){
        $pro_id = Input::get('pro_id');
        if($pro_id && !in_array($pro_id, $this->user->project->toArray())){
            return view('admin.error.no_info', ['info'=>'您没有权限']);
        }
        $sup_id = Input::get('sup_id');
        if($sup_id){
            $supervision_list = $this->getSupByUser();
            if(!in_array($sup_id, $supervision_list)){
                return view('admin.error.no_info', ['info'=>'您没有权限']);
            }
        }
        $sec_id = Input::get('sec_id');
        if($sec_id){
            $section_list = $this->user_section['section_list'];
            foreach ($section_list as $key => $value) {
                $section[$key] = $value['id'];
            }
            if(!in_array($sec_id, $section)){
                return view('admin.error.no_info', ['info'=>'您没有权限']);
            }
        }
        return true;
    }
}
