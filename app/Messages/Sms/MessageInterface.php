<?php

namespace App\Messages\Sms;

/**
 * SMS消息接口（未使用）
 */
interface MessageInterface
{
    /**
     * 获取模板code
     * 
     * @return string
     */
    public function templateCode();

    /**
     * 获取模板参数
     * 
     * @return array
     */
    public function templateParam();
}