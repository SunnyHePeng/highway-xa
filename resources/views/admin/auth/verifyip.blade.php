@extends('admin.layouts.login')

@section('content')
<style type="text/css">
.login-form .login-div label {
    font-size: 14px;
    line-height: 38px;
}
.login .page .login-form {
    height: 230px;
}
</style>
	<div class="page">
		<div class="login-form">
			<div class="login-header"></div>
			<form class="form form-horizontal" id="form_container" data-url="{{ url('manage/verifyip') }}">
				<div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3">手机号码:</label>
			      <div class="formControls col-xs-9 col-sm-9" style="line-height:38px;">
			        {{$phone}}
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3">验证码:</label>
			      <div class="formControls col-xs-5 col-sm-6" style="padding-right: 0;">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入短信验证码" id="verify" name="verify">
			      </div>
			      <div class="formControls col-xs-4 col-sm-3" style="padding-left: 0;">
			      	<span class="phone_yzm" data-url="{{url('pcode/'.time().'?type=verifyip')}}">点击获取</span>
			      </div>
			    </div>
			    <div class="row cl login-div">
			      <label class="form-label col-xs-3 col-sm-3"></label>
			      <div class="formControls col-xs-9 col-sm-9 red-line">
			        由于登录IP与上次不一致，请先验证手机号
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
findpass.init();

$("#form_container .login-button").on('click', function(){
	var data = $("#form_container").serialize();
	common.doAjax($('#form_container'), 'POST');
});

</script>
@stop