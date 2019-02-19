<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Device_category;
use Input;

class DeviceCategoryController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.device_category';
    protected $url = '';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Device_category;
        $this->field = [
						'id',
						'name',
					];
        $this->url = url('manage/dev_category');
    }
}