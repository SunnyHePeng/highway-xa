<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站月生产总量（弃）
 */
class Snbhz_stat_month extends Common
{
    protected $table = 'snbhz_stat_month';
    protected $fillable = [
        					'id',
        					'project_id',
		                  	'section_id',
		                  	'supervision_id',
      						'device_id',
      						'scl',
      						'sc_num',
      						'bj_num',
      						'cl_num',
      						'bhgl',
                  			'cll',
      						'month',
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