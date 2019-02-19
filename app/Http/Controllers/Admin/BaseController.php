<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Project\Project,
    App\Models\Project\Project_section,
    App\Models\Project\Section,
    App\Models\User\Log;
use Redirect, Input, Validator, Auth, Cache;

class BaseController extends Controller
{
    protected $ispage=20;
    protected $user_is_act;
    protected $is_reload = 0;
    public function __construct()
    {
        parent::__construct();

        //判断该用户是否可以增删改操作
        $this->user_is_act = $data['user_is_act'] = true;
        /*if($this->user->role == 1 || $this->user->role == 2){
            $this->user_is_act = $data['user_is_act'] = true;
        }else{
            $this->user_is_act = $data['user_is_act'] = false;
        }*/
        view()->share($data);
    }
    /**
    *管理员可以查看所属项目的信息
    *驻地办 总监办 合同段可以查看所管理的标段的信息 只查看 不可修改和删除
    *拌合站/梁场/隧道  不可访问本模块
    **/
    public function index(){
        $where = $search = [];
        $project_list = $factory_list = $material_list = $url_para = '';
        
        //根据权限判断可以查看的数据
        $where = $this->getUserWhere();

        $keyword = str_replace('+', '', trim(Input::get('keyword')));
        if($keyword){
            $search['keyword'] = $keyword;
            $where[] = ['name', 'like', '%'.$keyword.'%'];
            $url_para = 'keyword='.$keyword;
        }

        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        return view($this->list_view, $list);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->edit_view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store()
    {
        if(!$this->user_is_act){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        $data = $this->model->checkData();

        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        $data['created_at'] = time();
        //$this->model->create($data);
        try {
            $this->model->create($data);
            $result = ['status'=>1,'info'=>'添加成功'];
            
            $this->log($this->user->username.'添加新', isset($this->act_name) ? $data[$this->act_name] : $data['name'], 'a');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'添加失败,请检查是否重复'];
        }

        return Response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $data = $this->model->find($id);
        if($data){
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->model->find($id);
        if($data){
        	$result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        
        $change_type = Input::get('change_type');

        if($change_type == 'sort'){
            $data['sort'] = Input::get('sort');
        }elseif($change_type == 'status'){
            $data['status'] = Input::get('status');
            $data['updated_at'] = time();
        }else{
            $data = $this->model->checkData();
            if($data['code'] == 1){
                $result = ['status'=>0,'info'=>$data['info']];
                return Response()->json($result);
            }
        }
        
        $device = $this->model->find($id);
        
        try {
            $device->fill($data);
            $device->save();
            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
        
            $this->log($this->user->username.'修改', isset($this->act_name) ? $device[$this->act_name] : $device['name'], 'm');
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'修改失败,请检查是否重复'];
        }
        return Response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //只有项目和集团用户可以删除
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        if($this->act_info == '项目' && $this->user->role !=2 && $this->user->role !=1){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        if($this->user->role ==4 || $this->user->role ==5){
            $result = ['status'=>0,'info'=>'您没有权限,请联系项目用户或集团用户'];
            return Response()->json($result);
        }
        $data = $this->model->find($id);
        
        if($this->model->destroy($id)){
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id, 'is_reload'=>$this->is_reload];
        
            $this->log($this->user->username.'删除', isset($this->act_name) ? $data[$this->act_name] : $data['name'], 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }

    public function restore($id){
        $device = $this->model->onlyTrashed()->find($id);
        if($device->restore()){
            $result = ['status'=>1,'info'=>'操作成功', 'data'=>$id];
        }else{
            $result = ['status'=>0,'info'=>'操作失败'];
        }
        return Response()->json($result);
    }

    public function delete($id){
        $device = $this->model->onlyTrashed()->find($id);
        if($device){
            $device->forceDelete();
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id];
            
            $this->log('删除', isset($this->act_name) ? $device[$this->act_name] : $device['name'], 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }

    public function getFilterInfoForCode(){
        

        return ['where'=>$where, 'search'=>$search, 'url_para'=>$url_para];
    }

    public function mixplantIndex(){
        $where = $search = [];
        $url_para = '';

        $sec_id = trim(Input::get('sec_id'));
        if($sec_id){
            $sec_info = (new Section)->select(['name','project_id'])->where('id', $sec_id)->first()->toArray();
            $sec_info['sec_id'] = $sec_id;
            //$sec_info['pro_name'] = (new Project)->where('id', $sec_info['project_id'])->pluck('name');
            $where[] = ['section_id', '=', $sec_id];
            $url_para = 'sec_id='.$sec_id;
        }
        if(!isset($sec_info['project_id'])){
            return view('admin.error.no_info', ['info'=>'标段信息错误']);
        }
        $this->order = isset($this->order) ? $this->order : 'id asc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);

        $list['search'] = $sec_info;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        return view($this->list_view, $list);
    }

    public function sectionAndSupervisionIndex(){
        $res = $this->judgePermission();
        if($res !== true){
            return $res;
        }
        $where = $search = [];
        $project_list = $url_para = '';

        //需获取项目信息
        $project_list = $this->getProjectList();
        if(!$project_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
        }

        //根据权限判断可以查看的数据
        $where = $this->getUserWhere();
        
        $pro_id = Input::get('pro_id') ? Input::get('pro_id') : $project_list[0]['id'];
        if($pro_id){
            $search['pro_id'] = $pro_id;
            $where[] = ['project_id', '=', $pro_id];
            $url_para .= $url_para ? '&pro_id='.$pro_id : 'pro_id='.$pro_id;
        }

        $name = Input::get('name') ? Input::get('name') : '';
        if($name){
            $search['name'] = $name;
            $where[] = ['name', 'like', '%'.$name.'%'];
            $url_para .= $url_para ? '&name='.$name : 'name='.$name;
        }

        $lxr = Input::get('lxr') ? Input::get('lxr') : '';
        if($lxr){
            $search['lxr'] = $lxr;
            $where[] = ['fzr', 'like', '%'.$lxr.'%'];
            $url_para .= $url_para ? '&lxr='.$lxr : 'lxr='.$lxr;
        }
        //var_dump($where);    
        $this->order = isset($this->order) ? $this->order : 'id desc';
        if($this->act_info == '标段'){
            $list = $this->model->select($this->field)
                                ->where(function($query) use($where){        
                                    foreach ($where as $v) {
                                        if($v[1] == 'in'){
                                            $query->whereIn($v[0], $v[2]);
                                        }else{
                                            $query->where($v[0], $v[1], $v[2]);
                                        }
                                    }             
                                })
                                ->with(['project'=>function($query){
                                    $query->select(['id','name']);
                                },'sup'=>function($query){
                                    $query->select(['id','name']);
                                }])
                                ->orderByRaw($this->order)
                                ->paginate($this->ispage)
                                ->toArray();
            $list['section'] = Project_section::where('project_id', $pro_id)->orderByRaw('id asc')->get()->toArray();
        }else{
            $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);
        }
        //var_dump($list);
        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['project'] = $project_list;
        $list['ztree_data'] = json_encode($project_list);
        $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
        if(isset($this->type)){
            $list['type'] = $this->type;
        }
        return view($this->list_view, $list);
    }
        
    public function log($pre_info, $data, $type){
        if(isset($this->act_info) && $this->act_info){
            $this->addLog($pre_info.$this->act_info.'：'.$data, $type);
        }
    }

    //记录操作日志
    //l登录r注册a新增m修改d删除c审核
    public function addLog($info, $type){
        $model = new Log;
        $ip = $model->get_client_ip();
        $area = $model->ip_to_area($ip);
        $region = isset($area['region']) ? $area['region'] : ''; 
        $city = isset($area['city']) ? $area['city'] : '';

        $name = $this->user->name;
        $data = [
            'ip'=>$ip,
            'addr'=>$region.' '.$city,
            'act'=>$info,
            'user_id'=>$this->user->id,
            'name'=>$name,
            'type'=>$type,
            'created_at'=>time()
        ];
        $model->create($data);
    }

    protected function getUserWhere(){
        $where = [];
        
        if($this->list_view == 'admin.project.section' && $this->user_section['section_where']){
            if(is_array($this->user_section['section_where'])){
                $where[] = ['id', 'in', $this->user_section['section_where']];
            }else{
                $where[] = ['id', '=', $this->user_section['section_where']];
            }
        }
        if($this->list_view == 'admin.project.supervision'){
            //$where[] = ['project_id', '=', $this->user->project_id];
            if($this->user->supervision_id){
                $where[] = ['id', '=', $this->user->supervision_id];
            }
        }
        return $where;
    }

    protected function getProjectList(){
        $project_model = new Project;
        $project_where = [];
        if($this->user->role !=1 && $this->user->role !=2){
            $project_where = [['id', '=', $this->user->project[0]]];
        }else{
            $project_where = [['id', 'in', $this->user->project]];
        }
        $project_list = $this->model->getList($project_model, $project_where, ['id','name'], 'id desc');
        return $project_list;
    }
}
