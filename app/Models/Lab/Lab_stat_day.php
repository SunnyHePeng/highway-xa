<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验日统计（弃）
 */
class Lab_stat_day extends Common
{
    protected $table = 'lab_stat_day';
    protected $fillable = [
        					'id',
        					'project_id',
		                  	'section_id',
		                  	'supervision_id',
      						'device_id',
      						'sy_num',
      						'bj_num',
                  'cl_num',
      						'bhgl',
                  'cll',
      						'date',
  						];
   	protected $rule = [
         			'device_id'=>'required',
                ];
    public $timestamps = false;

    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device', 'device_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function sup()
    {
        return $this->belongsTo('App\Models\Project\Supervision', 'supervision_id', 'id');
    }
}