<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ckplayer</title>
    <script type="text/javascript" src="/static/admin/js/ckplayer/ckplayer.js" charset="UTF-8"></script>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0px;
            font-family: "Microsoft YaHei", YaHei, "微软雅黑", SimHei, "黑体";
            font-size: 14px
        }
    </style>
</head>
<body>
<div id="video" style="width: 1000px; height: 600px; margin: 0px auto;"></div>
<p id="videoFiles">
    @foreach($videoFile as $v)
        <input type="hidden" value="{{$v}}" class="videoFile">
    @endforeach
</p>
<script type="text/javascript">
//    var videoFiles = document.getElementById('VideoFile');
    var inputs = videoFiles.getElementsByTagName('input');
    var files=[];
    {{--@foreach ($videoFile as $file)--}}
     {{--files.push('{{ $file }}');--}}
    {{--@endforeach--}}
//console.log(files);
//    console.log(inputs);
    for(var i = 0; i < inputs.length; i++) {
        if(inputs[i].value.slice(-4) == '.mp4'){
            files.push([inputs[i].value, 'video/mp4', '视频' + (i + 1), i]);
        }
        if(inputs[i].value.slice(-4) == '.flv'){
            files.push([inputs[i].value, 'video/flv', '视频' + (i + 1), i]);
        }

    }
    console.log(files);
    var videoObject = {
        //playerID:'ckplayer01',//播放器ID，第一个字符不能是数字，用来在使用多个播放器时监听到的函数将在所有参数最后添加一个参数用来获取播放器的内容
        container: '#video', //容器的ID或className
        variable: 'player', //播放函数名称
        loop: true, //播放结束是否循环播放
        config: '', //指定配置函数
        autoplay: true, //是否自动播放
        debug: true, //是否开启调试模式
        //flashplayer: true, //强制使用flashplayer
        drag: 'start', //拖动的属性
        seek: 0, //默认跳转的时间
        video: files
    };
    var player = new ckplayer(videoObject);
</script>
</body>

</html>