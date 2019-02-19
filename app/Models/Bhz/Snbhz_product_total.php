<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站生产总量
 */
class Snbhz_product_total extends Common
{
    protected $table = 'snbhz_product_total';
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