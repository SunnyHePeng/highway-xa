<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>登录地址提示</h2>

		<div>
			<p>ID为 {{ $did }} 的设备 {{ date('Y-m-d H:i:s',$timestamp) }} 在<strong> {{ $ip_addr }} </strong>登录，上次登录地址 {{ $pre_ip_addr }}</p>
			<p>请确定是本人操作，若不是,请您注意安全，及时修改密码</p>
			<p>----------------------------------------------------------------------
			<br>
			<p>此致<br>
			{{ Config()->get('common.app_name') }} 团队.</p>
		</div>
	</body>
</html>