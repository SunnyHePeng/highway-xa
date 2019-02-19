@extends('admin.layouts.iframe')

@section('container')

<article class="cl pd-20">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/user_info')}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">项目名称：</label>
			<div class="formControls col-xs-8 col-sm-10">
			@if($project)
				@foreach($project as $k=>$v)
				@if($k!=0) , @endif {{$v['name']}}
				@endforeach
			@endif
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">单位名称：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$company['name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">部门名称：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$department['name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">职位名称：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$posi['name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">姓名：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$name}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">联系方式：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$phone}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">账号：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$username}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">角色：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$roles[0]['display_name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">监理名称：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$supervision['name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">标段名称：</label>
			<div class="formControls col-xs-8 col-sm-10">{{$section['name']}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">注册时间：</label>
			<div class="formControls col-xs-8 col-sm-10">{{date('Y-m-d H:i', $created_at)}}</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">子系统：</label>
			<div class="formControls col-xs-8 col-sm-10">
				@if($module)
				@foreach($module as $k=>$value)
					@if($k != 0)，@endif {{$value['name']}}
				@endforeach
				@endif
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>状态：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				<div class="check-box">
					<input type="radio" name="status" value="1" @if($status==1) checked @endif>
		          	<label for="radio-2">通过审核</label>
		        </div>
		        <div class="check-box">
					<input type="radio" name="status" value="0" @if($status==0) checked @endif>
		          	<label for="radio-2">未通过审核</label>
		        </div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="formControls col-xs-8 col-sm-10">
				<button class="btn btn-primary radius" type="submit">确 定</button>
			</div>	
		</div>
		<input type="hidden" name="u_id" value="{{ $id }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>
@stop

@section('script')
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});

    $("#form_container").validate({

        submitHandler: function(form) {
            common.doAjax($('#form_container'), 'POST');
        },

        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
     });
});
</script>
@stop