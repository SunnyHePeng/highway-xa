<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Section,
    App\Models\Project\Project;
use Input;

class SectionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.section';
    protected $url = '';
    protected $act_info = '标段';
    protected $is_reload = 1;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Section;
        $this->field = ['id','project_id','name','begin_position','end_position','cbs_name','bhz_num','lc_num','sd_num','fzr','phone','created_at'];
        $this->url = url('manage/section');
    }

    public function index(){
        return $this->sectionAndSupervisionIndex();
    }

    public function show($id)
    {
        $data = $this->model->find($id);
        if($data){
            $info = Section::where('id', $id)
                            ->with(['project'=>function($query){
                                $query->select(['id','name']);
                            },'sup'=>function($query){
                                $query->select(['id','name']);
                            }])
                            ->first()
                            ->toArray();
            if(!isset($info['sup'][0]['name'])){
                $info['sup'][0]['name'] = '';
            }
            $info['created_at'] = date('Y-m-d H:i', $info['created_at']);
            $result = ['status'=>1,'info'=>'获取成功', 'data'=>$info, 'id'=>$id];
        }else{
            $result = ['status'=>0,'info'=>'获取失败'];
        }
        return Response()->json($result);
    }

}