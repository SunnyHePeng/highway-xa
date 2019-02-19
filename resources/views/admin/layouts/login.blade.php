<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	
	<title>{{ Config()->get('common.app_name') }}</title>
	<link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
	<link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" href="/static/admin/css/style.css">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<style type="text/css">
	.index-body .top a, .login .top a {
	    color: #fff;
	}
	</style>
</head>
<body class="login">
	<div class="top">
		<a href="{{url('index')}}"><span><i class="Hui-iconfont"></i> 欢迎进入{{ Config()->get('common.app_name') }}</span></a>
	</div>
	<!-- <div class="bg"></div> -->
	@yield('content')

    <script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script>
	@yield('script')

</body>
</html>