@extends('admin.layouts.login')

@section('content')
<style type="text/css">
.login .page .login-form {
    height: 150px;
}
</style>
	<div class="page">
		<div class="login-form">
			<div class="login-header"></div>
			<form class="form form-horizontal" id="form_container" data-url="{{ url('manage/verifyinfo') }}">
				<div class="row cl login-div">
			      <label class="form-label col-xs-2 col-sm-2"><i class="Hui-iconfont">&#xe60d;</i></label>
			      <div class="formControls col-xs-10 col-sm-10">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入账号" id="username" name="username">
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