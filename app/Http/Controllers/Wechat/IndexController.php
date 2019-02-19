<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\User\User,
    App\Models\Bhz\Snbhz_info,
    App\Models\Bhz\Snbhz_info_detail_new,
    App\Models\Bhz\Snbhz_info_deal;
use App\Http\Controllers\Admin\AuthController;

use Redirect, Input, Auth, Cache, DB, Session, Log;

class IndexController extends Controller
{
	protected $app_id = 'wxbadd458305537842';
	protected $app_secret = '2ae726b0862009ba233f2fefff1c75d1';
	protected $token = 'xawhgs2018';

	//微信中进入系统的首页
	public function index(Request $request){
		Log::info('wechat1');
		$url = url('wechat/openid');
		$url = $this->getWechatsCodeUrl($url);
		header('location:'.$url);
	}

	//获取code url
    public function getWechatsCodeUrl($url){
		$redirect_uri = urlencode($url);
		$codeUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->app_id.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
		return $codeUrl;
    }

    //获取openid
    public function openid(Request $request) {
		$code = Input::get('code');
		$state = Input::get('state');
		if (!$this->app_id || !$this->app_secret || !$code || !$state) { // 请求参数错误
			exit('invalid params!');
		}
		/*发送请求获取openId */
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
		$data = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->app_id.'&secret='.$this->app_secret.'&code='.$code.'&grant_type=authorization_code',false,stream_context_create($arrContextOptions));
		$data = json_decode($data, true);
		if (!$data['openid']) { // 获取失败
			exit($data['errmsg']);
		}
		Session::put('wechat_openid', $data['openid']);
		Log::info('openid '.$data['openid']);
		header('location:'.url('wechat/login'));
	}

	protected function LoginProject(){
		if(!Session::get('wechat_openid')){
			exit('invalid params!');
		}
		/*通过openid 获取用户信息，获取到后直接登录，
		没有获取到直接跳转到登录页面 然后绑定用户信息*/
		$openid = Session::get('wechat_openid');
		Log::info('openid2 '.$openid);
		$info = User::where('openid', $openid)->orderByRaw('wx_last_time desc')->first();
		if($info){
			Log::info('openid3 '.$openid);
			//Auth::loginUsingId($info->id);
			Auth::login($info);
			(new AuthController(request(), Auth::user()))->LoginInfo($info->name.'登录成功', $info->id, $info->name, 'l');
			$url = Session::get('wechat_url');
			Log::info($info->id.' 123 '.$url);
			Session::forget('wechat_url');
			if($url){
				header('location:'.$url);
			}else{
				header('location:'.url('index'));
			}
		}else{
			Log::info('openid4 login');
			header('location:'.url('manage/login'));
		}
	}

	public function checkToken(){
		$wechatObj = new wechatCallbackapiTest();
		if (isset($_GET['echostr'])) {
		  	$wechatObj->valid();
		}else{
		  	$wechatObj->responseMsg();
		}
	}

	//获取access_token
  	public function getAccessToken(){
  		if(Cache::get('wechat_access_token')){
  			return Cache::get('wechat_access_token');
  		}
  		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->app_id.'&secret='.$this->app_secret;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$access_token = $jsoninfo["access_token"];
		$expire_time = $jsoninfo["expires_in"]/60;
		Cache::put('wechat_access_token', $access_token, $expire_time);
		return $access_token;
  	}

	/*获取某条拌合信息的物料和处理信息*/
    public function getSnbhzDetailInfo(){
		Log::info('getSnbhzDetailInfo');
		$id = Input::get('id');
        if(!$id){
            exit('invalid params!');
        }
		Session::put('wechat_url', url('snbhz/detail_info?id='.$id));
		$url = Session::get('wechat_url');
		Log::info($url);
		header('location:'.url('wechat/index'));
    }

    /*获取某条试验信息的物料和处理信息*/
    public function getLabDetailInfo(){
		Log::info('getLabDetailInfo');
		$id = Input::get('id');
        if(!$id){
            exit('invalid params!');
        }
		Session::put('wechat_url', url('lab/lab_data_detail?id='.$id));
		$url = Session::get('wechat_url');
		Log::info($url);
		header('location:'.url('wechat/index'));
    }
}

/**
 * 微信验证服务器
 * 
 * 此类与 public/checkwechat.php 文件一致，responseMsg方法可能已经废弃，emptyempty方法没有定义
 */
class wechatCallbackapiTest
{
  	public function valid(){
	    $echoStr = $_GET["echostr"];
	    if($this->checkSignature()){
	      echo $echoStr;
	      exit;
	    }
  	}

  	private function checkSignature(){
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
	    $token = 'tljtxa2018';
	    $tmpArr = array($token, $timestamp, $nonce);
	    sort($tmpArr);
	    $tmpStr = implode( $tmpArr );
	    $tmpStr = sha1( $tmpStr );
	    if( $tmpStr == $signature ){
	      return true;
	    }else{
	      return false;
	    }
  	}

  	public function responseMsg(){
    	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	    if (!emptyempty($postStr)){
	      	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	      	$fromUsername = $postObj->FromUserName;
	      	$toUsername = $postObj->ToUserName;
	      	$keyword = trim($postObj->Content);
	      	$time = time();
	      	$textTpl = "<xml>
	            <ToUserName><![CDATA[%s]]></ToUserName>
	            <FromUserName><![CDATA[%s]]></FromUserName>
	            <CreateTime>%s</CreateTime>
	            <MsgType><![CDATA[%s]]></MsgType>
	            <Content><![CDATA[%s]]></Content>
	            <FuncFlag>0</FuncFlag>
	            </xml>";
	      	if($keyword != " " || !emptyempty( $keyword ) ){
	  			$msgType = "text";
	  			//$contentStr .= date("Y-m-d H:i:s",time());
	  			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	  			echo $resultStr;
	      	}
	    }else{
	      	echo "";
	      	exit;
	    }
  	}
}