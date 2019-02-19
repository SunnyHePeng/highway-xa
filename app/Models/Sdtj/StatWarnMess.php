<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 报警信息统计
 * 
 * 根据module_id区分拌合站或试验室
 */
class StatWarnMess extends Common
{
    protected $table = 'stat_warn_mess';
    protected $fillable = ['id',
                          'supervision_name',
                          'section_id',
                           'section_name',
                          'module_id',
                          'bj_num',
                          'cl_num',
                          'time'
    ];

    public $timestamps = false;


}