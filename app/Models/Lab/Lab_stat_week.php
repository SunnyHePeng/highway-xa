<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验周统计（弃）
 */
class Lab_stat_week extends Common
{
    protected $table = 'lab_stat_week';
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
                  'week',
                  'created_at'
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