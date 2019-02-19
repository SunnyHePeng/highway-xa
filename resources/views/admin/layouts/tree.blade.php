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
<link rel="stylesheet" href="/lib/zTree/zTreeStyle.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/style.css" />
<link rel="stylesheet" type="text/css" href="/lib/fancybox/fancybox.css" /> 
<!--[if IE 6]>
<script type="text/javascript" src="/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>{{ Config()->get('common.app_name') }}</title>
<meta name="keywords" content="{{ Config()->get('common.keywords') }}">
<meta name="description" content="{{ Config()->get('common.description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
@include('admin.layouts._header')

@include('admin.layouts._leftmenu')



<section class="Hui-article-box">
	{{--
	<div class="tree-page row cl">
      <div class="tree col-sm-2 col-xs-12">
  		 @include('admin.layouts._ztree')
 	</div>
	<div class="show-page-info col-sm-10 col-xs-12">
	--}}
  		<nav class="breadcrumb">
		  	<strong>位置：</strong> <a href="{{url($user['module_url'])}}">{{$user['module_name']}}</a> 
		  	<span class="c-999 en">&gt;</span><span class="c-666">@if($active){{$active['name']}}@endif</span>
			<a class="btn btn-success radius r hidden-xs" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
			@if($user['module_name'] == '试验室')
		    <a class="btn btn-success radius r mr-10 hidden-xs hidden-sm" style="line-height:1.6em;margin-top:3px" href="{{url('lab/olab')}}" target="_blank" title="进入试验室管理系统">进入试验室管理系统</a>
		    @endif
		</nav>
	  	<div class="Hui-article">
		    <article class="cl pd-20">
		      @yield('container')
		    </article>
	  	</div>
	{{--
  	</div>
  </div>
  --}}
</section>

@yield('layer')

@yield('layer2')

@include('admin.layouts._footer')

@yield('script')
</body>
</html>