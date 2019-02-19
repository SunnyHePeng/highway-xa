<?php

namespace App\Messages\Sms;

/**
 * 上传时间过期消息
 */
class ExpiredMessage implements MessageInterface
{
    /**
     * 模板
     * 
     * @return string
     */
    public function templateCode()
    {
        return 'SMS_135031190';
    }

    /**
     * 模板参数
     * 
     * @return array
     */
    public function templateParam()
    {
        return [
            'section'=>'abc',
            'tfkw'=>1000,
            'tfkw_finish'=>'bcd',
        ];
    }

    /**
     * 转化为字符串
     * 
     * @return array
     */
    public function __toString()
    {
        return var_export([
            'templateCode' => $this->templateCode(), 
            'templateParam' => $this->templateParam(), 
        ], true);
    }
}
