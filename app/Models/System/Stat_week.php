<?php

namespace App\Models\System;

use App\Models\Common;
use Input, DB;

/**
 * 周统计
 * 
 * 根据module_id区分拌合场生产量或试验次数
 */
class Stat_week extends Common
{
    protected $table = 'stat_week';
    protected $fillable = [
        					'id',
        					'project_id',
		                  	'section_id',
		                  	'supervision_id',
                        'module_id',
      						'device_id',
      						'scl',
      						'sc_num',
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