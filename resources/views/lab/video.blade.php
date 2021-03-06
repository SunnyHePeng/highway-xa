@extends('admin.layouts.iframe')

@section('container')
	<style type="text/css">
		body, #tab_demo {width: 100%; height: 100%;}
	</style>
	<div id="open_new">
		<p style="color:red;">仅支持IE,请使用IE10以上浏览器，且需安装控件包和补丁包。
            <a href="{{Config()->get('common.ocx_download_url')}}" target="_blank">下载控件包</a> &nbsp;&nbsp;
            <a href="{{Config()->get('common.app_url')}}/video/patch/OCX_patch.zip" target="_blank">下载补丁包</a>
        </p>
		<div class="mt-10">
			@if(empty($device))
				<span>该设备暂时没有视频</span>
			@else
				<div style="width:480px;float:left;">
					<input type="hidden" value="{{$device['camera1']}}" id="camera1" data-encoderuuid="{{$device['camera1_encoderuuid']}}"/>
					<input type="hidden" value="{{$device['camera2']}}" id="camera2" data-encoderuuid="{{$device['camera2_encoderuuid']}}" />
                    <div class="ActiveX">
                        <object classid="clsid:9ECD2A40-1222-432E-A4D4-154C7CAB9DE3" id="spv1"  width="100%" height="320px;"></object>
                    </div>
					@if(!empty($device['camera2']))
                        <div class="ActiveX">
                            <object classid="clsid:9ECD2A40-1222-432E-A4D4-154C7CAB9DE3" id="spv2"  width="100%" height="320px;"></object>
                        </div>
					@endif
                </div>
			@endif
            <div style="margin-left:490px;">
                <iframe id="data_iframe"  src="{{url('lab/getDataAtVideo/'.$device['id'].'?pro_id='.$project_id)}}" style="border:1px solid #eee;width:100%;height:95%;"></iframe>
            </div>
		</div>
	</div>
@stop
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/lib/MD5/md5.js"></script>
<script type="text/javascript">

