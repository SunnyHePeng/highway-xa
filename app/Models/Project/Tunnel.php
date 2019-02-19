<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 隧道（弃）
 */
class Tunnel extends Common
{
    protected $table = 'tunnel';
    protected $fillable = ['id','project_id','section_id','name','left_begin_position','left_end_position','right_begin_position','right_end_position','length','station_num','status','created_at'];
   	protected $rule = [
   					'project_id'=>'required',
   					'section_id'=>'required',
                    'name'=>'required',
                    'left_begin_position'=>'required',
                    'left_end_position'=>'required',
                    'right_begin_position'=>'required',
                    'right_end_position'=>'required',
                    'length'=>'required',
                    'station_num'=>'required',
                    'status'=>'required',
                ];
    public $timestamps = false;

    
}