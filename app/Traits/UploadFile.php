<?php

namespace App\Traits;

use App\Listeners\FileUploadDirListener;

/**
 * 上传文件
 */
trait UploadFile
{
    // private $_extensions = [];
    // private $_max_size = 1024 * 1024;
    //使用时自定义

    /**
     * 上传文件
     * 
     * @param string $parentDir
     * @return string
     */
    protected function _upload($name, $parentDir)
    {
        if($this->_validate($name)){

            $newName = $this->_generateFileName($name);
            $path = $this->_getDir($parentDir);

            request()->file('file')->move($path, $newName);

            return $path . '/' . $newName;
        }
        
        return false;
    }

    /**
     * 获取新文件名
     * 
     * @return string
     */
    protected function _generateFileName($name)
    {
        return uniqid() . '.' . request()->file($name)->guessExtension();
    }

    /**
     * 验证文件
     * 
     * @return boolen
     */
    protected function _validate($name)
    {
        return request()->hasFile($name) && request()->file($name)->isValid() && $this->_validateSize($name) && $this->_validateExtension($name);
    }

    /**
     * 判断文件类型
     * 
     * @return boolen
     */
    protected function _validateExtension($name)
    {
        return in_array(request()->file($name)->guessExtension(), $this->_extensions);
    }

    /**
     * 判断文件大小
     * 
     * @return boolen
     */
    protected function _validateSize($name)
    {
        return true;
        return request()->file($name)-> getMaxFilesize() <= (isset($this->_max_size) ? $this->_max_size : 1024 * 1024);
    }

    /**
     * 获取上传目录
     * 
     * @return string
     */
    protected function _getDir($parentDir)
    {
        $upload_path = (new FileUploadDirListener)->getUploadsDir($parentDir);//此方法得到路径用/，没用 DIRECTORY_SEPARATOR

        if(strpos($path = config('common.upload_path'), './') === 0){
            return substr($path, 2) . $upload_path;
        }

        return $path . $upload_path;
    }

}