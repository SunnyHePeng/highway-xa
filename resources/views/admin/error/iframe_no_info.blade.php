@extends('admin.layouts.iframe')

@section('container')
<article class="cl pd-20">
	<div class="f-14 cl pd-10 bg-1 bk-gray mt-20">
		<p class="c-error">{{$info}}</p>
	</div>
</article>
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop