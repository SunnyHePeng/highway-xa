<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道进度总量
 */
class SdtjTotal extends Common
{
    protected $table = 'sdtj_total';
    protected $fillable = ['id',
        'section_id',
        'site',
        'type',
        'type_name',
        'zl'
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id');
    }


}