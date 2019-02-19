<?php

namespace App\Http\Middleware;

use Closure,Input,Auth,Log;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Device\Device;

class Checkheader
{
    public $station_code;
    public $device_id;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //判断header
        $result = $this->isTrueHeader($request);
        if(!$result){
            header("HTTP/1.1 404 Not Found");
            die;
        }
        if(isset($result['error_code'])){
            header("HTTP/1.1 404 Not Found.");
            die;
            /*$time = time();
        	return Response()->json($result)->header('Timestamp', $time)->header('Verify',md5($time.$this->device_id_enc));*/
        }
        //Log::info('API:'.$request->path().' machine_id:'.$this->machine_id);
	    return $next($request);
    }

    protected function isTrueHeader($request){
        $content_type = $request->header('Content-Type');
        $this->station_code = $station_code = $request->header('Station-Code');
        $package_type = $request->header('Package-Type');

        if($content_type && $station_code && $package_type){
            //验证时间戳
            /*$time = time();
            $abss = abs($timestamp-$time);
            if($abss > Config()->get('common.time_difference')){
                $result = ['error_code'=>'-2', 'error_message'=>'时间不同步'];
                Log::info('timestamp error:'.json_encode($result));
                return $result;
            }*/
            //验证content_type
            if($content_type != 'application/json'){
                $result = ['error_code'=>'1', 'error_message'=>'Content-Type error'];
                return $result;
            }
            //验证设备
            $this->device_id = Device::where('kzjbm',$station_code)->pluck('id');
            if(!$this->device_id){
                $result = ['error_code'=>'2', 'error_message'=>'Device在系统中不存在'];
                return $result;
            }
            //验证package_type
            if(!in_array($package_type, ['keep-alive', 'cement', 'test'])){
                $result = ['error_code'=>'3', 'error_message'=>'package_type error'];
                return $result;
            }
            //验证权限 md5(DeviceIdEnc + str(timestamp))
            /*$this->device_id_enc = $this->getDeviceIdEnc($device_id);
            
            if(md5($this->device_id_enc.$timestamp) != $verify){
                return false;
            }*/
        }else{
        	return false;
        }
        return true;
    }

    protected function countApi($request, $uri){
        //客户端
        if($request->is('api/client/note/*')){
            $uri = 'api/client/note';
        }
        //市场
        if($request->is('api/search/*')){
            $uri= 'api/search';
        }
        if($request->is('api/download/*')){
            $uri = 'api/download';
        }
        if($request->is('api/script/*')){
            $uri = 'api/script';
        }
        if($request->is('api/dhistory/*')){
            $uri = 'api/dhistory';
        }
        if($request->is('api/chistory/*')){
            $uri = 'api/chistory';
        }
        if(preg_match("@^(api/soft/)[0-9]+$@i", $uri, $matches)){
            $uri = 'api/soft/id';
        }
        if(preg_match("@(category/)[0-9]+(/soft)@i", $uri, $matches)){
            $uri = 'api/category/id/soft';
        }
        if(preg_match("@(soft/)[0-9]+(/gift)@i", $uri, $matches)){
            $uri = 'api/soft/id/gift';
        }
        $api_data = [
            'uri'=>$uri,
            'num'=>1,
            'created_at'=>time(),
        ];
        Api_access::create($api_data);
    }

    /**
    *获取加密后的device标识
    *取DeviceId中每一个字符加上10，若结果大于'z', 则减去75。例子如下: 如字符串xiaoym,计算方式如下:
    *'x' + 10 = 130, 130 > 'z', 130 -75 = 55 = '7'
    *'i' + 10 = 115, 115 < 'z', 115 = 's'
    *'a' + 10 = 107, 107 < 'z', 107  = 'k'
    *'o' + 10 = 121, 121 < 'z', 121 = 'y'
    *'y' + 10 = 131, 131 > 'z', 131 - 75 = 56 = '8'
    *'m' + 10 = 119, 119 < 'z', 119 = 'w'
    *
    */
    public function getDeviceIdEnc($device_id){
        $leng = strlen($device_id);
        $device_id_enc = '';
        for($i=0; $i<$leng; $i++){
            $str = substr($device_id, $i, 1);
            $new_str = ord($str) + 10;
            if($new_str > ord('z')){
                $new_str = $new_str - 75;
            }
            $device_id_enc .= chr($new_str);
        }
        return $device_id_enc;
    }
}
