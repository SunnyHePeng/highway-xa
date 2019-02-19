<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 设备用户（弃）
 */
class Device_user extends Common
{
    protected $table = 'device_user';
    protected $fillable = [
    					'id',
                        'project_id',
                        'supervision_id',
                        'section_id',
				        'name',
				        'phone',
				        'position',
				        'email',
                        'created_at'
  						];
   	protected $rule = [
   					'project_id'=>'required',
                    'supervision_id'=>'required',
                    'section_id'=>'required',
   					'name'=>'required',
   					'phone'=>'required'
                ];
    public $timestamps = false;

    
}