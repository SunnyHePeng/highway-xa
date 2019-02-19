@extends('admin.layouts.iframe')

@section('container')

<style type="text/css">
    body, #tab_demo {width: 100%; height: 100%;}
</style>
<div id="open_new">
    <p style="color:red;">仅支持IE,请使用IE10及以上浏览器，且需安装控件包。<a href="{{Config()->get('common.app_url')}}/video/syjczx/video_kjb.rar" target="_blank">下载控件包</a></p>
    <div class="mt-10">
        @if(empty($device))
        <span>该设备暂时没有视频</span>
        @else
        <div style="width:480px;float:left;">
            <input type="hidden" value="{{$device['camera1']}}" id="camera1" />
            <input type="hidden" value="{{$device['camera2']}}" id="camera2" />
            <object classid="clsid:AC036352-03EB-4399-9DD0-602AB1D8B6B9" id="PreviewOcx" width="100%" height="320">
            </object>
            @if(!empty($device['camera2']))
            <object classid="clsid:AC036352-03EB-4399-9DD0-602AB1D8B6B9" id="PreviewOcx2" width="100%" height="320" style="margin-top:10px">
            </object>
            @endif
            <iframe src="{{url('snbhz/get_new_info_at_video?device_id='.$device['id'])}}" frameborder="0" style="width:100%;height:55%; border: 1px solid #eee;margin-top: 10px;"></iframe>
        </div>
        @endif
        <div style="margin-left:490px;">
            <iframe id="data_iframe" src="{{url('snbhz/product_data_at_video/'.$device['id'].'?pro_id='.$project_id)}}" style="border:1px solid #eee;width:100%;height:calc(100vh - 60px)"></iframe>
        </div>
    </div>
</div>
@stop

<script type="text/javascript">
    //拌合站实时视频调用使用ocx-preview方式调用
    //拌合数据的弹出层
    function getlayer(title,url){
        layer.open({
            type:2,
            area: ['90%', '90%'],
            zIndex:10000,
            title:title,
            content:url,
        });
    }
    function loginCMS(elem){
        var userName="admin";
        var pw="Hxtm12345+";
        var ipAdd="222.90.232.86";
        var port="8081";
        var ocxObj = document.getElementById(elem);
        ocxObj.SetLoginType(0);    // 设置同步登入模式
        var ret = ocxObj.Login(ipAdd, port, userName, pw);
        if (ret == -1){
            alert(elem + "登录失败！");
        }

    }

    function startPreview(elem, cameraId) {
        var ocxObj = document.getElementById(elem);
        ocxObj.StartTask_Preview_FreeWnd(cameraId);
    }

    function SetWndNum(elem, WndNum)
    {
        var ocxObj = document.getElementById(elem);
        ocxObj.SetWndNum(WndNum);
    }

    function initCameraList(elem){
        var ocxObj = document.getElementById(elem);
        ocxObj.GetResourceInfo(4);
    }

    window.onload=function()
    {
        setTimeout(function(){
            var cameraId1 = document.getElementById("camera1").value;
            var cameraId2 = document.getElementById("camera2").value;
            loginCMS("PreviewOcx");
            SetWndNum("PreviewOcx", "1");
            initCameraList("PreviewOcx");
            startPreview("PreviewOcx", cameraId1);
            if (cameraId2.length > 0) {
                loginCMS("PreviewOcx2");
                SetWndNum("PreviewOcx2", "1");
                initCameraList("PreviewOcx2");
                startPreview("PreviewOcx2", cameraId2);
            }
        }, 100);
    }
</script>