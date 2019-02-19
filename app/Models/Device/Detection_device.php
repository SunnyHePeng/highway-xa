<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 检测设备
 */
class Detection_device extends Common
{
    protected $table = 'detection_device';
    protected $fillable = [
    					'id',
    					'name',
						'type',
						'factory_name',
						'fzr',
						'phone',
  					];
   	protected $rule = [
   					'name'=>'required',
   					'type'=>'required',
					'factory_name'=>'required',
					'fzr'=>'required',
					'phone'=>'required',
                ];
    public $timestamps = false;

    
}