//  **************************试验室实时监控的调用采用ocx-OpenAPI方式调用**************************
    $(function(){
        var userName='ChangAnDaXue';
        var pw='ChangAnDaXue123';
        var ip='175.6.228.72';
        var port='80';
        var host=ip+':'+port;
        var appkey="dc50e4f1";
        var secret="19ae479c81d14e42b7812fc3d5df02fa";
        var defaultUserUUid='da2139d154784554bc05973db4567dad';
        var time=new Date().getTime();
        var report='';
        var cameraId1 = document.getElementById("camera1").value;
        var cameraId2 = document.getElementById("camera2").value;
        var camera1_encoderuuid=$("#camera1").attr("data-encoderuuid");
        var camera2_encoderuuid=$("#camera2").attr("data-encoderuuid");
        //播放窗口初始化
        function videoInit(ocx_id){

            var ocxObj=$("#"+ocx_id).get(0);

            var languageType = 1;
            var ret = ocxObj.MPV_Init(languageType);

            //设置分屏数
            var set_split_num = ocxObj.MPV_SetPlayWndCount(1);
            if (ret != 0) {
                alert("初始化失败");
            }
            if(set_split_num !=0 ){
                alert("设置分屏数失败");
            }


        }

        //设置本地参数
        function SetLocalParam(ocx_id,camera_uuid) {
            var encoderuuid='';
            if(camera_uuid=='camera1'){
               encoderuuid=camera1_encoderuuid;
            }
            if(camera_uuid=='camera2'){
                encoderuuid=camera2_encoderuuid;
            }

            var ocxObj = document.getElementById(ocx_id);

            var devPxRa = screen.deviceXDPI / screen.logicalXDPI;
            var height = $('#'+ocx_id).height() * devPxRa ;
            var width = $('#'+ocx_id).width() * devPxRa;
            var version="1.0";
            var edcoding="UTF-8";
            var xml = '\<\?xml version='+ version +'encoding='+ edcoding +'\?\> ' +
                '<localParam> ' +
                '<width>' + width + '</width> ' +
                '<height>' + height + '</height> ' +
                '<picType>0</picType> ' +
                '<capturePath>C:\\Hikvision</capturePath> ' +
                '<recordSize>2</recordSize> ' +
                '<recordPath>C:\\Hikvision</recordPath> ' +
                '<limitPreviewTime>0</limitPreviewTime> ' +
                '<showMsgTip>1</showMsgTip> ' +
                '</localParam>';
            console.log(xml);
            var ret = ocxObj.MPV_SetLocalParam(xml);

            if (ret != 0) {
                alert("设置本地参数失败");
            }
        }
        //设置对讲参数
        function SetTalkParam(ocx_id,camera_uuid) {
            var encoderuuid='';
            if(camera_uuid=='camera1'){
                encoderuuid=camera1_encoderuuid;
            }
            if(camera_uuid=='camera2'){
                encoderuuid=camera2_encoderuuid;
            }

            var ocxObj = document.getElementById(ocx_id);
//
//            var devPxRa = screen.deviceXDPI / screen.logicalXDPI;
//            var height = $('#'+ocx_id).height() * devPxRa ;
//            var width = $('#'+ocx_id).width() * devPxRa;
            var version="1.0";
            var edcoding="UTF-8";
            var xml = '\<\?xml version='+ version +'encoding='+ edcoding +'\?\> ' +
                '<localParam> ' +
                '<deviceUUid>'+encoderuuid+'</deviceUUid> '+
                '</localParam>';
            console.log(xml);
            var ret = ocxObj.MPV_SetLocalParam(xml);

            if (ret != 0) {
                alert("设置语音对讲本地参数失败");
            }
        }

        /**
         * 获取token
         * @param url_param 请求url(去掉host域名)
         * @param request_param 请求参数 一个json对象
         * @param secret
         */
        function getToken(url_param,request_param,secret){
            // console.log(request_param);
            //1 将请求参数json对象转为json字符串
//        var request_param=JSON.stringify(request_param);
            //2 拼接字符串
            var token_str=url_param+request_param+secret;
            // console.log(token_str);
            //3 将拼接好的字符串进行md532位加密并转换为大写
            var token=hex_md5(token_str).toUpperCase();
            return token;
        }


        //获取网域信息
        function getNetZone(ocx_id,camera_uuid){
            var url_param='/openapi/service/base/netZone/getNetZones';
            var input_data={"appkey":appkey,"time":time,"opUserUuid":defaultUserUUid};
            //获取token
            var token=getToken(url_param,JSON.stringify(input_data),secret);
            var netZone=$.ajax({
                url: "http://" + '175.6.228.72:80' + "/openapi/service/base/netZone/getNetZones?token=" + token,
                type:"POST",
                contentType: "application/json; charset=utf-8",
                data:JSON.stringify(input_data),
                success: function (njVal) {
//                console.log(njVal)
                    if(njVal.data.length==0){
                        alert("获取网域信息失败");
                    }else {
                        //获取网域信息
                        var netZone=njVal.data[0].netZoneUuid;
                        //获取camera_uuid
                        var camera_uuid_text=$("#"+camera_uuid).val();
                        //获取预览报文
                        getviewreport(camera_uuid_text,netZone,ocx_id);

                    }
                }
            })
        }
        //获取预览报文
        function getviewreport(camera_uuid,netZone,ocx_id){
            var url_param='/openapi/service/vss/preview/getPreviewParamByCameraUuid';
            var input_data={"appkey":appkey,"time":time,"opUserUuid":defaultUserUUid,"cameraUuid":camera_uuid,"netZoneUuid":netZone};
            var token=getToken(url_param,JSON.stringify(input_data),"19ae479c81d14e42b7812fc3d5df02fa");
            $.ajax({
                url: "http://" + host + "/openapi/service/vss/preview/getPreviewParamByCameraUuid?token=" + token,
                type:"POST",
                contentType: "application/json; charset=utf-8",
                data:JSON.stringify(input_data),
                success: function (njVal) {
                     if(njVal.errorCode != 0){
                         alert('获取预览报文失败');
                     }else{
                         report=njVal.data;
                         console.log(report);
                         //调用预览接口
                         preview(ocx_id);
                     }


                }
            })
        }

        //监控预览接口
        function preview(ocx_id){
            var ocxObj=document.getElementById(ocx_id);
            //开始预览
            var res=ocxObj.MPV_StartPreview(report);
            if(res != 0){
                alert('视频预览失败');
            }

        }
        //设置窗口小工具
        function SetToolBar(ocx_id){
            var ocxObj = document.getElementById(ocx_id);
            var ids = '0,1,2,3,4,5,6,7,8,9';  //配置
            if (null == ids){
                alert("参数为空！");
                return ;
            }

            var ret = ocxObj.MPV_SetToolBar(ids);
            if (ret != 0){
                alert("自定义播放工具条按钮失败！");
            }
        }
        //初始化
        function monitorInit(ocx_id,camera_uuid){


            //调用播放窗口初始化
            videoInit(ocx_id);
            //设置窗口小工具
            SetToolBar(ocx_id);
            //设置本地参数
            SetLocalParam(ocx_id,camera_uuid);
            //设置语音对讲参数
            SetTalkParam(ocx_id,camera_uuid);

            //获取网域信息并调用预览
           getNetZone(ocx_id,camera_uuid);


        }


        monitorInit("spv1","camera1");
        if (cameraId2.length > 0) {
            monitorInit("spv2","camera2");
            }
    });



</script>
