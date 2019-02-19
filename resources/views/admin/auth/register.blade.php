@extends('admin.layouts.login')

@section('content')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">
<style type="text/css">
body.login {overflow: auto;}
.register-page { margin-top: 60px;}
.login-header{font-size: 24px; text-align: center;}
.login .register-page .login-form {
    margin: 0 auto;
    width: 400px;
    /*height: 810px;*/
    max-width: 80%;
    background-color: #fff;
    padding: 20px 50px;
}
.check-box{min-width: 110px;}
#passstr_div {display: none;}
#passStrength{height:8px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
.strengthLv0{background:red;height:8px;width:10%;}
.strengthLv1{background:red;height:8px;width:40%;}
.strengthLv2{background:orange;height:8px;width:70%;}
.strengthLv3{background:green;height:8px;width:100%;}
#cp_div {display: none;}
.login-footer {
	position: relative;
	margin-top: 50px;
}
.select2-container--default .select2-selection--multiple {border: 1px solid #ddd; border-radius: 0;}
.select2-container--default.select2-container--focus .select2-selection--multiple {border: solid 1px #3bb4f2;}
</style>
	<div class="register-page">
		<div class="login-form">
			<div class="login-header">注册用户</div>
			<form class="form form-horizontal" id="form_container" data-url="{{url('manage/register')}}">
			    <div class="row cl show-all hidden-pass">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>角色：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <span class="select-box">
			          <select class="select msg_must role_select" id="role" name="role" placeholder="请选择角色" data-url="{{url('manage/get_pos')}}">
			            <option value="0">请选择</option>
			            @foreach($roles as $key=>$val )
			            <option value="{{ $val['id'] }}">{{ $val['display_name'] }}</option>
			            @endforeach
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      	<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>建设项目：</label>
				    <div class="formControls col-xs-8 col-sm-8">
				        <!-- <span class="select-box"> -->
				          <select class="select msg_must select2" id="project_id" name="project_id[]" placeholder="请先选择建设项目" data-url="{{url('manage/get_sup')}}" multiple="multiple">
				            <option value="0">请选择项目</option>
				            @if(isset($project))
				            @foreach($project as $key=>$val )
				            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
				            @endforeach
				            @endif
				          </select>
				        <!-- </span> -->
				    </div>
			    </div>
			    <div class="row cl show-all hidden-jtyh">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>单位名称：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <span class="select-box">
			          <select class="select company_select" id="company_id" name="company_id" placeholder="请先选择单位名称">
			            <option value="0">请选择单位名称</option>
			            @if(isset($company))
			            @foreach($company as $key=>$val )
			            <option value="{{ $val['id'] }}" class="company_option">{{ $val['name'] }}</option>
			            @endforeach
			            @endif
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>部门名称：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			       {{-- <input type="text" class="input-text msg_must" value="" placeholder="请输入部门名称" id="position" name="position" required>--}}
					  <span class="select-box">
			          <select class="select department_select" id="department_id" name="department_id" placeholder="请先选择部门名称">
			            <option value="0">请选择部门名称</option>
						  @if(isset($department))
							  @foreach($department as $key=>$val )
								  <option value="{{ $val['id'] }}" class="depart_option">{{ $val['name'] }}</option>
							  @endforeach
						  @endif
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属职位：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <span class="select-box">
			          <select class="select msg_must" id="position_id" name="position_id" placeholder="请先选择所属职位">
			            <option value="0">请选择职位</option>
			            @if(isset($position))
			            @foreach($position as $key=>$val )
			            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
			            @endforeach
			            @endif
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>姓名：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入姓名" id="name" name="name" required>
			      </div>
			    </div>
			    <div class="row cl show-all hidden-pass">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入联系方式" id="phone" name="phone" required>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>登录账号：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入登录账号" id="username" name="username" required>
			      </div>
			    </div>
			    <div id="pass" class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>密码：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <input type="password" class="input-text msg_must" value="" placeholder="请输入密码" placeholder="" id="password" name="password" required>
			      </div>
			    </div>
			    <div id="passstr_div" class="row cl">
			      <label class="form-label col-xs-3 col-sm-3">密码强度：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <div id="passStrength"></div>
			      </div>
			    </div>
			    <div class="row cl show-all hidden-pass hidden-xtgly">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理信息：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <span class="select-box">
			          <select class="select" id="supervision_id" name="supervision_id" placeholder="请选择监理信息" data-url="{{url('manage/get_sec')}}">
			            <option value="0">请选择</option>
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all hidden-pass hidden-xtgly hidden-zjb">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段：</label>
			      <div class="formControls col-xs-8 col-sm-8">
			        <span class="select-box">
			          <select class="select" id="section_id" name="section_id" placeholder="请选择标段">
			            <option value="0">请选择</option>
			          </select>
			        </span>
			      </div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>子系统：</label>
			      <div class="formControls skin-minimal col-xs-8 col-sm-9">
						@if($module)
						@foreach($module as $info)
						<div class="check-box">
							<input type="checkbox" name="mod_id[]" value="{{$info['id']}}">
				          	<label for="radio-2">{{$info['name']}}</label>
				        </div>
				        @endforeach
				        @endif
					</div>
			    </div>
			    <div class="row cl show-all">
			      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>短信验证码</label>
			      <div class="formControls col-xs-5 col-sm-5" style="padding-right: 0;">
			        <input type="text" class="input-text msg_must" value="" placeholder="请输入短信验证码" id="verify" name="verify">
			      </div>
			      <div class="formControls col-xs-3 col-sm-3" style="padding-left: 0;">
			      	<span class="phone_yzm" data-url="{{url('pcode/'.time())}}">点击获取</span>
			      </div>
			    </div>
			    <input type="hidden" value="" id="id" name="id">
			    <input type="hidden" value="" id="act_type" name="act_type">
			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
			    <br><br>
				<input class="btn btn-secondary login-button" value="注&emsp;册">
				<div class="row cl login-div">
			      <div class="text-r col-xs-11 col-sm-11"><a href="{{url('manage/login')}}"><i class="Hui-iconfont">&#xe60d;</i>&nbsp;账号密码登录</a></div>
			    </div>
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
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/password.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript">
$('#project_id').select2();

list.init();

$('.skin-minimal input').iCheck({
  checkboxClass: 'icheckbox-blue',
  radioClass: 'iradio-blue',
  increaseArea: '20%'
});
new PasswordStrength('password','passStrength');

register.init();

$("#form_container .login-button").on('click', function(){
	var data = $("#form_container").serialize();
	common.doAjax($('#form_container'), 'POST');
});

</script>
@stop