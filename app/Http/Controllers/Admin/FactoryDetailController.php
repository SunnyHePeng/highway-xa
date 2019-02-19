<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Factory\Factory,
    App\Models\Factory\Material,
    App\Models\Factory\Factory_detail;
use Input;

class FactoryDetailController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.factory.factory_detail';
    protected $url = '';
    protected $act_info = '厂家明细';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Factory_detail;
        $this->field = [
						'id',
						'material_id',
						'order_num',
						'cl_position_row',
						'cl_position_col',
						'fact_z_cjjs',
						'fact_z_position_row',
						'fact_z_position_col',
						'design_z_cjjs',
						'design_z_position_row',
						'design_z_position_col',
					];
        $this->url = url('manage/factory_detail');
    }

    public function index(){
        $where = $search = [];
        $factory_list = $material_list = $url_para = '';
        
        $factory_model = new Factory;
        $factory_list = $this->model->getList($factory_model, [], ['id','name'], 'id desc');
        $material_list = $this->model->getList(new Material, [], ['id','name'], 'id asc');
        if(!$factory_list || !$material_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何厂家/材料，请先添加厂家/材料']);
        }

        $fac_id = Input::get('fac_id') ? Input::get('fac_id') : $factory_list[0]['id'];
        if($fac_id){
            $search['fac_id'] = $fac_id;
            $where[] = ['factory_id', '=', $fac_id];
            $url_para .= $url_para ? '&fac_id='.$fac_id : 'fac_id='.$fac_id;
        }
       
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage,'','material',['id','name']);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['factory'] = $factory_list;
        $list['material'] = $material_list;
        
        return view($this->list_view, $list);
    }

    public function show($id)
    {
        $data = $this->model->with(['factory'=>function($query){
                                $query->select('id','name');
                            }, 'material'=>function($query){
                                $query->select('id','name');
                            }])->find($id);
        if($data){
            $data['factory_id'] = $data['factory']['name'];
            $data['material_id'] = $data['material']['name'];
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }
}