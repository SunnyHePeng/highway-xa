<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Position,
    App\Models\User\Role;
use Input,DB;

class PositionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.admin.position';
    protected $url = '';
    protected $act_info = '职位';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Position;
        $this->field = ['id','role_id','name'];
        $this->url = url('manage/position');
    }

    public function index(){
        $module_list = $url_para = '';
        $where = $search = [];
        //获取角色信息
        $role_list = (new Role)->where('id', '!=', 1)->OrderByRaw('id asc')->get()->toArray();
        if(!$role_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何角色，请先添加角色']);
        }

        $r_id = Input::get('r_id') ? Input::get('r_id') : '';
        if($r_id){
            $search['r_id'] = $r_id;
            $where[] = ['role_id', '=', $r_id];
            $url_para .= $url_para ? '&r_id='.$r_id : 'r_id='.$r_id;
        }

        $list = $this->model->select($this->field)
                            ->where(function($query) use ($where){
                                if($where){
                                    foreach ($where as $v) {
                                        $query->where($v[0], $v[1], $v[2]);
                                    }
                                } 
                            })
                            ->with('role')
                            ->orderByRaw($this->order)
                            ->paginate($this->ispage)
                            ->toArray();


        //getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'child', $this->field);
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $list['role'] = $role_list;
        $list['search'] = $search;
        return view($this->list_view, $list);
    }

    public function getPositionByRole(){
        if(!Input::get('role')){
            $result = ['status'=>0, 'info'=>'获取失败'];
            return Response()->json($result);
        }
        //获取职位
        $data = $this->model->select(['id','name','role_id'])
                    ->where('role_id', Input::get('role'))
                    ->get()
                    ->toArray();
        //获取公司
        $company=DB::table('company')->select(['id','name','role_id'])
                    ->where('role_id',Input::get('role'))
                    ->get();
        if(!$company){
            $result=['status'=>2,
                'info'=>'该角色没有相对应的公司，请联系管理员添加'];
            return Response()->json($result);
        }
        //获取部门
        $department=DB::table('department')->select(['id','name','role_id'])
                            ->where('role_id',Input::get('role'))
                            ->get();
        if(!$department){
            $result=['status'=>2,
                'info'=>'该角色没有相对应的部门,请联系管理员添加'];
            return Response()->json($result);
        }
        $result = ['status'=>1,
            'info'=>'获取成功',
            'data'=>$data,
            'company'=>$company,
            'department'=>$department,
            'send_path'=>'get_pos'];
        if(Input::get('role')==4){
            //需要获取监理信息

        }

        return Response()->json($result);
    }

}