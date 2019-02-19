<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道进度日累计量-土方开挖
 */
class SdtjDaliyTfkw extends Common
{
    protected $table = 'sdtj_daliy_tfkw';
    protected $fillable = ['id',
        'section_id',
        'time',
        'tfkw'
    ];
    public $timestamps = false;
}