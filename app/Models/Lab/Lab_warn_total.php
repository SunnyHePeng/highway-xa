<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验报警统计（弃）
 */
class Lab_warn_total extends Common
{
    protected $table = 'lab_warn_total';
    protected $fillable = [
                      		'id',
            				      'project_id',
                          'supervision_id',
                      		'section_id',
                      		'device_id',
          					     'num',
          					     'date'
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