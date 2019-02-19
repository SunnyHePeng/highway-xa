@extends('admin.layouts.iframe')

@section('container')

<article class="cl pd-20">
	<form class="form form-horizontal" id="form_container" data-url="{{$url}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>选择用户：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				<span class="select-box">
		          <select class="select" id="user_id" name="user_id" placeholder="请选择用户">
		            <option value="0">请选择</option>
		            @foreach($info as $key=>$val )
		            @if($val['module'])
		            <option value="{{ $val['id'] }}">{{ $val['name']}}/{{$val['roles'][0]['display_name']}}-{{$val['position']}}/{{$val['posi']['name'] }}</option>
		            @endif
		            @endforeach
		          </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>初级：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				<div class="check-box">
					<input type="checkbox" name="cj_0" value="1">
		          	<label for="radio-2">立即通知</label>
		        </div>
				<div class="check-box">
					<input type="checkbox" name="cj_24" value="1">
		          	<label for="radio-2">24小时未处理</label>
		        </div>
		        <div class="check-box">
					<input type="checkbox" name="cj_48" value="1">
		          	<label for="radio-2">48小时未处理</label>
		        </div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>中级：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				<div class="check-box">
					<input type="checkbox" name="zj_0" value="1">
		          	<label for="radio-2">立即通知</label>
		        </div>
				<div class="check-box">
					<input type="checkbox" name="zj_24" value="1">
		          	<label for="radio-2">24小时未处理</label>
		        </div>
		        <div class="check-box">
					<input type="checkbox" name="zj_48" value="1">
		          	<label for="radio-2">48小时未处理</label>
		        </div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>高级：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				<div class="check-box">
					<input type="checkbox" name="gj_0" value="1">
		          	<label for="radio-2">立即通知</label>
		        </div>
				<div class="check-box">
					<input type="checkbox" name="gj_24" value="1">
		          	<label for="radio-2">24小时未处理</label>
		        </div>
		        <div class="check-box">
					<input type="checkbox" name="gj_48" value="1">
		          	<label for="radio-2">48小时未处理</label>
		        </div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="formControls col-xs-8 col-sm-10">
				<button class="btn btn-primary radius" type="submit">确 定</button>
			</div>	
		</div>
		<input type="hidden" name="sec_id" value="{{ $section_id }}">
		<input type="hidden" name="sup_id" value="{{ $supervision_id }}">
		<input type="hidden" name="pro_id" value="{{ $project_id }}">
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