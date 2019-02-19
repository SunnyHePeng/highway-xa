<?php

namespace App\Models\Lab;

use App\Models\Common;
use Input, DB;

/**
 * 试验报警信息（弃）
 */
class Lab_warn_info extends Common
{
    protected $table = 'lab_warn_info';
    protected $fillable = [
                					'id',
                          'project_id',
                          'supervision_id',
                          'section_id',
                          'device_id',
                					'lab_info_id',
              						'warn_type',
                          'time'
              						];
   	protected $rule = [
         			'device_id'=>'required',
                ];
    public $timestamps = false;

    public function lab_info()
    {
        return $this->belongsTo('App\Models\Lab\Lab_info', 'lab_info_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device', 'device_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }
}