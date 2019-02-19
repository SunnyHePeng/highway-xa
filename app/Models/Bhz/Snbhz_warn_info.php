<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站报警信息（弃）
 */
class Snbhz_warn_info extends Common
{
    protected $table = 'snbhz_warn_info';
    protected $fillable = [
                					'id',
                          'project_id',
                          'supervision_id',
                          'section_id',
                          'device_id',
                					'snbhz_info_id',
              						'warn_type',
              						'design_value',
              						'fact_value',
              						'design_pcl',
              						'fact_pcl',
          						];
   	protected $rule = [
         			'device_id'=>'required',
                ];
    public $timestamps = false;

    public function snbhz_info()
    {
        return $this->belongsTo('App\Models\Bhz\Snbhz_info', 'snbhz_info_id', 'id');
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