@extends('admin.layouts.login')

@section('content')
<style type="text/css">
#passstr_div {display: none;}
#passStrength{height:2px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
.strengthLv0{background:red;height:2px;width:10%;}
.strengthLv1{background:red;height:2px;width:40%;}
.strengthLv2{background:orange;height:2px;width:70%;}
.strengthLv3{background:green;height:2px;width:100%;}
</style>
	<div class="login-form">
		<div class="login-header"><img src="/admin/images/logo.png"></div>
		<form method="post" action="{{ url('manage/pass') }}" id="passform">
			<div>
				<div class="mdl-textfield mdl-js-textfield textfield-demo login-field">
			    	<input class="mdl-textfield__input login_input" name="password" type="password" id="password" />
			    	<label class="mdl-textfield__label login_lable" for="password">Password</label>
			  	</div>
		  	</div>
		  	<div id="passstr_div">
		      <span>密码强度</span>
		      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field">
		          <div id="passStrength"></div>
		      </div>
		    </div>
		  	<div>
				<div class="mdl-textfield mdl-js-textfield textfield-demo login-field">
				    <input class="mdl-textfield__input login_input" name="repassword" type="password" id="repassword" />
				    <label class="mdl-textfield__label login_lable" for="repassword">Confirm Password</label>
				</div>
			</div>
			<span>*请先修改密码</span>
			<br><br><br>
			<span style="width:200px;margin-left:100px;font-weight:600;" onclick="checkpass();" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
			  提&emsp;交
			</span>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
	</div>
	<div class="login-footer">
		<?php echo htmlspecialchars_decode(Config()->get('common.copyright')); ?>
	</div>
	<script type="text/javascript" src="/admin/js/password.js"></script>
	<script type="text/javascript">
		new PasswordStrength('password','passStrength');
		function checkpass(){
			if(!$('#password').val()){
				alert('请输入密码');
				return false;
			}
			if(!$('#repassword').val()){
				alert('请输入确认密码');
				return false;
			}
			if($('#password').val() != $('#repassword').val()){
				alert('密码和确认密码不一致');
				return false;
			}
			$('#passform').submit();
		}
	</script>
@stop
