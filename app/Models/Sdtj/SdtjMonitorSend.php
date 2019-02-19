<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 隧道监控量测通知发送状态
 */
class SdtjMonitorSend extends Common
{
    protected $table = 'sdtj_monitor_send_status';
    protected $fillable = ['id',
        'time',
        'status'
    ];

    public $timestamps = false;


}