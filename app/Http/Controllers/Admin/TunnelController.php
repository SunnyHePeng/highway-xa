<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Project\Tunnel;
use Input;

class TunnelController extends MixplantController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.project.tunnel';
    protected $url = '';
    protected $cat_id = 3;
    protected $column = 'sd_num';
    protected $act_info = '隧道';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Tunnel;
        $this->field = ['id','name','left_begin_position','left_end_position','right_begin_position','right_end_position','length','station_num','status','created_at'];
        $this->url = url('manage/tunnel');
    }

    public function index(){
    	return $this->mixplantIndex();
    }
}