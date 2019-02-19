<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 车辆分类（弃）
 */
class Truck_category extends Common
{
    protected $table = 'truck_category';
    protected $fillable = [
    						'id',
    						'name',
  						];
   	protected $rule = [
   					'name'=>'required',
                ];
    public $timestamps = false;
}