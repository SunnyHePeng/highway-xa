@extends('admin.layouts.iframe')

@section('container')

<article class="cl pd-20">
	<form class="form form-horizontal" id="form_container" data-url="{{ url('snbhz/notice/store') }}">
		@foreach($users->sortBy('role')->groupBy('role') as $role)
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>{{ $role->first()->roled->display_name }}</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-10">
				@foreach($role as $user)
				<div class="check-box">
					<input type="checkbox" name="notice[]" value="{{ $user->id }}" {{ $user->mixplantMessageUser ? 'checked' : '' }}>
					<label for="radio-2">{{ $user->posi->name or 'N/A' }} - {{ $user->name }}</label>
				</div>
				@endforeach
			</div>
		</div>
		@endforeach
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="formControls col-xs-8 col-sm-10">
				<button class="btn btn-primary radius" type="submit">确 定</button>
			</div>	
		</div>
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