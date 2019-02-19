<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Device\Detection_device;
use Input;

class DetectionDeviceController extends BaseController
{
    protected $request;
    protected $model;
    protected $field;
    protected $order = 'id asc';
    protected $list_view = 'admin.device.detection_device';
    protected $url = '';
    protected $act_info = '检测设备';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->request = new Request;
        $this->model = new Detection_device;
        $this->field = [
						'id',
    					'name',
						'type',
						'factory_name',
						'fzr',
						'phone',
					];
        $this->url = url('manage/detection_device');
    }
}