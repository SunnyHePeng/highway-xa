<?php

namespace App\Models\Bhz;

use App\Models\Common;
use Input, DB;

/**
 * 拌合站数据
 */
class Snbhz_info extends Common
{
    protected $table = 'snbhz_info';
    protected $fillable = [
        					'id',
                  'project_id',
                  'section_id',
                  'supervision_id',
                  'device_cat',
                  'device_type',
        					'device_id',
      						'time',
                  'scdw',
                  'jldw',
      						'sgdw',
      						'sgdd',
      						'jzbw',
                  'pbbh',
                  'pfl',
                  'cph',
                  'driver',
                  'operator',
                  'kssj',
                  'jssj',
      						'is_warn',
      						'warn_level',
      						'warn_info',
      						'is_sec_deal',
                  'is_sup_deal',
                  'created_at',
                  'is_24_notice',
                  'is_48_notice',
                  'is_pro_deal',
                  'warn_sx_level',
                  'warn_sx_info'
  						];
   	protected $rule = [
 					'device_id'=>'required',
 					'time'=>'required',
                ];
    public $timestamps = false;

    public function device()
    {
        return $this->belongsTo('App\Models\Device\Device', 'device_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project\Project', 'project_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section', 'section_id', 'id');
    }

    public function sup()
    {
        return $this->belongsTo('App\Models\Project\Supervision', 'supervision_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Device\Device_type', 'device_type', 'id');
    }

    public function pbbh()
    {
        return $this->belongsTo('App\Models\Bhz\Pbbh', 'pbbh_id', 'id');
    }

    public function detail(){
        return $this->hasMany('App\Models\Bhz\Snbhz_info_detail_new', 'snbhz_info_id', 'id');
    }

    public function deal()
    {
        return $this->hasOne('App\Models\Bhz\Snbhz_info_deal', 'snbhz_info_id', 'id');
    }

    public function warn()
    {
        return $this->hasOne('App\Models\Bhz\Snbhz_warn_info', 'snbhz_info_id', 'id');
    }
}