@extends('admin.layouts.iframe')

@section('container')

<article class="cl pd-20">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/user_mod')}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>请选择子系统：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-9">
				@if($list)
				@foreach($list as $info)
				<div class="check-box">
					<input type="checkbox" name="mod_id[]" value="{{$info['id']}}" @if(in_array($info['id'], $mod)) checked @endif>
		          	<label for="radio-2">{{$info['name']}}</label>
		        </div>
		        @endforeach
		        @endif
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="formControls col-xs-8 col-sm-9">
				<button class="btn btn-primary radius" type="submit">确 定</button>
			</div>	
		</div>	
		<input type="hidden" name="u_id" value="{{ $u_id }}">
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