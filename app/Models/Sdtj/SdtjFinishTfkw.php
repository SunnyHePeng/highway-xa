<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道进度总累计量-土方开挖
 */
class SdtjFinishTfkw extends Common
{
    protected $table = 'sdtj_finish_tfkw';
    protected $fillable = ['id',
        'section_id',
        'time',
        'tfkw_finish'
    ];

    public $timestamps = false;


}