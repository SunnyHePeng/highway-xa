<?php

namespace App\Models\Project;

use App\Models\Common;
use Input, DB;

/**
 * 梁场（弃）
 */
class Beamfield extends Common
{
    protected $table = 'beamfield';
    protected $fillable = ['id','project_id','section_id','name','tz_num','status','created_at'];
   	protected $rule = [
   					        'project_id'=>'required',
   					        'section_id'=>'required',
                    'name'=>'required',
                    'tz_num'=>'required',
                    'status'=>'required',
                ];
    public $timestamps = false;

    
}