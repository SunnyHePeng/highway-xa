<?php

namespace App\Listeners;

use App\Events\FileUploadDir;

/**
 * 获取上传文件目录
 * 
 * 直接调用此类的方法即可获取上传文件目录，无需通过事件派发到此
 */
class FileUploadDirListener
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
     * @param  FileUploadDir  $event
     * @return void
     */
    public function handle(FileUploadDir $event)
    {
        return $this->getUploadsDir($event->type);
    }

    //$type  文件类型  获取文件名称
    public function getUploadsDir($type){
        $date = date('Y-m');
        $filename = Config()->get('common.upload_path').$type.'/'.$date;
        if(!is_dir($filename)){
            if($this->mkdirs($filename)){
                return $type.'/'.$date;
            }
            return false;
        }
        return $type.'/'.$date;
    }

    /*
     创建文件夹
     $dir       文件夹路径
    */
    protected function mkdirs($dir){
        //dd($dir);
        if(!is_dir($dir)){  
            if(!is_dir(dirname($dir))){
                $this->mkdirs(dirname($dir));
                mkdir($dir);
                return true;
            }  
            if(!mkdir($dir,0777)){  
                return false;  
            }  
        }  
        return true;  
    }
    
}
