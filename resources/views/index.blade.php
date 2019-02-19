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
<link rel="stylesheet" type="text/css" href="/static/admin/css/style.css?v=1" />
<!--[if IE 6]>
<script type="text/javascript" src="/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>{{ Config()->get('common.app_name') }}</title>
<meta name="keywords" content="{{ Config()->get('common.keywords') }}">
<meta name="description" content="{{ Config()->get('common.description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
		<img src="/static/admin/images/temp/logo.png" title="首页" />
	</div>
	<article class="index-page-container">
		<div class="content">
			@if($module)
			@foreach($module as $value)
			<div class="module row cl">
				<div class="col-xs-12 col-sm-2 font24">{{$value['name']}}</div>
				@if($value['child'])
				<div class="col-xs-12 col-sm-8">
					<div class="row cl" data-url="{{url('index')}}">
						@foreach($value['child'] as $v)
						<div class="c-module change-module col-xs-3 col-sm-2" data-id="{{$v['id']}}" data-is-new="{{$v['is_new']}}">
							<div class="icon-img">
								<div @if(!in_array($v['id'], $module_user))class="gray-img"@endif></div>
								<img src="/static/admin/images/icon/{{$v['icon']}}.jpg">
							</div>
							<p @if(!in_array($v['id'], $module_user))class="gray-p"@endif>{{$v['name']}}</p>
						</div>
						@endforeach
					</div>
				</div>
				@endif
			</div>
			@endforeach
			@endif
			<!-- <div class="col-xs-12 col-sm-9" style="margin: 0 auto; float:none;">
				<div class="row cl" data-url="{{url('index')}}">
					<div class="c-module change-module col-xs-12 col-sm-4" data-id="2">
						<div class="">
							<a href="javascript:;">
								<i class="Hui-iconfont">&#xe61d;</i>
								<p>系统设置</p>
							</a>
						</div>
					</div>
					<div class="c-module change-module col-xs-12 col-sm-4" data-id="4">
						<div class="">
							<a href="javascript:;">
								<i class="Hui-iconfont">&#xe64d;</i>
								<p>水泥拌合站</p>
							</a>
						</div>
					</div>
					<div class="c-module change-module col-xs-12 col-sm-4" data-id="3">
						<div class="">
							<a href="javascript:;">
								<i class="Hui-iconfont">&#xe63c;</i>
								<p>试验设备</p>
							</a>
						</div>
					</div>
				</div>
			</div> -->
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