<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Project,
	App\Models\Project\Section,
	App\Models\Project\Project_section;
use Input;

class ProjectSectionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.project_section';
    protected $url = '';
    protected $act_info = '项目标段';

    public function __construct()
    {
        parent::__construct();
        
        if($this->user->role == 1 || $this->user->role == 2 || $this->user->role == 3){
            $this->user_is_act = true;
        }else{
            $this->user_is_act = false;
        }
        $this->request = new Request;
        $this->model = new Project_section;
        $this->field = ['id','project_id','name'];
        $this->url = url('manage/psection');
    }

    public function index(){
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

        $keyword = str_replace('+', '', trim(Input::get('keyword')));
        if($keyword){
            $search['keyword'] = $keyword;
            $where[] = ['name', 'like', '%'.$keyword.'%'];
            $url_para = 'keyword='.$keyword;
        }

        $pro_id = Input::get('pro_id') ? Input::get('pro_id') : $project_list[0]['id'];
        if($pro_id){
            $search['pro_id'] = $pro_id;
            $where[] = ['project_id', '=', $pro_id];
            $url_para .= $url_para ? '&pro_id='.$pro_id : 'pro_id='.$pro_id;
        }

        /*if($this->user->role != 1 && $this->user->role != 2){
            $where[] = ['id', '=', $this->user->project[0]];
        }else{
            $where[] = ['id', 'in', $this->user->project];
        }
        var_dump($where);*/
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'project', ['id','name']);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['ztree_data'] = json_encode($project_list);
        $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
        $list['project'] = $project_list;
        return view($this->list_view, $list);
    }

    public function update($id)
    {
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        $data = $this->model->checkData();
        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        $device = $this->model->find($id);
        
        try {
            $device->fill($data);
            $device->save();
            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
        
            $this->log($this->user->username.'修改', isset($this->act_name) ? $device[$this->act_name] : $device['name'], 'm');
        
        	//修改标段中名称
            Section::where('psection_id', $id)->update(['name'=>$data['name']]);
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
        //只有集团用户可以删除
        if(!$this->user->has_sh){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        if(!$this->user_is_act){
            $result = ['status'=>0,'info'=>'您没有权限'];
            return Response()->json($result);
        }
        if(Section::where('psection_id', $id)->first()){
        	$result = ['status'=>0,'info'=>'该标段已有信息，不能删除'];
            return Response()->json($result);
        }
        $data = $this->model->destroy($id);
        
        if($data){
            $result = ['status'=>1,'info'=>'删除成功', 'data'=>$id, 'is_reload'=>$this->is_reload];
        
            $this->log($this->user->username.'删除', isset($this->act_name) ? $data[$this->act_name] : $data['name'], 'd');
        }else{
            $result = ['status'=>0,'info'=>'删除失败'];
        }
        return Response()->json($result);
    }

    public function getSecByPro(){
    	$pro_id = Input::get('pro_id');
        $result = ['status'=>0, 'info'=>'获取失败'];
        if($pro_id){
            $data = $this->model->where('project_id', $pro_id)
                         ->get()
                         ->toArray();
            $result = ['status'=>1, 'info'=>'获取成功', 'data'=>$data];
        }
        return Response()->json($result);
    }
}