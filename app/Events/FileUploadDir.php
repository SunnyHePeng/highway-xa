<?php

namespace App\Events;

use App\Events\Event;

/**
 * 获取上传目录
 * 
 * 直接去监听者获取，无需通过派发此事件
 */
class FileUploadDir extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $type;
    public function __construct($type)
    {
        $this->type = $type;
    }

    
}
