<?php
namespace App\YSCloud;
use Mockery\Exception;
use Log,Cache;

/**
 *
 * 从萤石云获取先相关数据，
 * 例如获取萤石云的accessToken，向外提供accessToken
 */
class YSCloud {

      /*萤石云appKey*/
      protected $appKey='cf850827c1904cdd8a3a9bcbc8e475d9';

      /*萤石云secret*/
      protected $secret='891cc9752cb150317adfc37ceb823e55';

      /*萤石云获取accessToken的请求地址*/
      protected $accessTokenUrl='https://open.ys7.com/api/lapp/token/get';

    /**
     * 向外提供accessToken
     */
      public function getAccessToken()
      {
           /**
            * 1，从memcache中获取萤石云accessToken
            * 2，能取到，则返回给调用，如获取不到，说明accessToken已过期
            * 3，重新从萤石云服务器获取accessToken，将accessToken返回并存入memcache
            */
            if(Cache::get('YSCloudAccessToken')){

               $accessToken=Cache::get('YSCloudAccessToken');
            }else{
               //从萤石云服务器获取accessToken,有效期为6天
                $accessToken=$this->accessTokenFromYS();
                Cache::put('YSCloudAccessToken', $accessToken, 6*1440);
            }
            return $accessToken;
      }

    /**
     *向外提供萤石云appKey
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

      /**
       * 从萤石云服务器端获取accessToken
       */
      protected function accessTokenFromYS()
      {
          //curl初始化
          $curl=curl_init();
          //设置要抓取的url
          curl_setopt($curl,CURLOPT_URL,$this->accessTokenUrl);
          curl_setopt($curl, CURLOPT_HEADER, false);
          curl_setopt($curl, CURLOPT_NOBODY, true);
          //设置获取的信息以文件流的形式返回，而不是直接输出。
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          //设置post方式提交
          curl_setopt($curl, CURLOPT_POST, 1);
          //设置post数据
          $post_data = [
                 'appKey'=>$this->appKey,
                 'appSecret'=>$this->secret
          ];
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

          $res = curl_exec($curl);

          //关闭该curl回话
          curl_close($curl);

          try{
              $data=json_decode($res,true);

              if($data['code'] == '200'){
                  return $data['data']['accessToken'];
              }else{
                  Log::info('get accessToken from YS error start');
                  Log::info($res);
                  Log::info('get accessToken from YS error end');
              }
          }catch (Exception $e){
             Log::info('get accessToken from YS error start');
             Log::info($e);
             Log::info($res);
             Log::info('get accessToken from YS error end');
          }

      }



}