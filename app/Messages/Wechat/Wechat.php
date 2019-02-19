<?php

namespace App\Messages\Wechat;

use App\Models\User\User;
use App\Send\SendWechat;

/**
 * 微信模板消息（未使用）
 */
class Wechat
{
    /**
     * 待发送用户
     * 
     * @var \App\Models\User\User
     */
    private $user;

    /**
     * 发送至
     * 
     * @param \App\Models\User\User $user
     * @return self
     */
    public function to(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * 发送内容
     * 
     * @param \App\Messages\Sms\MessageInterface $message
     * @return void
     */
    public function send(MessageInterface $message)
    {
        static::before($message);

        $link = $message->templateLink() ?: '';

        $result = (new SendWechat)->sendTemplateMessage($this->user->openid, $message->templateCode(), $message->templateParam(), $link);
        
        static::after($message, $result);
    }

    /**
     * 发送前
     * 
     * @param \App\Messages\Wechat\SmsInterface $message
     * @return void
     */
    public function before($message)
    {

    }

    /**
     * 发送后
     * 
     * @param \App\Messages\Wechat\MessageInterface $message
     * @param string $result 
     * @return void
     */
    public function after($message, $result)
    {
        // \Log::info($message, $result);
    }

}
