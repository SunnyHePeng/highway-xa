<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道进度总累计量-左右洞
 */
class SdtjFinishLf extends Common
{
    protected $table = 'sdtj_finish_lf';
    protected $fillable = ['id',
        'section_id',
        'time',
        'site',
        'adjj',
        'cqzh',
        'ygkw',
        'ygjz',
        'fsbpg',
        'ecjz',
        'gjbz',
        'bhcl',
    ];

    public $timestamps = false;


}