<?php

namespace App\Models\Video;

use App\Models\Common;
use Illuminate\Support\Facades\Auth;

/**
 * 监控
 *
 * 此类跟数据模型没有关系
 *
 */
class Video extends Common {

    public $url = [
        1 => ['name' => '西安外环高速公路南段', 'url' => 'http://222.90.232.86:8081', 'pro_id'=>4]
    ];

    public function token($status) {
        $hk_username = Auth::User()->hk_username;
        $hk_password = Auth::User()->hk_password;
        $url = isset($this->url[$status]['url']) ? $this->url[$status]['url'] : '';
        if(!$url){
            return '';
        }
        $token = @file_get_contents($url."/home/ssoTokenKey.action");
        $url = $url."/home/ssoLogin.action?redirectUrl=/vss/home/index.action?rurl=/video/vss_perview&userName=$hk_username&password=$hk_password&token=$token";
        return $url;

        /*switch ($status) {
            case 0:
                $token = @file_get_contents("http://61.185.204.119/home/ssoTokenKey.action");
                $url = "http://61.185.204.119/home/ssoLogin.action?redirectUrl=/vss/home/index.action?rurl=/video/vss_perview&userName=$hk_username&password=$hk_password&token=$token";
                return $url;
                break;
            case 1:
                $token = @file_get_contents("http://117.35.53.85:8081/home/ssoTokenKey.action");
                $url = "http://117.35.53.85:8081/home/ssoLogin.action?redirectUrl=/vss/home/index.action?rurl=/video/vss_perview&userName=$hk_username&password=$hk_password&token=$token";
                return $url;
                break;
            case 2:
                $token = @file_get_contents("http://1.85.19.170:8081/home/ssoTokenKey.action");
                $url = "http://1.85.19.170:8081/home/ssoLogin.action?redirectUrl=/vss/home/index.action?rurl=/video/vss_perview&userName=$hk_username&password=$hk_password&token=$token";
                return $url;
                break;
            case 3:
                $token = @file_get_contents("http://61.185.60.234:8081/home/ssoTokenKey.action");
                $url = "http://61.185.60.234:8081/home/ssoLogin.action?redirectUrl=/vss/home/index.action?rurl=/video/vss_perview&userName=$hk_username&password=$hk_password&token=$token";
                return $url;
                break;
            default:
                return "Not fied";
        }*/
    }

}
