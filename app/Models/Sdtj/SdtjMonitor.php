<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道监控量测信息
 */
class SdtjMonitor extends Common
{
    protected $table = 'sdtj_monitor';
    protected $fillable = ['id',
        'section_id',
        'l_stake_number',
        'l_dnwgc_status',
        'l_dnwgc_remark',
        'l_zbwy_measure_value',
        'l_zbwy_status',
        'l_zbwy_remark',
        'l_gdxc_measure_value',
        'l_gdxc_status',
        'l_gdxc_remark',
        'l_dbxc_measure_value',
        'l_dbxc_status',
        'l_dbxc_remark',
        'r_stake_number',
        'r_dnwgc_status',
        'r_dnwgc_remark',
        'r_zbwy_measure_value',
        'r_zbwy_status',
        'r_zbwy_remark',
        'r_gdxc_measure_value',
        'r_gdxc_status',
        'r_gdxc_remark',
        'r_dbxc_measure_value',
        'r_dbxc_status',
        'r_dbxc_remark',
        'time',
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }


}