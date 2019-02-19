<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 资源配置-机械设备
 */
class ResourceJxsb extends Common
{
    protected $table = 'resource_jxsb';
    protected $fillable = ['id',
        'section_id',
        'time',
        'site',
        'spjxs',
        'fsbpgtc',
        'ectc',
        'ecystc',
        'ygmbtc',
        'sgdlctc',
        'wpc',
        'wjj',
        'zzj',
        'zxc',
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }


}