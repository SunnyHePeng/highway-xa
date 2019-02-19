<?php

namespace App\Models\Sdtj;

use App\Models\Common;
use Input, DB;

/**
 * 资源配置信息发送状态
 */
class ResourceSendStatus extends Common
{
    protected $table = 'resource_send_status';
    protected $fillable = ['id',
        'time',
        'status',
    ];

    public $timestamps = false;


}