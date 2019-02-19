<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Project;
use Input;

class ProjectController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.project.project';
    protected $url = '';
    protected $act_info = '项目';

    public function __construct()
    {
        parent::__construct();
        
        if($this->user->role == 1 || $this->user->role == 2){
            $this->user_is_act = true;
        }else{
            $this->user_is_act = false;
        }
        $this->request = new Request;
        $this->model = new Project;
        $this->field = ['id','name','section_num','supervision_num','mileage','summary','created_at'];
        $this->url = url('manage/project');
    }

    public function index(){
        $where = $search = [];
        $project_list = $url_para = '';
        
        $keyword = str_replace('+', '', trim(Input::get('keyword')));
        if($keyword){
            $search['keyword'] = $keyword;
            $where[] = ['name', 'like', '%'.$keyword.'%'];
            $url_para = 'keyword='.$keyword;
        }

        if($this->user->role != 1 && $this->user->role != 2){
            $where[] = ['id', '=', $this->user->project[0]];
        }else{
            $where[] = ['id', 'in', $this->user->project];
        }
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        return view($this->list_view, $list);
    }
}