<?php

namespace App\Models\Device;

use App\Models\Common;
use Input, DB;

/**
 * 车辆（弃）
 */
class Truck extends Common
{
    protected $table = 'truck';
    protected $fillable = [
    						'id',
    						'project_id',
    						'section_id',
    						'group_code',
    						'truck_num',
    						'truck_type',
    						'unit_name',
    						'driver_name',
    						'phone',
    						'by1_select',
    						'by2_select',
    						'by3_select',
    						'by4_select',
    						'by5_select',
    						'factory_code',
    						'created_at'
  						];
   	protected $rule = [
   					'project_id'=>'required',
    				'section_id'=>'required',
   					'group_code'=>'required',
					'truck_num'=>'required',
					'truck_type'=>'required',
					'unit_name'=>'required',
					'driver_name'=>'required',
					'phone'=>'required',
					'factory_code'=>'required'
                ];
    public $timestamps = false;

    public function section(){
    	return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function category(){
    	return $this->belongsTo('App\Models\Device\Truck_category', 'truck_type', 'id');
    }
}