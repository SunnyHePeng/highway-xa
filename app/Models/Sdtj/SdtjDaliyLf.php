<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道进度日累积量-左右洞
 */
class SdtjDaliyLf extends Common
{
    protected $table = 'sdtj_daliy_lf';
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

    public function section()
    {
        return $this->belongsTo('App\Models\Project\Section','section_id','id');
    }


}