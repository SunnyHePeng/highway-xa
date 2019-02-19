<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Factory\Material;
use Input;

class MaterialController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.factory.material';
    protected $url = '';
    protected $act_info = '材料';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Material;
        $this->field = [
						'id',
    					'name',
						'type',
						'dasign_rate',
						'warn_rate',
						'note',
					];
        $this->url = url('manage/material');
    }
}