<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Permission,
    App\Models\User\Module;
use Input;

class PermissionController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'sort asc, id asc';
    protected $list_view = 'admin.admin.permission';
    protected $url = '';
    protected $act_info = '权限';
    protected $parent = '';
    protected $status = [
                    0=>'不显示',
                    1=>'显示'
                ];
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Permission;
        $this->field = ['id','mid','pid','status','name','url','description','sort','updated_at'];
        $this->url = url('manage/permission');
    }

    public function index($is_trashed=0){
        $where = [['pid','=','0']];
        $module_list = $url_para = '';

        //获取项目信息
        $module_list = (new Module)->OrderByRaw('id asc')->get()->toArray();
        if(!$module_list){
            return view('admin.error.no_info', ['info'=>'暂时没有任何模块，请先添加模块']);
        }

        $m_id = Input::get('m_id') ? Input::get('m_id') : $module_list[0]['id'];
        if($m_id){
            $search['m_id'] = $m_id;
            $where[] = ['mid', '=', $m_id];
            $url_para .= $url_para ? '&m_id='.$m_id : 'm_id='.$m_id;
        }

        $list = $this->model->select($this->field)
                            ->where(function($query) use ($where){
                                foreach ($where as $v) {
                                    $query->where($v[0], $v[1], $v[2]);
                                }  
                            })
                            ->with(['child'=>function($query){
                                $query->select($this->field);
                            }, 'mod'])
                            ->orderByRaw($this->order)
                            ->paginate($this->ispage)
                            ->toArray();


        //getList($this->model, $where, $this->field, $this->order, $this->ispage, '', 'child', $this->field);
        $list['url'] = $url_para ? $this->url.'?'.$url_para : $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;

        $list['parent'] = $this->model->getList($this->model, $where, ['id','pid','name'], 'sort asc', '', '', 'child', ['id','pid','name']);
        $list['module'] = $module_list;
        $list['search'] = $search;
        $list['status'] = $this->status;
        //var_dump($list['data']);
        return view($this->list_view, $list);
    }
}