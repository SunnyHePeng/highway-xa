<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Pda,
	App\Models\Project\Project;
use Input;

class PdaController extends AdminController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.admin.pda';
    protected $url = '';
    protected $act_info = 'PDA用户';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Pda;
        $this->field = [
			        'id',
                    'company',
			        'name',
			        'username',
			        'phone',
			        'phone_model',
			        'phone_system',
			        'phone_px',
			        'status'
		        ];
        $this->url = url('manage/pda');
    }

    public function index(){
    	$where = $search = [];
        $project_list = $url_para = '';
        
        //获取项目信息
        $project_list = $this->getProjectList();
        if(!$project_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
        }
        
        $pro_id = Input::get('pro_id') ? Input::get('pro_id') : $project_list[0]['id'];
        if($pro_id){
            $search['pro_id'] = $pro_id;
            $where[] = ['project_id', '=', $pro_id];
            $url_para .= $url_para ? '&pro_id='.$pro_id : 'pro_id='.$pro_id;
        }

        $this->order = isset($this->order) ? $this->order : 'id desc';
        $query = $this->model->select($this->field)
                             ->where('project_id', $pro_id);

        //根据搜索条件筛选
        $name = Input::get('name') ? Input::get('name') : '';
        if($name){
            $search['name'] = $name;
            $query = $query->where('name', 'like', '%'.$name.'%');
            $url_para .= $url_para ? '&name='.$name : 'name='.$name;
        }

        $tel = Input::get('tel') ? Input::get('tel') : '';
        if($tel){
            $search['tel'] = $tel;
            $query = $query->where('phone', 'like', '%'.$tel.'%');
            $url_para .= $url_para ? '&tel='.$tel : 'tel='.$tel;
        }                    
        $list = $query->orderByRaw($this->order)
                    ->paginate($this->ispage)
                    ->toArray();

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['project'] = $project_list;
        $list['status'] = $this->status;

        return view($this->list_view, $list);
    }
}