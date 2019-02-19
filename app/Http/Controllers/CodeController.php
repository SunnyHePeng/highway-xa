<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Img, App\Models\Common, Input, Auth, Log, Session;
use App\Models\User\User;
use App\Send\SendSms;
class CodeController extends BaseController
{
    /*获取图片验证码*/
    public function code()
    {
    	$width='160';
    	$height='38';
    	$num=4;
    	if(Input::get('width')){
    		$width=Input::get('width');
    	}
    	if(Input::get('height')){
    		$height=Input::get('height');
    	}
    	if(Input::get('num')){
    		$num=Input::get('num');
    	}
        return (new Img($width, $height, $num))->code();
    }

    /*获取手机验证码*/
    public function pcode(){
        $result = ['status'=>0, 'info'=>'参数错误'];
        $type = Input::get('type'); 
        if($type == 'findpass'){  //找回密码验证
            $verifyinfo = Session::get('verifyinfo');
            $phone = $verifyinfo['phone'];
            $temp_code = 'SMS_118825002';
        }elseif($type == 'verifyip'){   //更换IP地址验证
            $verifyinfo = Session::get('verifyinfo');
            $phone = $verifyinfo['phone'];
            $temp_code = 'SMS_118825004';
        }else{      //注册验证
            $phone = Input::get('mobile');
            //判断手机号是否重复
            if(User::where('phone', $phone)->first()){
                $result = ['status'=>0, 'info'=>'该手机号已被注册'];
                return Response()->json($result);
            }
            $temp_code = 'SMS_118825003';
        }

        if($phone){
            $code = (new Common)->getNum(6);
            Session::put('pcode', $code);
            Session::put('pmobile', $phone); 
            
            $temp_param = ['code'=>$code];
            $result = (new SendSms)->send($phone, $temp_param, $temp_code);
            Log::info('SendSms');
            Log::info(json_encode($result));
            if($result->Code == 'OK'){
                $result = ['status'=>1, 'info'=>'获取验证码成功'];
            }elseif($result->Code == 'isv.BUSINESS_LIMIT_CONTROL'){
                $result = ['status'=>0, 'info'=>'获取次数超过限制/太频繁'];
            }elseif($result->Code == 'isv.MOBILE_NUMBER_ILLEGAL'){
                $result = ['status'=>0, 'info'=>'手机号错误'];
            }else{
                $result = ['status'=>0, 'info'=>'获取验证码失败'];
            }
        }
        return Response()->json($result);
    }
}
