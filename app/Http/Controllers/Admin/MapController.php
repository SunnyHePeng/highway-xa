<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Project,
	App\Models\Project\Map;
use Input;

class MapController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.map';
    protected $url = '';
    protected $act_info = '地图';
    protected $type = [
    			'1'=>'建设单位',
    			'2'=>'监理',
    			'3'=>'合同段',
    			'4'=>'拌和站',
    			'5'=>'桩号',
    			'6'=>'监控',
    			'7'=>'隧道',
    			'8'=>'桥梁',
    			'9'=>'站点/线路',
                '10'=>'试验室'
    			];

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Map;
        $this->field = ['id','project_id','name','type','jwd','sort'];
        $this->url = url('manage/map');
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

        if($this->user->role != 1 && $this->user->role != 2){
            $where[] = ['id', '=', $this->user->project[0]];
        }
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'project', ['id','name']);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['ztree_data'] = json_encode($project_list);
        $list['ztree_name'] = Project::where('id', $pro_id)->pluck('name');
        $list['project'] = $project_list;
        $list['type'] = $this->type;
        return view($this->list_view, $list);
    }
}