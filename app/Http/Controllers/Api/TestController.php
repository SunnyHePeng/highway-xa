<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class TestController extends BaseController
{
   
    /*通过curl发送get数据*/
    public function testget(){
        $url = Input::get('url');
        $header = array ();
        $time = time();
        $header [] = 'Package-Type: keep-alive';
        //$header [] = 'PackageSize: 1234';
        $header [] = 'Station-Code: 123';
        
        $ch = curl_init();
        //$post_data['data'] = $this->getBhz();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        //curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout on connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout on
        $info= curl_exec($ch);
        curl_close($ch);
        return $info;
    }

    /*通过curl发送post数据*/
    public function testpost(){
        $url = Input::get('url');//'http://www.wechat.com/api/v1.0';//
        $header = array ();
        $time = time();
        $header [] = 'Package-Type: cement';
        $header [] = 'PackageSize: ';
        $header [] = 'Station-Code: 123'; //789
        $ch = curl_init();
        $post_data['data'] = $this->getBhz();
        //$post_data['data'] = json_encode(['data'=>['cid'=>'5','serial'=>'5','mac'=>'5'],'timestamp'=>time(),'method'=>'device']);//$this->getEncryptData();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$header);
        //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $info= curl_exec($ch);
        curl_close($ch);
        return $info;
    }

    
}