@extends('admin.layouts.login')

@section('content')
<style type="text/css">
.login-form .login-div label {
    font-size: 14px;
    line-height: 38px;
}
.login .page .login-form {
    height: 310px;
}
</style>
	<div class="page">
		<div class="login-form">
			<div class="login-header"></div>
			<form class="form form-horizontal" id="form_container" data-url="{{ url('manage/changepass') }}">
				<div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3">用户名:</label>
			      <div class="formControls col-xs-9 col-sm-9" style="line-height:38px;">
			        {{$username}}
			      </div>
			    </div>
				<div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3">手机号码:</label>
			      <div class="formControls col-xs-9 col-sm-9" style="line-height:38px;">
			        {{$phone}}
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3"><i class="Hui-iconfont">新密码:</i></label>
			      <div class="formControls col-xs-9 col-sm-9">
			        <input type="password" class="input-text msg_must" value="" placeholder="请输入新密码" id="password" name="password">
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3"><i class="Hui-iconfont">确认密码:</i></label>
			      <div class="formControls col-xs-9 col-sm-9">
			        <input type="password" class="input-text msg_must" value="" placeholder="请输入确认密码" id="repassword" name="repassword">
			      </div>
			    </div>
				<br><br>
				<div>
				<input class="btn btn-secondary login-button" value="提&emsp;交">
				</div>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
		</div>
	</div>
	<div class="login-footer">
		<?php echo htmlspecialchars_decode(Config()->get('common.copyright')); ?>
	</div>
	
@stop

@section('script')
<script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/common/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/js/H-ui.admin.page.js"></script> 
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript">

$("#form_container .login-button").on('click', function(){
	var data = $("#form_container").serialize();
	common.doAjax($('#form_container'), 'POST');
});

</script>
@stop