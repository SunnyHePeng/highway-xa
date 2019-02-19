<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\User\Module;
use Input;

class ModuleController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.admin.module';
    protected $url = '';
    protected $act_info = '模块';
    protected $status = [
        0=>'不显示',
        1=>'显示'
    ];

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Module;
        $this->field = ['id','name','sort','shown'];
        $this->url = url('manage/module');
    }

    public function index()
    {
        $list = $this->model->getList($this->model, [], $this->field, $this->order, $this->ispage);

        $list['url'] = $this->url;
        $list['page_num'] = Input::get('page') ? (Input::get('page')-1)*20+1 : 1;
        $list['status'] = $this->status;

        return view($this->list_view, $list);
    }
}