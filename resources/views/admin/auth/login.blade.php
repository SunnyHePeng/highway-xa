@extends('admin.layouts.login')

@section('content')
	<div class="page">
		<div class="login-logo"><img src="/static/admin/images/temp/logo.png" title="登录页面"></div>
		<div class="login-form">
			<div class="login-header"></div>
			<form class="form form-horizontal" method="post" action="{{ url('manage/login') }}">
				<div class="row cl login-div">
			      <label class="form-label col-xs-2 col-sm-2"><i class="Hui-iconfont">&#xe60d;</i></label>
			      <div class="formControls col-xs-10 col-sm-10">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入账号" id="username" name="username">
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-2 col-sm-2"><i class="Hui-iconfont">&#xe60e;</i></label>
			      <div class="formControls col-xs-10 col-sm-10">
			        <input type="password" class="input-text msg_must" value="" placeholder="请输入密码" id="password" name="password">
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-2 col-sm-2"></label>
			      <div class="formControls col-xs-5 col-sm-6" style="padding-right: 0;">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入验证码" id="verify" name="verify">
			      </div>
			      <div class="formControls col-xs-5 col-sm-4" style="padding-left: 0;">
			      	<span class="yzm"><img id="verify" src="{{ url('code/'.time()) }}" onclick="this.src='{{url('code/')}}?rand='+Math.random();"></span>
			      </div>
			    </div>
			    <!-- <div class="row cl">
			      <label class="form-label col-xs-2 col-sm-2"><i class="Hui-iconfont">&#xe60e;</i></label>
			      <div class="formControls col-xs-10 col-sm-10 skin-minimal">
			      	<div class="check-box">
			      		<input type="checkbox" name="remember" id="checkbox-1"/>
			        	<label for="checkbox-1">记住我</label>
			      	</div>
			      </div>
			    </div> -->
			    
				<br><br>
				<div>
				<button style="" type="submit" class="btn btn-secondary login-button">
				  登&emsp;录
				</button>
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="row cl login-div">
			      <div class="text-r col-xs-12 col-sm-12">
			      	<a href="{{url('manage/verifyinfo')}}"><i class="Hui-iconfont">&#xe63f;</i>&nbsp;找回密码</a>&emsp;&emsp;
			      	<a href="{{url('manage/register')}}"><i class="Hui-iconfont">&#xe60d;</i>&nbsp;注册新账号</a>
			      </div>
			    </div>
			</form>
		</div>
	</div>
	<div class="login-footer">
		<?php echo htmlspecialchars_decode(Config()->get('common.copyright')); ?>
	</div>
	
@stop

@section('script')
<script type="text/javascript">

$(function(){
	$('#verify').attr('src', "{{url('code/')}}" + "/" + Math.random());
});
</script>
@stop