<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 资源配置-施工人员
 */
class ResourceSgry extends Common
{
    protected $table = 'resource_sgry';
    protected $fillable = ['id',
        'section_id',
        'time',
        'site',
        'zzmkw',
        'cqzh',
        'ygkw',
        'ygjz',
        'fsbpg',
        'ecjz'
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }

}