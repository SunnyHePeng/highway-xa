<?php

namespace App\Messages\Wechat;

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
        return '8mUcnQa4PFTrHnVeGG2AnOQX_RMzUmmcWvqfoA6ZDTA';
    }

    /**
     * 模板参数
     * 
     * @return array
     */
    public function templateParam()
    {
        return [
            'first' => [
                'value'=> '233333333',
                'color'=> '#173177'
            ],
            'keyword1'=>[
                'value' => '23333333',
                'color'=>'#173177'
            ],
            'keyword2'=>[
                'value' =>' 施工进行中',
                'color' => '#173177'
            ],
            'keyword3' => [
                'value' => '2333333',
                'color' => '#173177'
            ],
            'keyword4'=> [
                'value' => date('Y年m月d日 H:i:s'),
                'color' => '#173177'
            ],
            'remark'=> [
                'value' => '请您注意跟进，谢谢',
                'color' => '#173177'
            ],
        ];
    }

    /**
     * 模板链接
     * 
     * @return string
     */
    public function templateLink()
    {
        return '';
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
