<?php

namespace App\Messages\Wechat;

/**
 * 微信模板消息接口（未使用）
 */
interface MessageInterface
{
    /**
     * 模板code
     * 
     * @return string
     */
    public function templateCode();

    /**
     * 模板参数
     * 
     * @return array
     */
    public function templateParam();

    /**
     * 消息链接
     * 
     * @return string
     */
    public function templateLink();
}