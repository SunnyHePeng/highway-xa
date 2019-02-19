<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Supervision,
    App\Models\Project\Project,
    App\Models\Project\Section;
use Input;

class SupervisionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.supervision';
    protected $url = '';
    protected $type = [
    			'1'=>'总监',
    			'2'=>'驻地办'
    			];
    protected $act_info = '监理';
    protected $is_reload = 1;

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Supervision;
        $this->field = ['id','project_id','name','type','company','position','fzr','phone','created_at'];
        $this->url = url('manage/supervision');
    }

    public function index(){
        return $this->sectionAndSupervisionIndex();
        
    }

    public function show($id)
    {
        $data = $this->model->find($id);
        if($data){
            $info = Supervision::where('id', $id)
                                ->with(['project'=>function($query){
                                    $query->select(['id','name']);
                                }])
                                ->first()
                                ->toArray();
            $info['type'] = $this->type[$info['type']];
            $info['created_at'] = date('Y-m-d H:i', $info['created_at']);
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$info];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

    public function supSection(Request $request){
    	if($request->isMethod('get')){
            $project_id = Input::get('pro_id');
	        $list = (new Section)->select('id','name')->where('project_id',$project_id)->get()->toArray();
	        if($list && Input::get('sup_id')){
	        	$data['list'] = $list;
	        	$data['sup_id'] = Input::get('sup_id');
	        	$sup = $this->model->find(Input::get('sup_id'));
            	$data['sec'] = $sup->sec->toArray();
            	if($data['sec']){
                    //var_dump($data);
                    foreach ($data['sec'] as $key => $value) {
                        $data['sec'][$key] = $value['id'];
                    }
                }
	            return view('admin.project.set_section', $data);
	        }
	        return view('admin.error.no_info', ['info'=>'暂时没有标段信息']);
        }

        $result = ['status'=>0,'info'=>'设置失败'];
        if(Input::get('sup_id')){
        	$sup = $this->model->find(Input::get('sup_id'));
        	try {
                $set_id = Input::get('set_id') ? Input::get('set_id') : [];
	            $sup->sec()->sync($set_id);
	            $result = ['status'=>1,'info'=>'设置成功'];

                $this->addLog('修改监理：'.$sup->name.'管理的标段', 'm');
	        } catch (\Exception $e) {
	            $result = ['status'=>0,'info'=>'设置失败，请检查是否一个标段设置了多个监理'];
	        }
        }

        return Response()->json($result);
    }

    public function getSecBySup(){
        $pro_id = Input::get('pro_id');
        $sup_id = Input::get('sup_id');

        $result = ['status'=>0, 'info'=>'获取失败'];
        if($pro_id && $sup_id){
            $data = $this->model->where('id', $sup_id)
                         ->with(['sec'=>function($query) use ($pro_id){
                            $query->select(['id','name'])
                                  ->where('project_id', $pro_id);
                         }])
                         ->get()
                         ->toArray();
            $result = ['status'=>1, 'info'=>'获取成功', 'data'=>$data[0]['sec']];
        }
        return Response()->json($result);
    }

    public function getSupByProject(){
        $pro_id = Input::get('pro_id');
        $result = ['status'=>0, 'info'=>'获取失败'];
        if($pro_id){
            $data = $this->model->select(['id','name'])
                                ->where('project_id', $pro_id)
                                ->get()
                                ->toArray();
            $result = ['status'=>1, 'info'=>'获取成功', 'data'=>$data];
        }
        return Response()->json($result);
    }
}