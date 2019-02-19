<?php

namespace App\Listeners;

use App\Events\GetFileInfo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 获取文件信息
 * 
 * 直接调用文件信息的方法即可，无需通过事件派发
 */
class GetFileInfoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GetFileInfo  $event
     * @return void
     */
    public function handle(GetFileInfo $event)
    {
        return $event->get_file_info();
    }
}
