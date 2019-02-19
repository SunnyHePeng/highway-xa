<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!-- <link rel="Bookmark" href="favicon.ico" >
<link rel="Shortcut Icon" href="favicon.ico" /> -->
<!--[if lt IE 9]>
<script type="text/javascript" src="/lib/html5.js"></script>
<script type="text/javascript" src="/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>{{ Config()->get('common.app_name') }}</title>
<meta name="keywords" content="{{ Config()->get('common.keywords') }}">
<meta name="description" content="{{ Config()->get('common.description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style type="text/css">
.index-page-container{ width: 350px; min-width: 320px; margin: 0 auto;}
.main-index .index-page-container .content {filter: Alpha(opacity=70);background: rgba(255,255,255, .7);}
.index-footer {padding-top: 150px;}
</style>
</head>
<body class="index-body">
<div class="top">
	<span class="hidden-xs">
		<i class="Hui-iconfont">&#xe62f;</i>
		欢迎进入{{ Config()->get('common.app_name') }}
	</span>
	<ul>
	    <li><a href="{{url('manage/logout')}}">安全退出</a></li>
	</ul>
</div>
<div class="main-index">
	<div class="main-title">
		<span class="hidden-xs">视频监控管理系统</span>
	</div>
	<article class="index-page-container">
		<div class="content">
			@if($data)
			@foreach($data as $key=>$value)
			<div class="module row cl">
				<a href="{{url('spjk/index?status='.$key)}}" target="_blank"><div class="col-xs-12 col-sm-12 font24">{{$value['name']}}</div></a>
			</div>
			@endforeach
			@endif
		</div>
	</article>
	<div class="index-footer">
		<?php echo htmlspecialchars_decode(Config()->get('common.copyright')); ?>
	</div>	
</div>	
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">

</script>

</body>
</html>