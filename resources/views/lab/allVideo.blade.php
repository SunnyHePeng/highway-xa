@extends('admin.layouts.iframe')

@section('container')
    <style type="text/css">
        body, article, #tab_demo {width: 90%; height: 90%;}
    </style>
    <p style="color:red;">仅支持IE,请使用IE8以上浏览器，且需安装控件包(默认显示当前设备视频)。<a href="{{Config()->get('common.app_url')}}/video/syjczx/video_kjb.rar" target="_blank">下载控件包</a></p>

    <div class="mt-10">
            <iframe id="iframe" src="{{Config()->get('common.app_url')}}/video/allVideo/PlayView-All-All.htm" frameborder='0' width='100%' height='100%'></iframe>
    </div>
@stop

<script type="text/javascript">

    function video(event){
        var url=event.getAttribute("dataUrl");
        $("#iframe").attr("src", url);
    }
</script>