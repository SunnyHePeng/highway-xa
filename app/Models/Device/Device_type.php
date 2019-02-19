<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 设备类型（弃）
 */
class Device_type extends Common
{
    protected $table = 'device_type';
    protected $fillable = [
    						'id',
    						'category_id',
    						'name',
  						];
   	protected $rule = [
   					'name'=>'required',
                ];
    public $timestamps = false;

    
}