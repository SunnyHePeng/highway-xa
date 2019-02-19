<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Role,
    App\Models\User\Module,
    App\Models\User\Permission;
use Input;

class RoleController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.admin.role';
    protected $url = '';
    protected $act_info = '角色';
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Role;
        $this->field = ['id','name','display_name','description','created_at','updated_at'];
        $this->url = url('manage/role');
    }

    public function index($is_trashed=0){
        $where = [];
        $url_para = '';

        $list = $this->model->getList($this->model, $where, $this->field, $this->order, $this->ispage);
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $permission = new Permission;
        /*$list = Module::with()
                      ->get()
                      ->toArray();
        $list['permission'] = $permission->getList($permission, [['pid','=',0]], ['id','pid','name'], 'sort asc', '', '', 'child', ['id','pid','name']);
        */
        $list['permission'] = $permission->select(['id','mid','pid','name'])
                                        ->where('pid','=',0)
                                        ->with(['child'=>function($query){
                                            $query->select(['id','pid','name']);
                                        }, 'mod'])
                                        ->orderByRaw('mid asc,pid asc,sort asc')
                                        ->get()
                                        ->toArray();
        return view($this->list_view, $list);
    }

    public function store(){
        $data = $this->model->checkData();

        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }
        try {
            $res = $this->model->create($data);
            
            //添加角色对应权限
            if(Input::get('permission')){
                $res->perms()->attach(Input::get('permission'));
            }
            $result = ['status'=>1,'info'=>'添加成功'];
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'添加失败,请检查是否重复'];
        }

        return Response()->json($result);
    }

    public function edit($id)
    {
        $result = ['status'=>0,'info'=>'获取失败'];
        if($id){
            $data = $this->model->find($id);
            $data['permission'] = $data->perms;
            if($data){
                $result = ['status'=>1,'info'=>'获取成功', 'data'=>$data];
            }
        }
        return Response()->json($result);
    } 

    public function update($id)
    {
        $data = $this->model->checkData();
        if($data['code'] == 1){
            $result = ['status'=>0,'info'=>$data['info']];
            return Response()->json($result);
        }

        $role = $this->model->find($id);
        
        $role->fill($data);
        try {
            $role->save();
            $role->perms()->sync(Input::get('permission') ? Input::get('permission') : []);
            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
        } catch (\Exception $e) {
            $result = ['status'=>0,'info'=>'修改失败'];
        }
        return Response()->json($result);
    }
}