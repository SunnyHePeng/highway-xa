@extends('admin.layouts.iframe')

@section('container')
<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('lab/deal')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>意见：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<textarea class="textarea" placeholder="输入处理意见" id="info" name="info"></textarea>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>
@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
@stop