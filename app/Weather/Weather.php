<?php
namespace App\Weather;

/**
 * 获取天气信息
 */
class Weather {

    /**
     * 获取当天的天气信息
     * @param $location
     * @return bool|string
     */
    public function getWeatherMess($location)
    {
        //api地址
        $api_url='https://api.seniverse.com/v3/weather/daily.json';

        //获取签名
        $sign=$this->getSign();
        $url=$api_url.'?location='.$location.'&'.$sign;
        $result=file_get_contents($url);

        return $result;
    }


    //配置加密签名
    protected function getSign()
    {
        $key = "ardgxtpnaejqhrqy"; // api_key
        $uid = "U810E0548E"; // 用户
        // 获取当前时间戳，并构造验证参数字符串
        $keyname = "ts=".time()."&ttl=1800&uid=".$uid;
        // 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密
        $sig = base64_encode(hash_hmac('sha1', $keyname, $key, true));
        // 将上一步生成的加密结果用 base64 编码，并做一个 urlencode，得到签名 sig
        $signedkeyname = $keyname."&sig=".urlencode($sig);

        return $signedkeyname;
    }

}