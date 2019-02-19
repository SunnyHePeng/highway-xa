@extends('admin.layouts.tree')

@section('container')

<article class="cl pd-20">
	<div class="f-14 cl pd-10 bg-1 bk-gray mt-20">
		<p class="c-error">{{$info}}</p>
	</div>
</article>

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop