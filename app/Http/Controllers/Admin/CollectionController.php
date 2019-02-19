<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Collection,
    App\Models\Device\Device_category,
	App\Models\Project\Project,
	App\Models\Project\Section;
use Input;

class CollectionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id desc';
    protected $list_view = 'admin.device.collection';
    protected $url = '';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Collection;
        $this->field = [
						'id',
						'section_id',
						'name',
					];
        $this->url = url('manage/collection');
    }

    public function index(){
        $where = $search = [];
        $project_list = $section_list = $url_para = '';
        
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

        //该项目标段信息
        $section_list = $this->model->getList((new Section), [['project_id', '=', $pro_id]], ['id','name'], 'id asc');
        
        $this->order = isset($this->order) ? $this->order : 'id desc';
        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'section', ['id','name']);

        $list['search'] = $search;
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['project'] = $project_list;
        $list['section'] = $section_list;
        
        //所有设备分类
        $list['category'] = (new Device_category)->select('id','name')->get()->toArray();
        return view($this->list_view, $list);
    }

    public function getCollectionBySec(){
        $list = $this->model->getList($this->model, [['section_id', '=', Input::get('id')]], ['id','name'], 'id asc');
        if($list){
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$list];
        }else{
            $result = ['status'=>0,'info'=>'获取信息失败'];
        }
        return Response()->json($result);
    }

    public function collectionCat(Request $request){
        if($request->isMethod('get')){
            $list = (new Device_category)->select('id','name')->get()->toArray();
            if($list && Input::get('col_id')){
                $data['list'] = $list;
                $data['col_id'] = Input::get('col_id');
                $col = $this->model->find(Input::get('col_id'));
                $data['cat'] = $col->category->toArray();
                if($data['cat']){
                    //var_dump($data);
                    foreach ($data['cat'] as $key => $value) {
                        $data['cat'][$key] = $value['id'];
                    }
                }
                return view('admin.device.collection_cat', $data);
            }
            return view('admin.error.no_info', ['info'=>'暂时没有标段信息']);
        }

        $result = ['status'=>0,'info'=>'设置失败'];
        if(Input::get('col_id')){
            $col = $this->model->find(Input::get('col_id'));
            try {
                $cat_id = Input::get('cat_id') ? Input::get('cat_id') : [];
                $col->category()->sync($cat_id);
                $result = ['status'=>1,'info'=>'设置成功'];
            } catch (\Exception $e) {
                $result = ['status'=>0,'info'=>'设置失败'];
            }
        }

        return Response()->json($result);
    }

    public function getCollectionByCat()
    {
        $cat_id = Input::get('cat_id');
        $sec_id = Input::get('sec_id');
        $pro_id = Input::get('pro_id');
        $result = ['status'=>0,'info'=>'获取失败'];
        if($pro_id && $sec_id && $cat_id){
            $data = $this->model->getCollectionByCat($pro_id, $sec_id, $cat_id);
            $result = ['status'=>1,'info'=>'获取成功','data'=>$data];
        }
        return Response()->json($result);
    }
}