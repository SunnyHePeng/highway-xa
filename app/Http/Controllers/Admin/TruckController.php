<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Truck,
	App\Models\Device\Truck_category,
	App\Models\Project\Project,
	App\Models\Project\Section;
use Input;

class TruckController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.truck';
    protected $url = '';
    protected $select = [
    				'1'=>'温度',
    				'2'=>'震动'
    			];
    protected $act_info = '车辆';
    protected $act_name = 'truck_num';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Truck;
        $this->field = [
						'id',
						'section_id',
						'group_code',
						'truck_num',
						'truck_type',
						'unit_name',
						'driver_name',
						'phone',
						'by1_select',
						'by2_select',
						'by3_select',
						'by4_select',
						'by5_select',
						'factory_code',
    					'created_at'
					];
        $this->url = url('manage/truck');
    }

    public function index(){
        $where = $search = [];
        $project_list = $section_list = $category_list = $url_para = '';
        
        //获取项目信息
        $project_list = $this->getProjectList();
        if(!$project_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何项目，请先添加项目']);
        }

        $pro_id = Input::get('pro_id') ? Input::get('pro_id') : $project_list[0]['id'];
        if($pro_id){
            $search['pro_id'] = $pro_id;
            $url_para .= $url_para ? '&pro_id='.$pro_id : 'pro_id='.$pro_id;
        }

        //分类
        $category_list = $this->model->getList((new Truck_category), [], ['id','name'], 'id asc');
        if(!$category_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何车辆分类，请先添加车辆分类']);
        }
       
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $query = $this->model->select($this->field)
        					 ->where('project_id', $pro_id);
        
        //根据搜索筛选数据
        $sec_id = Input::get('sec_id') ? Input::get('sec_id') : '';
        if($sec_id){
            $search['sec_id'] = $sec_id;
            $url_para .= $url_para ? '&sec_id='.$sec_id : 'sec_id='.$sec_id;
            $query = $query->where('section_id', $sec_id);
        }else{
            if($this->user_section['section_where']){
                if(is_array($this->user_section['section_where'])){
                    $query = $query->where('section_id', 'in', $this->user_section['section_where']);
                }else{
                    $query = $query->where('section_id', '=', $this->user_section['section_where']);
                }
            }
        }

        $num = Input::get('num') ? Input::get('num') : '';
        if($num){
            $search['num'] = $num;
            $url_para .= $url_para ? '&num='.$num : 'num='.$num;
            $query = $query->where('truck_num', 'like', '%'.$num.'%');
        }
        
        $list = $query->with('section', 'category')
					->orderByRaw($this->order)
					->paginate($this->ispage)
					->toArray();
        
        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['project'] = $project_list;
        $list['section'] = $this->user_section['section_list'];
        $list['category'] = $category_list;
        $list['select'] = $this->select;

        return view($this->list_view, $list);
    }
}