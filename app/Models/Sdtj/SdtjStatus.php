<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道统计通知发送状态
 */
class SdtjStatus extends Common
{
    protected $table = 'sdtj_status';
    protected $fillable = ['id',
        'time',
        'send_status'
    ];

    public $timestamps = false;


}