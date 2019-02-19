<?php

namespace App\Messages\Sms;

use App\Models\User\User;
use App\Send\SendSms;

/**
 * 短信（未使用）
 */
class Sms
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

        $result = (new SendSms)->send($this->user->phone, $message->templateParam(), $message->templateCode());
        
        static::after($message, $result);
    }

    /**
     * 发送前
     * 
     * @param \App\Messages\Sms\SmsInterface $message
     * @return void
     */
    public function before($message)
    {

    }

    /**
     * 发送后
     * 
     * @param \App\Messages\Sms\MessageInterface $message
     * @param object $result 
     * @return void
     */
    public function after($message, $result)
    {

    }

}
