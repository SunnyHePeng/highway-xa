<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Device_user;
use Input;

class DeviceUserController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.device_user';
    protected $url = '';
    protected $user_type; //1试验2张拉压浆3拌合

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Device_user;
        $this->field = [
						'id',
                        'supervision_id',
                        'section_id',
				        'name',
				        'phone',
				        'position',
				        'email',
                        'created_at'
					];
        $this->url = url('manage/device_user');
    }

    //管理员获取项目所有用户 其他根据拥有标段显示
    public function index(){
    	$type = Input::get('type');
    	if(!$type){
    		return view('admin.error.no_info', ['info'=>'链接错误']);
    	}
    	$this->url .= '?type='.$type;
    	$where = $search = [];
        $supervision_list = $url_para = '';
        $this->order = isset($this->order) ? $this->order : 'id desc';

        $query = $this->model->select($this->field)
        					 ->where('type', $type)
                             ->where('project_id', $this->user->project_id);
        
        $sup_id = Input::get('sup_id') ? Input::get('sup_id') : '';
        if($sup_id){
            $search['sup_id'] = $sup_id;
            $url_para .= $url_para ? '&sup_id='.$sup_id : 'sup_id='.$sup_id;
        }

        //该项目监理信息
        if($this->user->type = 1){
        	$supervision_where[] = ['project_id', '=', $this->user->project_id];
        	if($sup_id){
        		$query = $query->where('supervision_id', $sup_id);
        	}
        }else{
        	$supervision_where[] = ['id', '=', $this->user->supervision_id];
        	$query = $query->where('supervision_id', $this->user->supervision_id);
        }
        $supervision_list = $this->model->getList((new Supervision), $supervision_where, ['id','name'], 'id desc');

        //通过标段筛选
        if($this->user_section['section_where']){
            if(is_array($this->user_section['section_where'])){
                $query = $query->where('section_id', 'in', $this->user_section['section_where']);
            }else{
                $query = $query->where('section_id', '=', $this->user_section['section_where']);
            }
        }

        //通过搜索条件筛选
        $name = Input::get('name') ? Input::get('name') : '';
        if($name){
            $search['name'] = $name;
            $url_para .= $url_para ? '&name='.$name : 'name='.$name;
            $query = $query->where('name', 'like', $name);
        }
        
        $tel = Input::get('tel') ? Input::get('tel') : '';
        if($tel){
            $search['tel'] = $tel;
            $url_para .= $url_para ? '&tel='.$tel : 'tel='.$tel;
            $query = $query->where('phone', 'like', $tel);
        }
        
        
        $list = $query->with('supervision', 'section')
                      ->orderByRaw($this->order)
                      ->paginate($this->ispage)
                      ->toArray();

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['supervision'] = $supervision_list;
        $list['type'] = $type;
        
        return view($this->list_view, $list);
    }

}