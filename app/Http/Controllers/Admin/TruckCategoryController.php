<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Truck_category;
use Input;

class TruckCategoryController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.truck_category';
    protected $url = '';

    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Truck_category;
        $this->field = [
						'id',
						'name',
					];
        $this->url = url('manage/truck_category');
    }
}