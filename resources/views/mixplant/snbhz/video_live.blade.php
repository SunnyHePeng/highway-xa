@extends('admin.layouts.iframe')

@section('container')

    <style type="text/css">
        body, #tab_demo {width: 100%; height: 100%;}
    </style>
    <div id="open_new">
        <p style="color:red;">仅支持IE,请使用IE8以上浏览器，且需安装控件包。<a href="{{Config()->get('common.app_url')}}/video/EZUIKit.exe" target="_blank">下载控件包</a></p>
        <div class="mt-10">
            @if(empty($device))
                <span>该设备暂时没有视频</span>
            @else
                <div style="width:680px;float:left;">
                    <input type="hidden" value="{{'ezopen://open.ys7.com/'.$device->camera1.'/'.$device->camera1_encoderuuid.'.hd.live?mute=true'}}" name="Eurl" id="Eurl"/>
                    <input type="hidden" value="{{$appKey}}" id="appKey" name="appKey">
                    <input type="hidden" value="{{$accessToken}}" id="accessToken" name="accessToken">
                    @if($device->camera1)
                    <div class="mb-10 full-video ">
                        <object classid="clsid:54FC7795-1014-4BF6-8BA3-500C61EC1A05" id="EZUIKit" width="100%" height="45%" name="EZUIKit" ></object>
                    </div>
                    <div style="width: 100%;" class="mb-10 text-r">
                        <input class="btn btn-default radius size-M mr-30 start" type="button" value="开始对讲">
                        <input class="btn btn-default radius size-M stop" type="button" value="结束对讲">
                    </div>
                    @endif
                    <iframe src="{{url('snbhz/get_new_info_at_video?device_id='.$device->id)}}" frameborder="0" style="width:100%;height:45%; border: 1px solid #eee;margin-top: 10px;"></iframe>
                </div>
            @endif

            <div style="margin-left:690px;">
                <iframe id="data_iframe" src="{{url('snbhz/product_data_at_video/'.$device->id)}}" style="border:1px solid #eee;width:100%;height:calc(100vh - 60px)"></iframe>
            </div>
        </div>
    </div>
@stop
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">

        //拌合数据的弹出层
        function getlayer(title,url){
            layer.open({
                type:2,
                area: ['90%', '90%'],
                zIndex:10000,
                title:false,
                content:url,
            });
        }
