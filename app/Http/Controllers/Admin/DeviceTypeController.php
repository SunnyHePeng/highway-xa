<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Device_category,
	App\Models\Device\Device_type,
    App\Models\Device\Collection;
use Input, DB;

class DeviceTypeController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.device.device_type';
    protected $url = '';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Device_type;
        $this->field = [
						'id',
						'name',
					];
        $this->url = url('manage/dev_type');
    }

    public function index(){
        $where = $search = [];
        $category_list = $url_para = '';
        
        $category_model = new Device_category;
        $category_list = $this->model->getList($category_model, [], ['id','name'], 'id asc');
        if(!$category_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何设备分类，请先添加设备分类']);
        }

        $cat_id = Input::get('cat_id') ? Input::get('cat_id') : $category_list[0]['id'];
        if($cat_id){
            $search['cat_id'] = $cat_id;
            $where[] = ['category_id', '=', $cat_id];
            $url_para .= $url_para ? '&cat_id='.$cat_id : 'cat_id='.$cat_id;
        }
       
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['category'] = $category_list;
        
        return view($this->list_view, $list);
    }

    public function getDeviceTypeByCat(){
        $result = ['status'=>0,'info'=>'获取信息失败'];
        $cat_id = Input::get('cat_id');
        $sec_id = Input::get('sec_id');
        $pro_id = Input::get('pro_id');
        if($cat_id && $sec_id && $pro_id){
            $data['type'] = $this->model->getList($this->model, [['category_id', '=', $cat_id]], ['id','name'], 'id asc');
            
            /*$type = [
                '1'=>'mixplant',
                '2'=>'beamfield',
                '3'=>'beamfield',
                '4'=>'tunnel',
                '5'=>''
                ];
            if($type[$cat_id]){
                $data['col'] = DB::table($type[$cat_id])
                                 ->select(['id','name'])
                                 ->where('project_id', $pro_id)
                                 ->where('section_id', $sec_id)
                                 ->get();
            }*/

            $data['col'] = (new Collection)->getCollectionByCat($pro_id, $sec_id, $cat_id);
            //var_dump($data);
            //$data['col'] = $data['col'][0]['collection'];
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
        }
        return Response()->json($result);
    }
}