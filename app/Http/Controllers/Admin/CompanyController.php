<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Company;
use App\Models\User\Role;
use Input;
use Qiniu\Http\Response;

class CompanyController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.admin.company';
    protected $url = '';
    protected $act_info = '单位';

    public function __construct()
    {
        parent::__construct();

        $this->request = new Request;
        $this->model = new Company;
        $this->field = ['id', 'name', 'sort'];
        $this->url = url('manage/company');
    }

    public function index()
    {
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
//          dd($list);

        //getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'child', $this->field);
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $list['role_list'] = $role_list;
        $list['search'] = $search;
//        dd($list);
        return view($this->list_view, $list);
    }

//    public function update($id)
//    {
//        if(!$this->user->has_sh){
//            $result = ['status'=>0,'info'=>'您没有权限'];
//            return Response()->json($result);
//        }
//
//            $data = $this->model->checkData();
//
//            if($data['code'] == 1){
//                $result = ['status'=>0,'info'=>$data['info']];
//                return Response()->json($result);
//            }
////           return $data;
//        $device = $this->model->find($id);
////            return $device;
//        try {
////            $device->fill($data);
//            $res=$device->update($data);
////            return Response()->json($res);
//            if($res){
//                $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
//            }else{
//                $result = ['status'=>0,'info'=>'修改失败'];
//            }
////            $result = ['status'=>1,'info'=>'修改成功','url'=>$_SERVER['HTTP_REFERER']];
//
//            $this->log($this->user->username.'修改', isset($this->act_name) ? $device[$this->act_name] : $device['name'], 'm');
//        } catch (\Exception $e) {
//            $result = ['status'=>0,'info'=>'修改失败,请检查是否重复'];
//        }
//        return Response()->json($result);
//    }
}