//handle message msgtype
        var EZUI_MSGID_PLAY_EXCEPTION 			 = 0;			//播放异常
        var EZUI_MSGID_PLAY_RECONNECT 			 = 1;			//播放重连
        var EZUI_MSGID_PLAY_RECONNECT_EXCEPTION  = 2;			//播放重连异常
        var EZUI_MSGID_PLAY_START 				 = 3;			//播放开始
        var EZUI_MSGID_PLAY_STOP 				 = 4;			//播放停止
        var EZUI_MSGID_PLAY_ARCHIVE_END 		 = 5;			//回放结束
        var EZUI_MSGID_VOICETALK_START 		     = 16;			//语音对讲开始
        var	EZUI_MSGID_VOICETALK_STOP 			 = 17;			//语音对讲停止
        var	EZUI_MSGID_VOICETALK_EXCEPTION 	     = 18;			//语音对讲异常
        var EZUI_MSGID_RECORD_FILE 			     = 20;			//查询的录像文件
        var EZUI_MSGID_PTZCTRL_SUCCESS		     = 46;			//云台控制命令发送成功
        var EZUI_MSGID_PTZCTRL_FAILED			 = 47;			//云台控制失败

        var EZUI_ERROR_ACCESSTOKEN_EXPIRE        = "UE001"; 	///< accesstoken异常或失效，需要重新获取accesstoken，并传入到sdk
        var EZUI_ERROR_APPKEY_TOKEN_NOT_MATCH    = "UE002";     ///< appkey和AccessToken不匹配,建议更换appkey或者AccessToken
        var EZUI_ERROR_CHANNEL_NOT_EXIST	     = "UE004";     ///< 通道不存在，设备参数错误，建议重新获取播放地址
        var EZUI_ERROR_DEVICE_NOT_EXIST	         = "UE005";     ///< 设备不存在，设备参数错误，建议重新获取播放地址
        var EZUI_ERROR_PARAM_INVALID	         = "UE006";     ///< 参数错误，建议重新获取播放地址
        var EZUI_ERROR_EZOPEN_URL_INVALID	     = "UE007";     ///< 播放地址错误,建议重新获取播放地址
        var EZUI_ERROR_NO_RESOURCE 			 	 = "UE101";	    ///< 设备连接数过大，停止其他连接后再试试
        var EZUI_ERROR_DEVICE_OFFLINE   	 	 = "UE102"; 	///< 设备不在线，确认设备上线之后重试
        var EZUI_ERROR_CONNECT_DEVICE_TIMEOUT    = "UE103"; 	///< 播放失败，请求连接设备超时，检测设备网路连接是否正常.
        var EZUI_ERROR_INNER_VERIFYCODE   		 = "UE104"; 	///< 视频验证码错误，建议查看设备上标记的验证码
        var EZUI_ERROR_PLAY_FAIL       	 		 = "UE105"; 	///< 视频播放失败
        var EZUI_ERROR_TERMINAL_BINDING   		 = "UE106"; 	///< 当前账号开启了终端绑定，只允许指定设备登录操作
        var EZUI_ERROR_DEVICE_INFO_INVALID       = "UE107"; 	///< 设备信息异常为空，建议重新获取播放地址
        var EZUI_ERROR_VIDEO_RECORD_NOTEXIST     = "UE108"; 	///< 未查找到录像文件
        var EZUI_ERROR_VTDU_NO_RESOURCE        	 = "UE109"; 	///< 取流并发路数限制,请升级为企业版.
        var EZUI_ERROR_UNSUPPORTED        		 = "UE110"; 	///< 设备不支持的清晰度类型, 请根据设备预览能力级选择.


        //检测控件是否安装
        function TestActiveX()
        {
            try {
                var ax = new ActiveXObject("EZOPENUIACTIVEXK.EzOpenUIActiveXKCtrl.1");
                bInstall = true;
//                   alert("已安装");
            } catch(e) {
                alert("实时视频控件未安装");
            }
        }

        //设置取流参数
        function SetParame(appKey,accessToken,playObj)
        {

            if(appKey == ''){
                alert("appKey获取失败");
            }
            if(accessToken == ''){
                alert("accessToken获取失败");
            }

            //初始化appKey
            var initAppKeyRes=playObj.InitWithAppKey(appKey);

            if(initAppKeyRes != 0){
                alert('初始化appKey失败');
            }
            //设置AccessToken
            var setAccessTokenRes=playObj.SetAccessToken(accessToken);

            if(setAccessTokenRes != 0){
                alert("设置accessToken出现错误");
            }
        }

        function PluginEventHandler(lEventType, strErrorCode, lInterErrorCode){

            switch(lEventType)
            {
                case EZUI_MSGID_PLAY_START:		//播放开始
                {
                    $(".start").removeClass('btn-default').addClass('btn-success').addClass('start-talk');
                    $(".stop").addClass('stop-talk');
                }
                    break;
                case EZUI_MSGID_PLAY_EXCEPTION:	//播放异常
                {
                    var errinfo;
                    if(strErrorCode == EZUI_ERROR_ACCESSTOKEN_EXPIRE)
                    {
                        errinfo = "accesstoken异常或失效，需要重新获取accesstoken，并传入到sdk";
                        alert(errinfo);
                    }
                    else if(strErrorCode == EZUI_ERROR_APPKEY_TOKEN_NOT_MATCH)
                    {
                        errinfo = "ppkey和AccessToken不匹配,建议更换appkey或者AccessToken";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_CHANNEL_NOT_EXIST)
                    {
                        errinfo = "通道不存在，设备参数错误，建议重新获取播放地址";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_DEVICE_NOT_EXIST)
                    {
                        errinfo = "设备不存在，设备参数错误，建议重新获取播放地址";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_PARAM_INVALID)
                    {
                        errinfo = "参数错误，建议重新获取播放地址";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_EZOPEN_URL_INVALID)
                    {
                        errinfo = "播放地址错误,建议重新获取播放地址";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_NO_RESOURCE)
                    {
                        errinfo = "设备连接数过大，停止其他连接后再试试";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_DEVICE_OFFLINE)
                    {
                        errinfo = "设备不在线，确认设备上线之后重试";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_CONNECT_DEVICE_TIMEOUT)
                    {
                        errinfo = "播放失败，请求连接设备超时，检测设备网路连接是否正常.";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_INNER_VERIFYCODE)
                    {
                        errinfo = "视频验证码错误，建议查看设备上标记的验证码";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_PLAY_FAIL)
                    {
                        errinfo = "视频播放失败";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_TERMINAL_BINDING)
                    {
                        errinfo = "当前账号开启了终端绑定，只允许指定设备登录操作";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_DEVICE_INFO_INVALID)
                    {
                        errinfo = "设备信息异常为空，建议重新获取播放地址";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_VIDEO_RECORD_NOTEXIST)
                    {
                        errinfo = "未查找到录像文件";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_VTDU_NO_RESOURCE)
                    {
                        errinfo = "取流并发路数限制,请升级为企业版.";
                        alert(errinfo);

                    }
                    else if(strErrorCode == EZUI_ERROR_UNSUPPORTED)
                    {
                        errinfo = "设备不支持的清晰度类型, 请根据设备预览能力级选择";
                        alert(errinfo);

                    }


                }
                    break;
                case EZUI_MSGID_PLAY_STOP:			//播放停止
                {

                }
                    break;
                case EZUI_MSGID_RECORD_FILE:		//录像搜索成功
                {
                    gPlaybackSearchRecord = "录像搜索成功:" + strErrorCode;
                }
                    break;
                case EZUI_MSGID_VOICETALK_START:		//对讲开启
                {
                    var info = "对讲开启成功";

                }
                    break;
                case EZUI_MSGID_VOICETALK_STOP:		//对讲开启
                {
                    var info = "对讲停止成功";

                }
                    break;
                case EZUI_MSGID_VOICETALK_EXCEPTION:
                {
                   var errorInfo='语音对讲异常';

                   alert(errorInfo);
                }
                   break;
                case EZUI_MSGID_PTZCTRL_SUCCESS:		//云台控制成功
                {
                    var info = "云台控制信令发送成功";
                }
                    break;
                case EZUI_MSGID_PTZCTRL_FAILED:		//云台控制失败
                {
                    var info = "云台控制失败";
                }
                    break;
                default:
            }

        }

        function init(){
            var appKey=$("#appKey").val();
            var accessToken=$("#accessToken").val();
            var Eurl=$("#Eurl").val();

            console.log(Eurl);
            //获取控件引用
            var playObj=document.getElementById('EZUIKit');
            //检测控件
            TestActiveX();
            //设置取流参数
            SetParame(appKey,accessToken,playObj);
            //调用播放
            var playRes=playObj.StartPlay(Eurl);
            if(playRes == 0){

            }
        }

    window.onload=function(){
        init();

    }



</script>
<script language="javascript" for="EZUIKit" event="PluginEventHandler(lEventType, strErrorCode, lInterErrorCode)">   //打开预览时触发该事件
    PluginEventHandler(lEventType, strErrorCode, lInterErrorCode);
    //开始对讲
    $(".start-talk").on('click',function(){
        if($(this).hasClass('btn-success')){
            var playObj=document.getElementById('EZUIKit');
            $(this).removeClass('btn-success').removeClass('start-talk').addClass('btn-default');
            $('.stop-talk').removeClass('btn-default').addClass('btn-danger');
            var startRes=playObj.startTalk();
            if(startRes != 0){
                 alert('语音对讲失败');
            }
        }

    });
    //结束对讲
    $(".stop-talk").on('click',function(){
          if($(this).hasClass("btn-danger")){
              var playObj=document.getElementById('EZUIKit');
              $(this).removeClass('btn-danger').addClass('btn-default');
              $(".start").removeClass('btn-default').addClass('btn-success').addClass('start-talk');
              var stopRes=playObj.stopTalk();
              if(stopRes != 0){
                  alert('对讲结束失败');
              }
          }
      });

</script>