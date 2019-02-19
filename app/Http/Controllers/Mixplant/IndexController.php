<?php

namespace App\Http\Controllers\Mixplant;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input, Cache, DB, Log,Auth;
use App\Models\Device\Device,
    App\Models\Project\Project,
    App\Models\Project\Supervision,
    App\Models\Project\Section,
    App\Models\System\Warn_user_set;
use App\Send\SendSms, App\Send\SendWechat;
class IndexController extends Controller
{
    protected $project_id = '';
    public function __construct(){
        parent::__construct();

        if($this->user->role == 1 || $this->user->role == 2){
            $data['project'] = Project::select(['id','name'])->orderByRaw('id')->get()->toArray();
            $this->project_id = Input::get('pro_id') ? Input::get('pro_id') : $data['project'][0]['id'];
            view()->share($data);
        }else{
            $this->project_id = $this->user->project[0];
        }
    }

    public function index()
    {
        return view('mixplant.index');
    }

    /*
    *根据用户类型 获取对应的设备
    *'1'=>'系统管理员',
    *'2'=>'集团用户',
    *'3'=>'项目用户',
    *'4'=>'监理',
    *'5'=>'合同段'
    */
    protected function getDevice($field='*', $pro_id='', $sup_id='', $sec_id='', $ispage=1){
        //获取可查看的设备信息id
        $query = Device::select($field);
        //->where('project_id', $this->project_id);
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }
        if($this->user->supervision_id){
            $query = $query->where('supervision_id', $this->user->supervision_id);
        }
        if($this->user->section_id){
            $query = $query->where('section_id', $this->user->section_id);
        }
        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }

        if($this->user->role > 1 && $pro_id){
            $query = $query->where('project_id', $pro_id);
        }
        if($sup_id){
            $query = $query->where('supervision_id', $sup_id);
        }
        if($sec_id){
            if(is_array($sec_id)){
                $query = $query->whereIn('section_id', $sec_id);
            }else{
                $query = $query->where('section_id', $sec_id);
            }
        }

        if(in_array('section_id', $field)){
            $query = $query->with(['project'=>function($query){
                $query->select(['id','name']);
            },'section'=>function($query){
                $query->select(['id','name']);
            }, 'sup'=>function($query){
                $query->select(['id','name']);
            },'category']);
        }

        if($ispage && count($field)>5){
            $list = $query->orderByRaw($this->order)
                ->paginate($this->ispage)
                ->toArray();
        }else{
            $list = $query->orderByRaw($this->order)
                ->get()
                ->toArray();
        }

        //可以查看的设备id 名称 类型等信息存入session/memcache
        return $list;
    }

    /*获取所有该子系统的设备*/
    protected function getDevice1($field='*', $ispage=1){
        //获取可查看的设备信息id
        $query = Device::select($field);

        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }

        if(in_array('section_id', $field)){
            $query = $query->with(['project'=>function($query){
                $query->select(['id','name']);
            },'section'=>function($query){
                $query->select(['id','name']);
            }, 'sup'=>function($query){
                $query->select(['id','name']);
            },'category']);
        }

        if($ispage && count($field)>5){
            $list = $query->orderByRaw($this->order)
                ->paginate($this->ispage)
                ->toArray();
        }else{
            $list = $query->orderByRaw($this->order)
                ->get()
                ->toArray();
        }
        return $list;
    }

    //获取登录的用户可以获取的设备信息
    protected function getDeviceId($pro_id='', $sup_id='', $sec_id=''){
        $device = $this->getDevice(['id'],$pro_id, $sup_id, $sec_id);
        foreach ($device as $key => $value) {
            $data[$key] = $value['id'];
        }
        return $data;
    }

    protected function getPageTreeDataAndUrl($url, $pro_id='', $sup_id='', $sec_id='', $has_device=0){
        if(isset($this->device_cat)){
            $data = $this->getTreeAllData($has_device, $this->device_cat);
        }else{
            $data = $this->getTreeAllData($has_device, $this->device_cat='');
        }

//        $data = $this->getTreeAllData($has_device, $this->device_cat);

        $list['ztree_data'] = json_encode($data);

        $url_para = '';
        if($pro_id){
            $url_para = 'pro_id='.$pro_id;
            $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
            $list['tree_value'] = $pro_id;
            $list['tree_key'] = 'pro_id';
        }
        if($sup_id){
            $url_para = 'sup_id='.$sup_id;
            $list['ztree_name'] = Supervision::where('id', $sup_id)->pluck('name');
            $list['tree_value'] = $sup_id;
            $list['tree_key'] = 'sup_id';
        }
        if($sec_id && !is_array($sec_id)){
            $url_para = 'sec_id='.$sec_id;
            $list['ztree_name'] = Section::where('id', $sec_id)->pluck('name');
            $list['tree_value'] = $sec_id;
            $list['tree_key'] = 'sec_id';
        }
        if($has_device && Input::get('device_id')){
            $device_id = Input::get('device_id');
            $device = Device::where('id', $device_id)->with('category')->first();
            $list['ztree_name'] = $device['model'].$device['category']['name'];
            $list['tree_value'] = $device_id;
            $list['tree_key'] = 'device_id';
        }
        //var_dump($list);
        if(!isset($list['ztree_name']) || !$list['ztree_name']){
            $list['ztree_name'] = $data[0]['name'];
            $list['tree_value'] = $pro_id;
            $list['tree_key'] = 'pro_id';
        }
        $list['ztree_url'] = $url;
        $list['url'] = $list['search_url'] = $url_para ? $url.'?'.$url_para : $url;
        //var_dump($list);
        return $list;
    }

    protected function getPageTreePorjectDataAndUrl($url, $pro_id=''){
        $data = $this->getTreeProjectData();
        $list['ztree_data'] = json_encode($data);

        $url_para = '';
        if($pro_id){
            $url_para = 'pro_id='.$pro_id;
            $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
            $list['tree_value'] = $pro_id;
            $list['tree_key'] = 'pro_id';
        }
        if(!isset($list['ztree_name']) || !$list['ztree_name']){
            $list['ztree_name'] = $data[0]['name'];
            $list['tree_value'] = $pro_id;
            $list['tree_key'] = 'pro_id';
        }
        $list['ztree_url'] = $url;
        $list['url'] = $list['search_url'] = $url_para ? $url.'?'.$url_para : $url;

        return $list;
    }

    protected function getUserSection($pro_id='', $sup_id=''){
        $role = $this->user->role;
        $user_pro = $this->user->project[0];
        $user_sup = $this->user->supervision_id;
        $user_sec = $this->user->section_id;
        if(!$pro_id && !$sup_id){
            $pro_id = $this->project_id;
        }
        if($pro_id){
            if ($role == 1) {
                $section = Section::select(['id','name'])->get()->toArray();
                return $section;
            }
            if($role == 2 || $role == 3){
                $section = Section::select(['id','name'])->where('project_id', $pro_id)->get()->toArray();
                return $section;
            }
            if($pro_id != $user_pro){
                return [];
            }
            $list = Supervision::where('id', $user_sup)
                ->with(['sec'=>function($query) use ($user_sec){
                    $query->select(['id','name']);
                    if($user_sec){
                        $query->where('id', $user_sec);
                    }
                }])
                ->first()
                ->toArray();
            return $list['sec'];

        }
        if($sup_id){
            if(($role == 4 || $role == 5) && $sup_id != $user_sup){
                return [];
            }
            $list = Supervision::where('id', $sup_id)
                ->with(['sec'=>function($query) use ($role, $user_pro, $user_sec){
                    $query->select(['id','name']);
                    if($role != 1 && $role != 2){
                        $query->where('project_id', $user_pro);
                    }
                    if($role == 5){
                        $query->where('id', $user_sec);
                    }
                }])
                ->first()
                ->toArray();
            return $list['sec'];
        }
    }

    protected function getIndex($model, $url, $view){
        $list = [
            'J1'=>['0'=>'离线','1'=>'在线'],
            'J2'=>['0'=>'断网','1'=>'良好'],
            'J3'=>['0'=>'断电','1'=>'良好'],
            'J4'=>['0'=>'是','1'=>'否'],
            'J5'=>['0'=>'不正常','1'=>'正常','2'=>'正常'],
        ];

        //当日各类型实验次数统计
        $date = date('Y-m-d');

        $query = $model->where([]);
        $wait_deal_warn_query = $model->where([]);

        $device_field = ['id','project_id','supervision_id','section_id','cat_id','name','dcode','model','last_time','camera1','camera2'];
        if(!Input::get('pro_id') && ($this->user->role==2 || $this->user->role==1)){
            //获取所有拌和设备
            $device = Device::select($device_field);
            if(is_array($this->device_cat)){
                $device = $device->whereIn('cat_id', $this->device_cat);
            }else{
                $device = $device->where('cat_id', $this->device_cat);
            }
            $device = $device->with(['project'=>function($query){
                $query->select(['id','name']);
            },'section'=>function($query){
                $query->select(['id','name']);
            }, 'sup'=>function($query){
                $query->select(['id','name']);
            },'category'])
                ->orderByRaw($this->order)
                ->get()
                ->toArray();
        }else{
            $device = $this->getDevice($device_field, '', '', '', 0);

            $query = $query->where('project_id', $this->project_id);
        }

        //设备总数和在线数
        $list['device_online'] = 0;
        foreach ($device as $key => $value) {
            if(Cache::get('device_status_'.$value['id'])){
                $status = json_decode(Cache::get('device_status_'.$value['id']), true);
                if($status['J1'] == 1){
                    $list['device_online']++;
                }
            }else{
                $status = [
                    'J1'=>0,
                    'J2'=>0,
                    'J3'=>0,
                    'J4'=>0,
                    'J5'=>1,
                ];
            }
            $J5 = Cache::get('device_status_J5_'.$value['id']);
            $status['J5'] = $J5 ?: 1;
            /*也够奇葩的，必须显示设备最新上报时间，
            *因为设备最新上报时间是存到缓存中的，如果服务器重启，会遇到取不到设备最新上报时间的情况
             * 既然必须显示设备最新上报时间
             * 那就这样操作，如果取不到最新上报时间
             * 就去拿设备数据最新上传时间
             * 这样，不管怎样，都可以显示最新上报时间了
            */
            $status['status_time'] = Cache::get('device_status_time_'.$value['id']) ? Cache::get('device_status_time_'.$value['id']) : $value['last_time'];
            $device[$key] = array_merge($value, $status);
        }
        $list['device_count'] = count($device);
        $list['device'] = $device;
        
        //当天试验数 报警数量，未处理报警数量
        if($this->user->supervision_id){
            $query = $query->where('supervision_id', $this->user->supervision_id);
        }
        if($this->user->section_id){
            $query = $query->where('section_id', $this->user->section_id);
        }

        $time = strtotime($date);
        //三天内未处理报警（待处理报警，超过三天禁止处理，所有只计算三天内的）
        $wait_deal_warn_query = $wait_deal_warn_query->where('time', '>=', time()-3*86400)
            ->where('is_warn', 1);
        $list['wait_deal_warn'] = 0;
        $list['type'] = 1;
        //项目用户只统计高级报警，其他统计所有报警级别
        if($this->user->role == 3){
            $list['wait_deal_warn'] = $wait_deal_warn_query->where('is_pro_deal', 0)
                ->where(function($query){
                    $query->where('warn_level', 3)
                        ->orWhere('warn_sx_level', 3);
                })
                ->count();
            //报警信息中建设单位未处理分类值
            $list['type'] = 6;
        }
        if($this->user->role == 4){
            $list['wait_deal_warn'] = $wait_deal_warn_query->where('is_sup_deal', 0)->count();
            //报警信息中监理未处理分类值
            $list['type'] = 5;
        }
        if($this->user->role == 5){
            $list['wait_deal_warn'] = $wait_deal_warn_query->where('is_sec_deal', 0)->count();
            //报警信息中标段未处理分类值
            $list['type'] = 3;
        }

        //查询当天试验/生产信息
        $query = $query->where('time', '>=', $time)
            ->where('time', '<', $time+86400);
        //当天试验数
        $list['sc_num'] = $query->count();
        //当天报警数
        $list['warn_num'] = $query->where('is_warn', 1)->count();
        //当天未处理数
        $list['not_deal_warn'] = $query ->where('is_warn', 1)
            ->where(function($query){
                $query->where('is_sec_deal', 0)
                    ->orWhere('is_sup_deal', 0)
                    ->orWhere(function($query){
                        $query->where('is_pro_deal', 0)
                            ->where('warn_level', 3);
                    });
            })
            ->count();

        if($this->user->role == 1 || $this->user->role == 2){
            $data = $this->getPageTreePorjectDataAndUrl($url, Input::get('pro_id'));
            //添加全部选择
            $ztree_data = json_decode($data['ztree_data'], true);
            array_unshift($ztree_data, ['id'=>0, 'name'=>'全部']);
            $data['ztree_data'] = json_encode($ztree_data);
            if(!Input::get('pro_id')){
                $data['ztree_name'] = '全部';
            }
            $list['pro_id'] = Input::get('pro_id');
            $list['video_code']=Config()->get('common.video');
            return view($view, array_merge($list, $data));
        }
        $list['pro_id'] = $this->project_id;
        $list['video_code']=Config()->get('common.video');

        return view($view, $list);
    }
    /*获取对应项目设备列表*/
    protected function getDeviceList($url, $view){
        //根据用户类型 获取对应的设备
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        $field = ['id',
            'project_id',
            'supervision_id',
            'section_id',
            'cat_id',
            'name',
            'dcode',
            'model',
            'factory_name',
            'factory_date',
            'fzr',
            'phone'];
        if($this->user->role == 1 || $this->user->role == 2){
            //添加全部选择
            $ztree_data = json_decode($tree_data['ztree_data'], true);
            array_unshift($ztree_data, ['id'=>0, 'name'=>'全部']);
            $tree_data['ztree_data'] = json_encode($ztree_data);
            if(!$pro_id && !$sup_id && !$sec_id){
                $tree_data['ztree_name'] = '全部';
                $list = $this->getDevice1($field);
            }else{
                $list = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
            }
        }else{
            $list = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        }
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        return view($view, array_merge($list, $tree_data));
    }

    /*获取报警信息 默认显示全部*/
    protected function getWarnInfo($model, $field, $url, $view){
        $url_para = '';
        $project = [];
        $section = [];

        $query = Device::select(['id', 'name', 'dcode','model','cat_id']);

        //集团用户显示选择项目 监理 标段
        if($this->user->role == 1 || $this->user->role == 2){
            $project = Project::select(['id','name'])->orderByRaw('id asc')->get()->toArray();
            $supervision = Supervision::select(['id','name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
            $section = Section::select(['id','name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
        }
        //项目用户显示选择监理 标段
        if($this->user->role == 3){
            $supervision = Supervision::select(['id','name'])->where('project_id', $this->user->project[0])->orderByRaw('id asc')->get()->toArray();
            $section = $this->getUserSection();
            $query = $query->where('project_id', $this->user->project[0]);
        }
        //监理只显示选择标段
        if($this->user->role == 4){
            $supervision = '';
            $section = $this->getUserSection();
            $query = $query->where('project_id', $this->user->project[0])
                ->where('supervision_id', $this->user->supervision_id);
        }
        if($this->user->role == 5){
            $supervision = '';
            $section = '';
            $query = $query->where('project_id', $this->user->project[0])
                ->where('supervision_id', $this->user->supervision_id)
                ->where('section_id', $this->user->section_id);
        }
        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }
        $device = $query->with('category')->orderByRaw('id asc')->get()->toArray();
//         dd($device);
        //报警状态
        $type = Input::get('type') ? Input::get('type') : 1;
        $url_para .= '?type='.$type;

        //根据用户类型 获取对应的设备的报警信息
        $pro_id = Input::get('pro_id');
        $sec_id = Input::get('sec_id');
        $sup_id = Input::get('sup_id');
        $dev_id = Input::get('dev_id');
        $query = $model->select(DB::raw($field))
            ->where('is_warn', 1);
        if($this->user->role != 1 && $this->user->role != 2){
            $pro_id = $this->user->project[0];
        }
        if($this->user->role == 4 || $this->user->role == 5){
            $sup_id = $this->user->supervision_id;
        }
        if($this->user->role == 5){
            $sec_id = $this->user->section_id;
        }
        //var_dump($pro_id);
        if($pro_id){
            if(is_array($pro_id)){
                if(count($pro_id) == 1 && $pro_id[0] == 0){
                    $search['pro_id'] = 0;
                }else{
                    $query = $query->whereIn('project_id', $pro_id);
                    foreach ($pro_id as $key => $value) {
                        $url_para .= '&pro_id[]='.$value;
                    }
                }
            }else{
                $query = $query->where('project_id', $pro_id);
                $url_para .= '&pro_id='.$pro_id;
            }
            $search['pro_id'] = json_encode($pro_id);
        }else{
            $search['pro_id'] = 0;
        }
        if($sup_id){
            if(is_array($sup_id)){
                if(count($sup_id) == 1 && $sup_id[0] == 0){
                    $search['sup_id'] = 0;
                }else{
                    $query = $query->whereIn('supervision_id', $sup_id);
                    foreach ($sup_id as $key => $value) {
                        $url_para .= '&sup_id[]='.$value;
                    }
                }
            }else{
                $query = $query->where('supervision_id', $sup_id);
                $url_para .= '&sup_id='.$sup_id;
            }
            $search['sup_id'] = json_encode($sup_id);
        }else{
            $search['sup_id'] = 0;
        }
        if($sec_id){
            if(is_array($sec_id)){
                if(count($sec_id) == 1 && $sec_id[0] == 0){
                    $search['sec_id'] = 0;
                }else{
                    $query = $query->whereIn('section_id', $sec_id);
                    foreach ($sec_id as $key => $value) {
                        $url_para .= '&sec_id[]='.$value;
                    }
                }
            }else{
                $query = $query->where('section_id', $sec_id);
                $url_para .= '&sec_id='.$sec_id;
            }
            $search['sec_id'] = json_encode($sec_id);
        }else{
            $search['sec_id'] = 0;
        }
        if($dev_id){
            if(is_array($dev_id)){
                if(count($dev_id) == 1 && $dev_id[0] == 0){
                    $search['dev_id'] = 0;
                }else{
                    $query = $query->whereIn('device_id', $dev_id);
                    foreach ($dev_id as $key => $value) {
                        $url_para .= '&dev_id[]='.$value;
                    }
                }
            }else{
                $query = $query->where('device_id', $dev_id);
                $url_para .= '&dev_id='.$dev_id;
            }
            $search['dev_id'] = json_encode($dev_id);
        }else{
            $search['dev_id'] = 0;
        }
        switch ($type) {
            case '2':
                $query = $query->where('is_sec_deal', 1);
                break;
            case '3':
                $query = $query->where('is_sec_deal', 0);
                break;
            case '4':
                $query = $query->where('is_sup_deal', 1);
                break;
            case '5':
                $query = $query->where('is_sup_deal', 0);
                break;
            case '6':
                $query = $query->where('is_pro_deal', 0)
                    ->where('warn_level', 3);
                break;
        }
        $search['type'] = $type;

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<', strtotime($end_date)+86400);
            $url_para .= '&end_date='.$end_date;
        }
        $list = $query->with(['project'=>function($query){
            $query->select(['id','name']);
        },'section'=>function($query){
            $query->select(['id','name']);
        },'sup'=>function($query){
            $query->select(['id','name']);
        }, 'device'=>function($query){
            $query->select(['id','name','dcode','model','cat_id'])
                ->with('category');
        }, 'warn'])
            ->orderByRaw('time desc, id desc')
            ->paginate($this->ispage)
            ->toArray();

        $list['type'] = [
            '1'=>'全部',
            '2'=>'标段已处理',
            '3'=>'标段未处理',
            '4'=>'监理已处理',
            '5'=>'监理未处理',
            '6'=>'建设单位未处理',
        ];

        $list['search'] = $search;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['status'] = [
            '0'=>'未处理',
            '1'=>'已处理'
        ];
        $list['level']=[
            '1'=>'初级',
            '2'=>'中级',
            '3'=>'高级'
        ];
        $list['project'] = $project;
        $list['section'] = $section;
        $list['supervision'] = $supervision;
        $list['device'] = $device;
        $list['url'] = $url.$url_para;
        return view($view, $list);
    }

    /*获取设备生产/试验数据*/
    protected function getProductData($url, $view){
        //根据用户类型 获取对应的设备
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $field = ['id',
            'project_id',
            'supervision_id',
            'section_id',
            'cat_id',
            'name',
            'dcode',
            'model',
            'last_time'];
        if(!Input::get('pro_id') && !Input::get('sup_id') && !Input::get('sec_id') && ($this->user->role==2 || $this->user->role==1)){
            //获取所有拌和设备
            $query = Device::select($field);
            if(is_array($this->device_cat)){
                $query = $query->whereIn('cat_id', $this->device_cat);
            }else{
                $query = $query->where('cat_id', $this->device_cat);
            }
            $list = $query->with(['project'=>function($query){
                $query->select(['id','name']);
            },'section'=>function($query){
                $query->select(['id','name']);
            }, 'sup'=>function($query){
                $query->select(['id','name']);
            }, 'category'])
                ->orderByRaw($this->order)
                ->paginate($this->ispage)
                ->toArray();
        }else{
            $list = $this->getDevice($field, $pro_id, $sup_id, $sec_id);
        }

        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if($this->user->role==2 || $this->user->role==1){
            //添加全部选择
            $ztree_data = json_decode($tree_data['ztree_data'], true);
            array_unshift($ztree_data, ['id'=>0, 'name'=>'全部']);
            $tree_data['ztree_data'] = json_encode($ztree_data);
            if(!Input::get('pro_id') && !Input::get('sup_id') && !Input::get('sec_id')){
                $tree_data['ztree_name'] = '全部';
            }
        }

        return view($view, array_merge($list, $tree_data));
    }

    /*获取日/周/月统计信息*/
    protected function getStat($model, $field, $url, $view, $start_date, $end_date, $module_id){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }
        $dfield = ['id','section_id','name','dcode','model','cat_id'];
        $device = $this->getDevice($dfield, $pro_id, $sup_id, $sec_id, 0);
        if(!$device){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无设备信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        $url_para = '';

        $query = $model->select($field)
            ->where('module_id', $module_id);
        if(strrpos($view, 'snbhz.warn_report')){
            $query = $query->where('bj_num', '!=', 0);
        }
        if($pro_id){
            if($this->user->role != 1 && $this->user->role != 2){
                $pro_id = $this->user->project[0];
            }
            $query = $query->where('project_id', $pro_id);
        }
        if($sup_id){
            if($this->user->role == 4 || $this->user->role == 5){
                $sup_id = $this->user->supervision_id;
            }
            $query = $query->where('supervision_id', $sup_id);
        }
        if($sec_id){
            if($this->user->role == 5){
                $sec_id = $this->user->section_id;
            }
            $query = $query->where('section_id', $sec_id);
        }

        $device_id = Input::get('d_id');
        if($device_id){
            $search['d_id'] = $device_id;
            $query = $query->where('device_id', '=', $device_id);
            $url_para .= '&d_id='.$device_id;
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : $start_date;
        if($start_date){
            $search['start_date'] = $start_date;
            if(in_array('created_at', $field)){
                $query = $query->where('created_at', '>=', strtotime($start_date));
            }else{
                $query = $query->where('date', '>=', $start_date);
            }
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : $end_date;
        if($end_date){
            $search['end_date'] = $end_date;
            if(in_array('created_at', $field)){
                $query = $query->where('created_at', '<=', strtotime($end_date)+86400);
            }else{
                $query = $query->where('date', '<=', $end_date);
            }

            $url_para .= '&end_date='.$end_date;
        }

        $query = $query->with(['section'=>function($query){
            $query->select(['id','name']);
        },'sup'=>function($query){
            $query->select(['id','name']);
        }, 'device'=>function($query){
            $query->select(['id','name','dcode','model','cat_id'])
                ->with('category');
        }])
            ->orderByRaw('id desc');

        $d = Input::get('d');
        if($d == 'all'){
            $list['data'] = $query->get()->toArray();
        }else{
            $list = $query->paginate($this->ispage)
                ->toArray();
        }

        $list['search'] = $search;
        $list['device'] = $device;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if(stripos('?', $tree_data['url'])){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $tree_data['url'] = $url_para ? $tree_data['url'].$url_lj.$url_para : $tree_data['url'];
        return view($view, array_merge($list, $tree_data));
    }

    /*获取某设备的拌和/试验数据信息*/
    protected function getDataByDevice($d_id, $model, $field, $url, $ispage=1){
        $device_id = $this->getDeviceId();
        if(!in_array($d_id, $device_id)){
            return view('admin.error.no_info', ['info'=>'设备信息错误']);
        }

        $search = [];

        $query = $model->select($field)
            ->where('device_id', $d_id);
        $data_type = Input::get('data_type') ? Input::get('data_type') : 1;
        switch ($data_type) {
            case '2':
                $query = $query->where('is_warn', 0);
                break;
            case '3':
                $query = $query->where('is_warn', 1);
                break;
            case '4':
                $query = $query->where('is_warn', 1)
                    ->where(function($query){
                        $query->where('is_sup_deal', 0)
                            ->orWhere('is_sec_deal', 0)
                            ->orWhere(function($query){
                                $query->where('is_pro_deal', 0)
                                    ->where('warn_level', 3);
                            });;
                    });
                break;
            case '5':
                $query = $query->where(function($query){
                    $query->where('is_warn', 1)
                        ->where('is_sup_deal', 1)
                        ->where('is_sec_deal', 1)
                        ->where('warn_level', '<', 3);
                })
                    ->orWhere(function($query){
                        $query->where('is_warn', 1)
                            ->where('is_sup_deal', 1)
                            ->where('is_sec_deal', 1)
                            ->where('is_pro_deal', 1)
                            ->where('warn_level', '=', 3);
                    });
                break;
        }
        $url_para = 'data_type='.$data_type;

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<', strtotime($end_date)+86400);
            $url_para .= '&end_date='.$end_date;
        }

        $query = $query->with(['section'=>function($query){
            $query->select(['id','name'])
                ->with(['sup'=>function($query){
                    $query->select(['id','name']);
                }]);
        }])
            ->orderByRaw($this->order);
        if($ispage){
            $list = $query->paginate($this->ispage)
                ->toArray();
        }else{
            $list['data'] = $query->get()
                ->toArray();
        }

        $list['data_type'] = [
            '1'=>'全部',
            '2'=>'正常数据',
            '3'=>'报警数据',
            '4'=>'未处理报警',
            '5'=>'已处理报警'
        ];
        $list['search'] = $search;
        $list['search']['data_type'] = $data_type;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['d_id'] = $d_id;
        $list['pro_id'] = Input::get('pro_id');
        $list['url'] = ($url_para ? $url.'?'.$url_para : $url).'&pro_id='.Input::get('pro_id');
        return $list;
    }

    /*处理报警信息*/
    public function doDeal($id, $model, $deal_model, $url, $request, $info_url){
        if($request->isMethod('get')){
            $device_id = Input::get('d_id');
            if(!$id || !$device_id){
                $result = ['status'=>0,'info'=>'操作失败'];
                return view('admin.error.iframe_no_info', $result);
                // Response()->json($result);
            }

            //监理和标段,建设单位可以处理
            if($this->user->role !=3 && $this->user->role !=4 && $this->user->role !=5 ){
                $result = ['status'=>0,'info'=>'您没有权限处理'];
                return view('admin.error.iframe_no_info', $result);
            }

            //判断超过三天不予处理
            /*11月27最新修改，报警产生后超过8小时不再允许修改*/
            /*报警的时间为拌合站生产时间或试验室试验时间*/
            $last_time = time() - 86400*3;
            $is_cl = true;
            if(is_array($id)){
                $info = $model->select(['time','is_sec_deal','is_sup_deal','is_pro_deal'])->whereIn('id', $id)->get()->toArray();
                foreach ($info as $key => $value) {
                    if($value['time'] < $last_time){
                        $is_cl = false;
                        break;
                    }
                }
            }else{
                $info = $model->select(['time','is_sec_deal','is_sup_deal','is_pro_deal'])
                    ->where('id', $id)
                    ->first()
                    ->toArray();
                if($info['time'] < $last_time){
                    $is_cl = false;
                }
            }
            if(!$is_cl){
                $result = ['status'=>0,'info'=>'报警已超过三天，无法处理'];
                return view('admin.error.iframe_no_info', $result);
                //return Response()->json($result);
            }

            //根据处理顺序判断是否可以处理
            if(!is_array($id)){
                //监理处理过 标段不可更改处理
                if($this->user->role == 5){
                    if($info['is_sup_deal'] == 1){
                        $result = ['status'=>0,'info'=>'监理已处理，标段的处理信息不可更改'];
                        return view('admin.error.iframe_no_info', $result);
                    }
                }
                //标段没处理  监理不可处理，建设单位处理过 监理不可处理
                if($this->user->role == 4){
                    if(!$info['is_sec_deal']){
                        $result = ['status'=>0,'info'=>'标段处理后您才可处理'];
                        return view('admin.error.iframe_no_info', $result);
                    }
                }
                //监理没处理  建设单位不可处理
                if($this->user->role == 3){
                    if(!$info['is_sup_deal']){
                        $result = ['status'=>0,'info'=>'监理处理后您才可处理'];
                        return view('admin.error.iframe_no_info', $result);
                    }
                }
            }

            $data = [
                'url' => $url,
                'id' => $id,
                'd_id'=>$device_id
            ];
            return view('system.deal_info', $data);
        }

        $device_id = Input::get('d_id');
        if(!$id || !$device_id){
            $result = ['status'=>0,'info'=>'参数错误'];
            return Response()->json($result);
        }

        $info = Input::get('info');
        if(!$info){
            $result = ['status'=>0,'info'=>'请输入处理信息'];
            return Response()->json($result);
        }
        if($this->user->role == 3){ //建设单位处理
            $data = [
                'pro_info'=>$info,
                'pro_img' =>Input::get('thumb'),
                'pro_file' =>Input::get('file'),
                'pro_name'=>$this->user->name,
                'pro_time'=>time()
            ];
            $update_info['is_pro_deal'] = 1;
        }elseif($this->user->role == 4){ //驻地办 监理处理
            $data = [
                'sup_info'=>$info,
                'sup_img' =>Input::get('thumb'),
                'sup_file' =>Input::get('file'),
                'sup_name'=>$this->user->name,
                'sup_time'=>time()
            ];
            $update_info['is_sup_deal'] = 1;
        }elseif($this->user->role == 5){  //标段处理
            $data = [
                'sec_info'=>$info,
                'sec_img' =>Input::get('thumb'),
                'sec_file' =>Input::get('file'),
                'sec_name'=>$this->user->name,
                'sec_time'=>time()
            ];
            $update_info['is_sec_deal'] = 1;
        }
        $data['info_id'] = $id;
        $data['device_id'] = $device_id;
        $data['module_id'] = $this->module;
        try {
            if(is_array($id)){
                $model->whereIn('id', $id)->update($update_info);
                foreach ($id as $key => $value) {
                    $data['info_id'] = $value;
                    $this->addDealInfo($data, $deal_model);
                    //施工单位处理后给监理发送已处理通知
                    if($this->user->role == 5){
                        $this->sendDealNotice($info_url, $value, $model);
                    }
                }
                $result = ['status'=>1,'info'=>'处理成功','data'=>$id];
            }else{
                $model->where('id', $id)->update($update_info);
                $this->addDealInfo($data, $deal_model);
                //施工单位处理后给监理发送已处理通知
                if($this->user->role == 5){
                    $this->sendDealNotice($info_url, $id, $model);
                }
                $result = ['status'=>1,'info'=>'处理成功','id'=>$id];
            }
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'处理失败'];
        }

        return $result;
    }

    /*添加处理信息*/
    protected function addDealInfo($data, $model){
        $info = $model->where('info_id', $data['info_id'])
            ->where('device_id', $data['device_id'])
            ->where('module_id', $data['module_id'])
            ->first();
        if($info){
            $model->where('id', $info->id)->update($data);
        }else{
            $model->create($data);
        }
    }

    protected function doWarnSet($request, $with, $module_id, $url, $set_position, $set_info, $model, $user_model){
        if($request->isMethod('get')) {
            //if($this->user->position_id != $set_position){
            if (!$this->user->has_sh) {
                if(Input::get('uid') || Input::get('type') == 'add'){
                    $view = 'admin.error.iframe_no_info';
                }else{
                    $view = 'admin.error.no_info';
                }
                return view($view, ['info'=>$set_info]);
            }

            if(Input::get('uid')){
                //获取该用户信息
                $data = $user_model->leftJoin('warn_user_set', function($join) use ($module_id){
                    $join->on('user.id', '=', 'warn_user_set.user_id')
                        ->where('warn_user_set.module_id', '=', DB::raw($module_id));
                })
                    ->where('user.id', Input::get('uid'))
                    ->with(['project'=>function($query){
                        $query->select('id','name');
                    }, 'supervision'=>function($query){
                        $query->select('id','name');
                    }, 'section'=>function($query){
                        $query->select('id','name');
                    }, 'roles'=>function($query){
                        $query->select('id','display_name');
                    }, 'posi'=>function($query){
                        $query->select('id','name');
                    }, 'company'=>function($query){
                        $query->select('id','name');
                    }])
                    ->first()
                    ->toArray();
                $data['url'] = $url;
                return view('system.warn_set_edit', $data);
            }
            //添加报警人员
            if(Input::get('type') == 'add'){
                //获取可管理该标段的所有用户
                $data = $this->getUser($user_model, $module_id);
                if(!is_array($data)){
                    return $data;
                }
                $data['url'] = $url;
                return view('system.warn_set_add', $data);
            }

            //获取所有报警通知用户
            $data = $this->getNoticeUser($user_model, $url, $module_id);
            if(!is_array($data)){
                return $data;
            }
            $data['url'] = $url;
            return view('system.warn_set', $data);
        }

        //只能拌和站信息化管理员设置
        $result = ['status'=>0,'info'=>'参数错误'];
        //if($this->user->position_id != $set_position){
        if (!$this->user->has_sh) {
            $result = ['status'=>0,'info'=>$set_info];
            return Response()->json($result);
        }

        if(Input::get('user_id')){
            $id = Input::get('user_id');
            //$model = new Snbhz_user_warn;
            $data = $model->checkData();
            if($data['code'] == 1){
                $result = ['status'=>0,'info'=>$data['info']];
                return Response()->json($result);
            }
            try {
                $info = $model->select(['id'])->where('user_id', $id)->where('module_id', $this->module)->first();
                if($info){
                    $arr = ['cj_0'=>0,'cj_24'=>0,'cj_48'=>0,'zj_0'=>0,'zj_24'=>0,'zj_48'=>0,'gj_0'=>0,'gj_24'=>0,'gj_48'=>0];
                    unset($data['user_id']);
                    unset($data['_token']);
                    unset($data['code']);
                    unset($data['sec_id']);
                    unset($data['sup_id']);
                    unset($data['pro_id']);
                    if($data){
                        $data = array_merge($arr, $data);
                        $model->where('user_id', $id)->update($data);
                    }else{
                        $model->destroy($info['id']);
                    }
                }else{
                    $data['project_id'] = $data['pro_id'];
                    $data['supervision_id'] = $data['sup_id'];
                    $data['section_id'] = $data['sec_id'];
                    unset($data['sec_id']);
                    unset($data['sup_id']);
                    unset($data['pro_id']);
                    $data['module_id'] = $this->module;
                    $model->create($data);
                }
                $result = ['status'=>1,'info'=>'设置成功'];
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'设置失败'];
            }
        }
        return Response()->json($result);
    }

    /*获取可管理该标段的所有用户 项目 监理 标段用户*/
    protected function getUser($user_model, $module_id){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');

        if(!$sec_id){
            $tree_data['info'] = '先选择标段信息';
            return view('admin.error.no_info', $tree_data);
        }
        $role_id=Auth::user()->role;
        if($role_id ==4||$role_id ==5){
            $user_model=$user_model->where('role',$role_id);
        }
        //DB::connection()->enableQueryLog();
        $query = $user_model->select(['user.id','user.name','user.position','user.position_id','user_project.project_id']);



        if($sec_id){
            $info = Section::select(['id','project_id'])
                ->where('id', $sec_id)
                ->with(['sup'=>function($query){
                    $query->select(['id','name']);
                }])
                ->first()
                ->toArray();
            $pro = $info['project_id'];
            //获取标段所属监理
            $sup = '';
            if($sup_id){
                $sup = $sup_id;
            }else{
                $sup = $info['sup'][0]['id'];
            }
            //var_dump($pro);
            $query = $query->leftJoin('user_project', function($query) use($pro){
                $query->on('user.id', '=', 'user_project.user_id')
                    ->on('user_project.project_id', '=', DB::raw($pro));
            })
                ->where(function($query) use ($sec_id, $sup){
                    $query->where('user.section_id', $sec_id)
                        ->orWhere(function($query) use ($sup){
                            $query->where('user.supervision_id', $sup)
                                ->where('user.role', 4);
                        })
                        ->orWhere('user.role', 3);
                })
                ->whereNotNull('user_project.project_id');

        }
        $data['info'] = $query->where('user.status', 1)
            ->with(['module'=>function($query) use($module_id){
                $query->where('module_id', $module_id);
            },'posi','roles'])
            ->orderByRaw('user.position_id desc, user.id asc')
            ->get()
            ->toArray();

        $data['section_id'] = $sec_id;
        $data['project_id'] = $pro;
        $data['supervision_id'] = $sup;
        //$data['section'] = $section;
        return $data;
    }

    protected function getNoticeUser($user_model, $url, $module_id){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id && !$pro_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }

        $section = $this->getUserSection($pro_id, $sup_id);
        if(!$section){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无标段信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        $role_id=Auth::user()->role;
        if($role_id ==4 ||$role_id == 5){
            $user_model=$user_model->where('role',$role_id);
        }
        $query = $user_model->select(DB::raw('user.id,user.name,user.position,user.position_id,warn_user_set.*'));


        $sec_id = Input::get('sec_id') ? Input::get('sec_id') : $section[0]['id'];
        if($sec_id){
            $info = Section::select(['id','project_id'])
                ->where('id', $sec_id)
                ->with(['sup'=>function($query){
                    $query->select(['id','name']);
                }])
                ->first()
                ->toArray();
//            dd($info);
            $pro = $info['project_id'];
            //获取标段所属监理
            $sup = '';
            if($sup_id){
                $sup = $sup_id;
            }else{
                $sup = $info['sup'][0]['id'];
            }
            $query = $query->leftJoin('user_project', function($query) use($pro){
                $query->on('user.id', '=', 'user_project.user_id')
                    ->on('user_project.project_id', '=', DB::raw($pro));
            })
                ->leftJoin('warn_user_set', function($join) use($module_id) {
                    $join->on('user.id', '=', 'warn_user_set.user_id')
                        ->where('warn_user_set.module_id', '=', DB::raw($module_id));
                })
                ->whereNotNull('user_project.project_id')
                ->whereNotNull('warn_user_set.id')
                ->where(function($query) use ($sec_id, $sup){
                    $query->where('user.section_id', $sec_id)
                        ->orWhere(function($query) use ($sup){
                            $query->where('user.supervision_id', $sup)
                                ->where('user.role', 4);
                        })
                        ->orWhere('user.role', 3);
                });

            $search['sec_id'] = $sec_id;
        }
        $data['info'] = $query->where('user.status', 1)
            ->with(['module'=>function($query) use($module_id){
                $query->where('module_id', $module_id);
            },'posi'])
            ->orderByRaw('user.position_id desc, user.id asc')
            ->get()
            ->toArray();
        //var_dump($data);
        $data['pro_id'] = $pro_id;
        $data['sec_id'] = $sec_id;
        $data['sup_id'] = $sup_id;
        $data['search'] = $search;
        $data['section'] = $section;
        $data['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        return array_merge($data, $tree_data);
    }

    /*错误信息处理报告*/
    protected function getClbg($model, $field, $view, $left_table){
        $id = Input::get('id');
        if(!$id){
            return '';
        }
        $module_id = $this->module;
        $data = $model->select(DB::raw($field))
            ->leftJoin('warn_deal_info', function($join) use($left_table, $module_id){
                $join->on($left_table.'.id', '=', 'warn_deal_info.info_id')
                    ->on($left_table.'.device_id', '=', 'warn_deal_info.device_id')
                    ->where('warn_deal_info.module_id', '=', DB::raw($module_id));
            })
            ->where($left_table.'.id', $id)
            ->where($left_table.'.is_warn', 1)
            ->with(['project'=>function($query){
                $query->select(['id','name']);
            },'sup'=>function($query){
                $query->select(['id','name']);
            },'section'=>function($query){
                $query->select(['id','name']);
            },'device'=>function($query){
                $query->select(['id','name']);
            }])
            ->first()
            ->toArray();
        $data['level'] = [
            '1'=>'初级',
            '2'=>'中级',
            '3'=>'高级',
            ''=>'初级'
        ];
        $data['symc'] = Config()->get('common.sylx');
        return view($view, $data);
    }

    /*错误信息处理台账  默认显示建设单位 监理 标段要处理的全部列表  集团用户只显示项目对比图*/
    protected function getTzlb($model, $field, $url, $view, $left_table){
        $sec_id = Input::get('sec_id');

        //用户可访问的section
        $section = $this->getUserSection();
        if(!$section){
            //$tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无标段信息';
            return view('admin.error.no_info', $tree_data);
        }
        $url_para = '';

        //获取信息
        $module_id = $this->module;
        $query = $model->select(DB::raw($field))
            ->leftJoin('warn_deal_info', function($join) use($left_table, $module_id){
                $join->on($left_table.'.id', '=', 'warn_deal_info.info_id')
                    ->on($left_table.'.device_id', '=', 'warn_deal_info.device_id')
                    ->where('warn_deal_info.module_id', '=', DB::raw($module_id));
            })
            ->where('is_warn', 1);

        //判断条件
        if($this->user->role != 1 && $this->user->role != 2){
            $query = $query->where($left_table.'.project_id', $this->user->project[0]);
        }
        if($this->user->role == 4 || $this->user->role == 5){
            $query = $query->where($left_table.'.supervision_id', $this->user->supervision_id);
        }
        if($this->user->role == 5){
            $query = $query->where($left_table.'.section_id', $this->user->section_id);
        }

        if($sec_id){
            $query = $query->where($left_table.'.section_id', $sec_id);
            $search['sec_id'] = $sec_id;
            $url_para .= '&sec_id='.$sec_id;
        }
        //如果是建设单位只显示自己需要处理的 即高级报警
        /*
        if($this->user->role == 3){
            $query = $query->where(function($query) use ($left_table){
                $query->where($left_table.'.warn_level', 3)
                    ->orWhere($left_table.'.warn_sx_level', 3);
            });

            //获取各合同段台账图表
        }
        */

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where($left_table.'.time', '>=', strtotime($start_date));
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where($left_table.'.time', '<=', strtotime($end_date)+86400);
            $url_para .= '&end_date='.$end_date;
        }

        $query = $query->with(['project'=>function($query){
            $query->select(['id','name']);
        },'sup'=>function($query){
            $query->select(['id','name']);
        },'section'=>function($query){
            $query->select(['id','name']);
        },'device'=>function($query){
            $query->select(['id','name']);
        }])
            ->orderByRaw($left_table.'.time desc');
        $d = Input::get('d');
        if($d == 'all'){
            $data['data'] = $query->get()->toArray();
        }else{
            $data = $query->paginate($this->ispage)
                ->toArray();
        }
        $data['level'] = [
            '1'=>'初级',
            '2'=>'中级',
            '3'=>'高级',
            ''=>'初级'
        ];

        $data['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        if($d){
            $data['sec_name'] = '';
            if($sec_id){
                $data['sec_name'] = Section::where('id', $sec_id)->pluck('name');
            }
            return view($view.'_export', array_merge($data, $search));
        }

        $data['search'] = $search;
        $data['section'] = $section;
        $data['symc'] = Config()->get('common.sylx');

        //$tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
        if(stripos('?', $url)){
            $url_lj = '&';
        }else{
            $url_lj = '?';
        }
        $data['url'] = $url_para ? $url.$url_lj.$url_para : $url;

        return view($view, $data);
    }

    /*统计管理人员每天登录次数*/
    public function getLoginStat($user_model, $url, $module_id, $view){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        if(!$sup_id && !$sec_id && !$pro_id){
            $pro_id = $pro_id ? $pro_id : $this->project_id;
        }

        $section = $this->getUserSection($pro_id, $sup_id);
        if(!$section){
            $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);
            $tree_data['info'] = '无标段信息';
            return view('admin.error.tree_no_info', $tree_data);
        }
        //DB::connection()->enableQueryLog();
        $query = $user_model->select(DB::raw('user.id,user.name,user.position_id,user.role,company.name as company_name,user_project.project_id, count(log.id) as num'));
        $sec_id = Input::get('sec_id') ? Input::get('sec_id') : $section[0]['id'];

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
        }

        if($sec_id){
            $info = Section::select(['id','project_id','name'])
                ->where('id', $sec_id)
                ->with(['sup'=>function($query){
                    $query->select(['id','name']);
                }])
                ->first()
                ->toArray();
            $sec_name = $info['name'];
            $pro = $info['project_id'];
            //获取标段所属监理
            $sup = '';
            if($sup_id){
                $sup = $sup_id;
            }else{
                $sup = $info['sup'][0]['id'];
            }
            $query = $query->leftJoin('user_project', function($query) use($pro){
                $query->on('user.id', '=', 'user_project.user_id')
                    ->on('user_project.project_id', '=', DB::raw($pro));
            })
                ->leftJoin('log', function($query) use ($start_date, $end_date){
                    $query->on('user.id', '=', 'log.user_id')
                        ->where('log.type', '=', 'l')
                        ->where('log.created_at', '>=', strtotime($start_date))
                        ->where('log.created_at', '<=', strtotime($end_date));
                })
                ->leftJoin('company', function($query) {
                    $query->on('user.company_id', '=', 'company.id');
                })
                ->where(function($query) use ($sec_id, $sup){
                    $query->where('user.section_id', $sec_id)
                        ->orWhere(function($query) use ($sup){
                            $query->where('user.supervision_id', $sup)
                                ->where('user.role', 4);
                        })
                        ->orWhere('user.role', 3);
                })
                ->whereNotNull('user_project.project_id');

        }
        $data['info'] = $query->where('user.status', 1)
            ->with(['module'=>function($query) use($module_id){
                $query->where('module_id', $module_id);
            },'posi','roles'])
            ->groupBy('user.id')
            ->orderByRaw('user.position_id desc, user.id asc')
            ->get()
            ->toArray();

        $data['section'] = $section;
        $data['search'] = $search;
        $data['search']['sec_id'] = $sec_id;
        $data['sec_name'] = $sec_name;
        $data['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id);

        return view($view, array_merge($data, $tree_data));
    }

    protected function getDealInfo($model, $info_id, $device_id){
        $data = $model->where('info_id', $info_id)
            ->where('module_id', $this->module)
            ->where('device_id', $device_id)
            ->first();
        return $data;
    }

    /*获取台账图表 默认显示一周*/
    protected function getTzlbPie($model, $url, $view, $left_table){
        $module_id = $this->module;
        $query = $model->select(DB::raw('count(*) as num, project_id, section_id'))
            ->leftJoin('warn_deal_info', function($join) use($left_table, $module_id){
                $join->on($left_table.'.id', '=', 'warn_deal_info.info_id')
                    ->on($left_table.'.device_id', '=', 'warn_deal_info.device_id')
                    ->where('warn_deal_info.module_id', '=', DB::raw($module_id));
            })
            ->where('is_warn', 1);

        if($this->user->role == 3){
            $chart_title = "各标段处理台账";
            $query = $query->where('project_id', '=', $this->user->project[0]);
            //获取所有标段
            $info = $this->getUserSection();
            $column = 'section_id';
            $data['type'] = 'sec';
        }else{
            $data['type'] = '';
            $chart_title = "各项目处理台账";
            //获取所有项目
            $info = Project::select(['id','name'])->orderByRaw('id asc')->get()->toArray();
            $column = 'project_id';
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<=', strtotime($end_date)+86400);
        }

        $list = $query->groupBy($column)
            ->orderByRaw($column.' asc')
            ->get()
            ->toArray();

        $data['chart_title'] = $chart_title.' '.$start_date.'~'.$end_date;
        $series = $this->getPieChart($info, $list, $column);

        $data['chart'] = json_encode($series);
        $data['url'] = $url;
        $data['search'] = $search;
        //var_dump($data);
        return view($view, $data);
    }

    /*试验次数/报警次数统计*/
    protected function getSectionReport($model, $title, $url, $view, $type)
    {
        $query = $model->select(DB::raw('count(*) as num, project_id, section_id, device_id'));
        if($type == 'warn'){
            $query = $query->where('is_warn', 1);
        }
        //如果是集团用户 显示各项目实验/报警次数对比图
        if($this->user->role == 2 || $this->user->role == 1){
            $column = 'project_id';
            $title = '各项目'.$title.'次数统计';
            //获取所有项目
            $info = Project::select(['id','name'])->orderByRaw('id asc')->get()->toArray();
        }
        //如果是项目用户 点击显示各标段实验/报警次数对比图
        if($this->user->role == 3){
            $query = $query->where('project_id', '=', $this->user->project[0]);
            $column = 'section_id';
            $title = '各标段'.$title.'次数统计';
            //获取所有标段
            $info = $this->getUserSection();
        }

        //如果是监理用户 点击显示实验/报警次数对比图，如果只有一个标段显示  各设备试验/报警次数
        if($this->user->role == 4){
            $query = $query->where('supervision_id', '=', $this->user->supervision_id);
            $column = 'section_id';
            $title = '各标段'.$title.'次数统计';
            //获取所有标段
            $info = $this->getUserSection();
            if(count($info) == 1){
                $query = $query->where('section_id', '=', $info[0]['id']);
                $column = 'device_id';
                $title = '各设备'.$title.'次数统计';
                //获取所有设备
                $info = $this->getDevice(['id','name','model','section_id','cat_id'], $this->user->project[0], $this->user->supervision_id, '', 0);
            }
        }

        //如果是标段用户 点击显示各设备试验/报警次数
        if($this->user->role == 5){
            $query = $query->where('section_id', '=', $this->user->section_id);
            $column = 'device_id';
            $title = '各设备'.$title.'次数统计';
            //获取所有设备
            $info = $this->getDevice(['id','name','model','section_id','cat_id'], $this->user->project[0], $this->user->supervision_id, $this->user->section_id, 0);
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<=', strtotime($end_date)+86400);
        }

        $list = $query->groupBy($column)
            ->orderByRaw($column.' asc')
            ->get()
            ->toArray();


        $data['chart_title'] = $title.' '.$start_date.'~'.$end_date;
        $series = $this->getPieChart($info, $list, $column);

        $data['chart'] = json_encode($series);
        $data['url'] = $url;
        $data['search'] = $search;
        $data['type'] = '';

        return view($view, $data);
    }

    /*各类型试验次数/报警次数统计*/
    protected function getTypeReport($model, $title, $url, $view, $type)
    {
        $title = '各类型'.$title.'次数统计';
        $query = $model->select(DB::raw('count(*) as num, project_id, section_id, device_id, sylx'));
        if($type == 'warn'){
            $query = $query->where('is_warn', 1);
        }
        //如果是集团用户 显示各项目实验/报警次数对比图
        if($this->user->role == 2 || $this->user->role == 1){
            $column = 'project_id';
            //获取所有项目
            $info = Project::select(['id','name'])->orderByRaw('id asc')->get()->toArray();
        }
        //如果是项目用户 点击显示各标段实验/报警次数对比图
        if($this->user->role == 3){
            $query = $query->where('project_id', '=', $this->user->project[0]);
            $column = 'section_id';
            //获取所有标段
            $info = $this->getUserSection();
        }

        //如果是监理用户 点击显示实验/报警次数对比图，如果只有一个标段显示  各设备试验/报警次数
        if($this->user->role == 4){
            $query = $query->where('supervision_id', '=', $this->user->supervision_id);
            $column = 'section_id';
            //获取所有标段
            $info = $this->getUserSection();
            if(count($info) == 1){
                $query = $query->where('section_id', '=', $info[0]['id']);
                $column = 'device_id';
                //获取所有设备
                $info = $this->getDevice(['id','name','model','section_id','cat_id'], $this->user->project[0], $this->user->supervision_id, '', 0);
            }
        }

        //如果是标段用户 点击显示各设备试验/报警次数
        if($this->user->role == 5){
            $query = $query->where('section_id', '=', $this->user->section_id);
            $column = 'device_id';
            //获取所有设备
            $info = $this->getDevice(['id','name','model','section_id','cat_id'], $this->user->project[0], $this->user->supervision_id, $this->user->section_id, 0);
        }

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<=', strtotime($end_date)+86400);
        }

        $list = $query->with(['project'=>function($query){
            $query->select(['id','name']);
        },'device'=>function($query){
            $query->select(['id','name','model','cat_id'])
                ->with('category');
        },'section'=>function($query){
            $query->select(['id','name']);
        }])
            ->groupBy([$column, 'sylx'])
            ->orderByRaw('sylx asc')
            ->get()
            ->toArray();
        //var_dump($list);
        $categories = Config()->get('common.sylx');
        unset($categories[4]);
        unset($categories[5]);
        unset($categories[8]);
        $testTypes = [];
        $testTypeIds = [];
        $index = 0;
        foreach($categories as $key => $value) {
            $testTypeIds[$index] = $key;
            $testTypes[$index] = $value;
            $index++;
        }
        foreach ($info as $key => $value) {
            if($column == 'device_id'){
                $series[$key]['name'] = $value['model'].$value['category']['name'];
            }else {
                $series[$key]['name'] = $value['name'];
            }
            foreach ($testTypeIds as $ck => $cv) {
                $is_has = false;
                foreach ($list as $k => $v) {
                    if($value['id'] == $v[$column] && $v['sylx'] == $cv){
                        $series[$key]['data'][$ck] = $v['num'];
                        $is_has = true;
                    }
                }
                if(!$is_has){
                    $series[$key]['data'][$ck] = 0;
                }
            }
        }

        $data['chart_title'] = $title;
        $chart = [
            'sub_title'=>$start_date.'~'.$end_date,
            'categories'=>$testTypes,
            'series'=>$series,
        ];
        //var_dump($chart);
        $data['chart'] = json_encode($chart);
        $data['url'] = $url;
        $data['search'] = $search;
        return view($view, $data);
    }

    protected function getPieChart($info, $list, $column){
        $series = [];
        foreach ($info as $key => $value) {
            if($column == 'device_id'){
                $series[$key]['name'] = $value['model'].$value['category']['name'];
            }else{
                $series[$key]['name'] = $value['name'];
            }
            $is_has = false;
            foreach ($list as $k => $v) {
                if($value['id'] == $v[$column]){
                    $series[$key]['y'] = $v['num'];
                    $is_has = true;
                }
            }
            if(!$is_has){
                $series[$key]['y'] = 0;
            }
        }

        return $series;
    }

    /*实验数据*/
    public function getLabDataStat($model, $url, $view, $title){
        $query = $model;
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $device_id = Input::get('device_id');
        //如果是集团用户显示所有的设备当天试验数 报警数 处理数
        if($this->user->role == 1 || $this->user->role == 2){

        }

        //项目用户显示该项目所有的设备当天试验数 报警数 处理数
        if($this->user->role == 3){
            $pro_id = $this->user->project[0];
        }

        //监理用户显示该监理所有的设备当天试验数 报警数 处理数
        if($this->user->role == 4){
            $sup_id = $this->user->supervision_id;
        }

        //标段用户显示该标段所有的设备当天试验数 报警数 处理数
        if($this->user->role == 5){
            $sec_id = $this->user->section_id;
        }

        if($pro_id){
            $query = $query->where('project_id', $pro_id);
        }

        if($sup_id){
            $query = $query->where('supervision_id', $sup_id);
        }

        if($sec_id){
            $query = $query->where('section_id', $sec_id);
        }

        if($device_id){
            $query = $query->where('device_id', $device_id);
        }

        $time = strtotime(date('Y-m-d'));
        $query = $query->where('time', '>=', $time)
            ->where('time', '<', $time+86400);
        //当天报警数
        $warn_query = clone $query;
        $list['warn_num'] = $warn_query->where('is_warn', 1)->count();
        //当天试验数
        $list['sc_num'] = $query->count();
        //当天处理数
        $deal_query = clone $query;
        $list['deal_warn'] = $deal_query->where(function($query){
            $query->where(function($query){
                $query->where('is_sec_deal', 1)
                    ->where('is_sup_deal', 1)
                    ->where('warn_level', '<', 3);
            })
                ->orWhere(function($query){
                    $query->where('is_sec_deal', 1)
                        ->where('is_sup_deal', 1)
                        ->where('is_pro_deal', 1)
                        ->where('warn_level', 3);
                });
        })
            ->count();

        $tree_data = $this->getPageTreeDataAndUrl($url, $pro_id, $sup_id, $sec_id, 1);
        if($this->user->role==2 || $this->user->role==1){
            //添加全部选择
            $ztree_data = json_decode($tree_data['ztree_data'], true);
            array_unshift($ztree_data, ['id'=>0, 'name'=>'全部']);
            $tree_data['ztree_data'] = json_encode($ztree_data);
            if(!Input::get('pro_id') && !Input::get('sup_id') && !Input::get('sec_id') && !Input::get('device_id')){
                $tree_data['ztree_name'] = '全部';
                $tree_data['tree_key'] = '';
                $tree_data['tree_value'] = 0;
            }
        }
        $data['chart_title'] = $tree_data['ztree_name'].$title;
        $series = [
            0=>['name'=>'试验数', 'data'=>[$list['sc_num']]],
            1=>['name'=>'报警数', 'data'=>[$list['warn_num']]],
            2=>['name'=>'处理数', 'data'=>[$list['deal_warn']]],
        ];
        $data['chart'] = json_encode(['sub_title'=>'', 'categories'=>[$tree_data['ztree_name']], 'series'=>$series]);
        $data['pro_id'] = $pro_id;
        $data['sup_id'] = $sup_id;
        $data['sec_id'] = $sec_id;
        $data['device_id'] = $device_id;
        return view($view, array_merge($data, $tree_data));
    }

    /*获取试验数据信息*/
    protected function getLabInfo($model, $field, $url,$all_sylx){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');
        $sec_id = Input::get('sec_id');
        $device_id = Input::get('device_id');

        $query = $model->select($field)->whereIn('sylx',$all_sylx);

        if($pro_id){
            $query = $query->where('project_id', $pro_id);
        }
        if($sup_id){
            $query = $query->where('supervision_id', $sup_id);
        }
        if($sec_id){
            $query = $query->where('section_id', $sec_id);
        }
        if($device_id){
            $query = $query->where('device_id', $device_id);
        }
        $data_type = Input::get('data_type') ? Input::get('data_type') : 1;
        switch ($data_type) {
            case '2':
                $query = $query->where('is_warn', 0);
                break;
            case '3':
                $query = $query->where('is_warn', 1);
                break;
            case '4':
                $query = $query->where('is_warn', 1)
                    ->where(function($query){
                        $query->where('is_sup_deal', 0)
                            ->orWhere('is_sec_deal', 0)
                            ->orWhere(function($query){
                                $query->where('is_pro_deal', 0)
                                    ->where('warn_level', 3);
                            });;
                    });
                break;
            case '5':
                $query = $query->where(function($query){
                    $query->where('is_warn', 1)
                        ->where('is_sup_deal', 1)
                        ->where('is_sec_deal', 1)
                        ->where('warn_level', '<', 3);
                })
                    ->orWhere(function($query){
                        $query->where('is_warn', 1)
                            ->where('is_sup_deal', 1)
                            ->where('is_sec_deal', 1)
                            ->where('is_pro_deal', 1)
                            ->where('warn_level', '=', 3);
                    });
                break;
        }
        $url_para = 'data_type='.$data_type;

        $start_date = Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime('-6 day'));
        if($start_date){
            $search['start_date'] = $start_date;
            $query = $query->where('time', '>=', strtotime($start_date));
            $url_para .= '&start_date='.$start_date;
        }

        $end_date = Input::get('end_date') ? Input::get('end_date') : date('Y-m-d');
        if($end_date){
            $search['end_date'] = $end_date;
            $query = $query->where('time', '<', strtotime($end_date)+86400);
            $url_para .= '&end_date='.$end_date;
        }

        $list = $query->with(['section'=>function($query){
            $query->select(['id','name'])
                ->with(['sup'=>function($query){
                    $query->select(['id','name']);
                }]);
        }])
            ->with('detail','gjsydetail')
            ->orderByRaw('time desc, id desc')
            ->paginate($this->ispage)
            ->toArray();
        $list['data_type'] = [
            '1'=>'全部',
            '2'=>'正常数据',
            '3'=>'报警数据',
            '4'=>'未处理报警',
            '5'=>'已处理报警'
        ];
        $list['search'] = $search;
        $list['search']['data_type'] = $data_type;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['pro_id'] = $pro_id;
        $list['sup_id'] = $sup_id;
        $list['sec_id'] = $sec_id;
        $list['device_id'] = $device_id;
        $list['url'] = ($url_para ? $url.'?'.$url_para : $url).'&pro_id='.$pro_id.'&sup_id='.$sup_id.'&sec_id='.$sec_id.'&device_id='.$device_id;
        return $list;
    }

    public function getCommonSelectVal($type){
        if($type == 'pro'){ //获取对应监理 标段 设备
            $pro_id = Input::get('pro_id');
            if(count($pro_id)==1 && $pro_id[0]==0){
                $data['sup_id'] = Supervision::select(['id', 'name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
                $data['sec_id'] = Section::select(['id', 'name'])->orderByRaw('project_id asc, id asc')->get()->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
            }else{
                $data['sup_id'] = Supervision::select(['id', 'name'])->whereIn('project_id', $pro_id)->orderByRaw('id asc')->get()->toArray();
                $data['sec_id'] = Section::select(['id', 'name'])->whereIn('project_id', $pro_id)->orderByRaw('id asc')->get()->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id'])->whereIn('project_id', $pro_id);
            }
        }
        if($type == 'sup'){ //获取对应标段 设备
            $sup_id = Input::get('sup_id');
            if(count($sup_id)==1 && $sup_id[0]==0){
                $info = Supervision::select(['id', 'name']);
                $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
                if($this->user->role == 3){
                    $info = $info->where('project_id', $this->user->project[0]);
                    $query = $query->where('project_id', $this->user->project[0]);
                }
                $info = $info->with(['sec'=>function($query){
                    $query->select(['id','name']);
                }])
                    ->get()
                    ->toArray();
            }else{
                $info = Supervision::whereIn('id', $sup_id)
                    ->with(['sec'=>function($query){
                        $query->select(['id','name']);
                    }])
                    ->get()
                    ->toArray();
                $query = Device::select(['id', 'name', 'model','dcode','cat_id'])->whereIn('supervision_id', $sup_id);
            }
            foreach ($info as $key => $value) {
                foreach ($value['sec'] as $k => $v) {
                    $data['sec_id'][] = $v;
                }
            }
        }
        if($type == 'sec'){ //获取对应设备
            $sec_id = Input::get('sec_id');
            $query = Device::select(['id', 'name', 'model','dcode','cat_id']);
            if(count($sec_id)==1 && $sec_id[0]==0){
                if($this->user->role == 3){
                    $query = $query->where('project_id', $this->user->project[0]);
                }
                if($this->user->role == 4){
                    $query = $query->where('supervision_id', $this->user->supervision_id);
                }
                if($this->user->role == 5){
                    $query = $query->where('section_id', $this->user->section_id);
                }
            }else{
                $query = $query->whereIn('section_id', $sec_id);
            }
        }
        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }
        $data['dev_id'] = $query->with('category')->orderByRaw('id asc')->get()->toArray();
        $result = ['status'=>1, 'info'=>'获取成功', 'data'=>$data];
        return Response()->json($result);
    }

    //施工单位处理后给监理发送已处理通知
    protected function sendDealNotice($info_url, $info_id, $info_model){
        //报警的信息
        $info = $info_model->select(['id','project_id','supervision_id','section_id','device_id','warn_info','time','warn_level','warn_sx_level'])
            ->where('id', $info_id)
            ->where('is_warn', 1)
            ->with(['device'=>function($query){
                $query->select(['id','name','dcode','model']);
            }])
            ->first()
            ->toArray();
        if(!$info){
            return '';
        }
        $level = $info['warn_level'] > $info['warn_sx_level'] ? $info['warn_level'] : $info['warn_sx_level'];
        switch ($level) {
            case 1:
                $level = '初级';
                $column = 'cj_0';
                break;
            case 2:
                $level = '中级';
                $column = 'zj_0';
                break;
            case 3:
                $level = '高级';
                $column = 'gj_0';
                break;
            default:
                $level = '初级';
                $column = 'cj_0';
                break;
        }
        //查找可以接收信息的监理
        $user = Warn_user_set::select(DB::raw('user.id,user.name,user.phone,user.openid'))
            ->leftJoin('user', function($join){
                $join->on('user.id', '=', 'warn_user_set.user_id')
                    ->where('user.role', '=', 4)
                    ->where('user.status', '=', 1);
            })
            ->where('warn_user_set.project_id', '=', $info['project_id'])
            ->where('warn_user_set.supervision_id', '=', $info['supervision_id'])
            ->where('warn_user_set.section_id', '=', $info['section_id'])
            ->where('warn_user_set.module_id', '=', $this->module)
            ->where('warn_user_set.'.$column, '=', 1)
            ->whereNotNull('user.name')
            ->orderByRaw('warn_user_set.id asc')
            ->get()
            ->toArray();
        Log::info($user);
        if($user){
            foreach ($user as $key => $value) {
                $temp_param = [
                    'dcode'=>$info['device']['name'],
                    'time'=>date('Y-m-d H:i:s',$info['time']),
                    'info'=>$info['warn_info']
                ];
                if($value['phone']){
                    Log::info($temp_param);
                    $res = (new SendSms)->send($value['phone'], $temp_param, 'SMS_126351111');
                    Log::info('sendDealNotice SendSms '.$value['phone']);
                    Log::info(json_encode($res));
                }
                if($value['openid']){
                    $temp_param['first'] = '设备发生报警,标段已处理';
                    $temp_param['time'] = $info['time'];
                    $temp_param['level'] = $level;
                    $temp_param['url'] = $info_url.'?id='.$info_id;
                    $res = (new SendWechat)->sendBj($value['openid'], $temp_param);
                    Log::info('sendDealNotice SendWechat');
                    Log::info($res);
                }
            }
        }
    }

    /*获取当天及时报警信息*/
    protected function getNowWarn($model,$field,$start_date,$end_date)
    {

        $project = [];
        $section = [];

        $query = Device::select(['id', 'name', 'dcode','model','cat_id']);

        //项目用户显示选择监理 标段
        if($this->user->role == 3){
            $supervision = Supervision::select(['id','name'])->where('project_id', $this->user->project[0])->orderByRaw('id asc')->get()->toArray();
            $section = $this->getUserSection();
            $query = $query->where('project_id', $this->user->project[0]);
        }
        //监理只显示选择标段
        if($this->user->role == 4){
            $supervision = '';
            $section = $this->getUserSection();
            $query = $query->where('project_id', $this->user->project[0])
                ->where('supervision_id', $this->user->supervision_id);
        }
        if($this->user->role == 5){
            $supervision = '';
            $section = '';
            $query = $query->where('project_id', $this->user->project[0])
                ->where('supervision_id', $this->user->supervision_id)
                ->where('section_id', $this->user->section_id);
        }
        if(is_array($this->device_cat)){
            $query = $query->whereIn('cat_id', $this->device_cat);
        }else{
            $query = $query->where('cat_id', $this->device_cat);
        }
        $device = $query->with('category')->orderByRaw('id asc')->get()->toArray();


        //根据用户类型 获取对应的设备的报警信息
        $pro_id = $this->user->project[0];
        $sec_id = $this->user->section_id;
//        return $sec_id;
        $sup_id = Input::get('sup_id');
        $dev_id = Input::get('dev_id');
        $query = $model->select(DB::raw($field))
            ->where('is_warn', 1);
        if($this->user->role != 1 && $this->user->role != 2){
            $pro_id = $this->user->project[0];
        }
        if($this->user->role == 4 || $this->user->role == 5){
            $sup_id = $this->user->supervision_id;
        }
        if($this->user->role == 5){
            $sec_id = $this->user->section_id;
        }
        if($pro_id){
            if(is_array($pro_id)){
                if(count($pro_id) == 1 && $pro_id[0] == 0){
                    $search['pro_id'] = 0;
                }else{
                    $query = $query->whereIn('project_id', $pro_id);

                }
            }else{
                $query = $query->where('project_id', $pro_id);

            }
            $search['pro_id'] = json_encode($pro_id);
        }else{
            $search['pro_id'] = 0;
        }
        if($sup_id){
            if(is_array($sup_id)){
                if(count($sup_id) == 1 && $sup_id[0] == 0){
                    $search['sup_id'] = 0;
                }else{
                    $query = $query->whereIn('supervision_id', $sup_id);
                }
            }else{
                $query = $query->where('supervision_id', $sup_id);

            }
            $search['sup_id'] = json_encode($sup_id);
        }else{
            $search['sup_id'] = 0;
        }
        if($sec_id){
            if(is_array($sec_id)){
                if(count($sec_id) == 1 && $sec_id[0] == 0){
                    $search['sec_id'] = 0;
                }else{
                    $query = $query->whereIn('section_id', $sec_id);
                }
            }else{
                $query = $query->where('section_id', $sec_id);
            }
        }
        if(isset($dev_id)){
            if(is_array($dev_id)){
                if(count($dev_id) == 1 && $dev_id[0] == 0){
                    $search['dev_id'] = 0;
                }else{
                    $query = $query->whereIn('device_id', $dev_id);

                }
            }else{
                $query = $query->where('device_id', $dev_id);
            }
            $search['dev_id'] = json_encode($dev_id);
        }else{
            $search['dev_id'] = 0;
        }
        //如果是项目公司用户，只显示高级报警
        if($this->user->role==3||$this->user->role==1){
            $query=$query->where('is_sup_deal',1)
                         ->where('is_sec_deal',1)
                         ->where('is_pro_deal',0)
                         ->where(function($query){
                             $query->where('warn_level',3)
                                   ->orWhere('warn_sx_level',3);
                         });
        }
        if($this->user->role==4){

             $query=$query->where('is_sup_deal',0)->where('is_sec_deal',1);
        }
        if($this->user->role==5){
            $query=$query->where('is_sec_deal',0)->where('is_sup_deal',0);
        }

        $start_date = $start_date ? $start_date : date('Y-m-d', strtotime('-3 day'));
        if($start_date){
            $query = $query->where('time', '>=', strtotime($start_date));
        }

        $end_date = $end_date ? $end_date : date('Y-m-d');
        if($end_date){

            $query = $query->where('time', '<', strtotime($end_date)+86400);
        }
        $list['data'] = $query->with(['project'=>function($query){
            $query->select(['id','name']);
        },'section'=>function($query){
            $query->select(['id','name']);
        },'sup'=>function($query){
            $query->select(['id','name']);
        }, 'device'=>function($query){
            $query->select(['id','name','dcode','model','cat_id'])
                ->with('category');
        }, 'warn'])
            ->orderByRaw('time desc, id desc')
            ->get()
            ->toArray();
        $list['device'] = $device;

        return $list;
    }
    /*张拉和压浆模块中在实时视频页面获取数据*/
    protected function getZyData($device_id,$model,$ispage,$url)
    {
       $type=Input::get('type');
       $start_date=Input::get('start_date');
       $end_date=Input::get('end_date');
       $url=$url.'?device_id='.$device_id;

       if(isset($type)){
           $search['type']=$type;
           $url=$url.'&type='.$type;
           if($type==0){
               $model=$model->where([]);

           }
           if($type==1){
               $model=$model->where('is_warn',0);
           }
           if($type==2){
               $model=$model->where('is_warn',1);
           }
           if($type==3){
               $model=$model->where('is_warn',1)
                            ->where(function($query){
                               $query->where('is_sec_deal',0)
                                     ->orWhere(function($query){
                                        $query->where('is_sec_deal',1)
                                              ->where('is_sup_deal',0);
                                     });
                            });
           }
           if($type==4){
               $model=$model->where(function($query){
                         $query->where('is_warn',1)
                               ->whereBetween('warn_level',[1,2])
                               ->where('is_sec_deal',1)
                               ->where('is_sup_deal',1);
                     })
                     ->orWhere(function($query){
                         $query->where('is_warn',1)
                               ->where('warn_level',3)
                               ->where('is_sec_deal',1)
                               ->where('is_sup_deal',1)
                               ->where('is_pro_deal',1);
                     });
           }
       }else{
           $model=$model->where([]);
           $search['type']=0;
           $url=$url.'&type=0';
       }

       if(isset($start_date)){
          $start_time=strtotime($start_date);
          $url=$url.'&start_date='.$start_date;
       }else{
          $start_time=strtotime(date('Y-m-d',time()-86400));
          $url=$url.'&start_date='.date('Y-m-d',time()-86400);
       }

       if(isset($end_date)){
           $end_time=strtotime($end_date)+86400;
           $url=$url.'&end_date='.$end_date;
       }else{
           $end_time=strtotime(date('Y-m-d',time()+86400));
           $url=$url.'&end_date='.date('Y-m-d',time());
       }


      $search['start_time']=$start_time;
      $search['end_time']=$end_time-86400;
      $search['device_id']=$device_id;


      $list=$model->where('device_id',$device_id)
                  ->whereBetween('time',[$start_time,$end_time])
                  ->paginate($ispage)
                  ->toArray();

      $list['search']=$search;
      $list['url']=$url;

      return $list;

    }
}
