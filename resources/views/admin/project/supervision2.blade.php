@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/zTree/zTreeStyle.css">

@if($user_is_act)
<div class="cl mt-10"> 
	<span class="l act-span" data-url="{{url('manage/supervision')}}" data-set-url="{{url('manage/sup_section')}}">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加监理" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加监理</a>
		<a class="btn btn-secondary radius edit-r" data-for="section" data-title="编辑监理" data-url="{{url('manage/supervision')}}" href="javascript:;"><i class="Hui-iconfont">&#xe6df;</i>编辑监理</a>
		<a class="btn btn-danger radius del-r" href="javascript:;" data-id="" data-url="{{url('manage/supervision')}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i>删除监理</a>
		<a class="open-iframe btn btn-success radius" href="javascript:;" data-title="设置标段" data-url="{{url('manage/sup_section')}}">设置标段</a>
	</span>
</div>
@endif
<div class="row cl mt-10">
	<div class="col-xs-12 col-sm-2">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
	<div class="col-xs-12 col-sm-10">
		<div class="show-sec"></div>
	</div>
</div>
<input type="hidden" value="{{$tree}}" id="tree_data">
<input type="hidden" value="{{url('manage/supervision')}}" id="tree_url">
<input type="hidden" value="{{$info}}" id="tree_info">
<input type="hidden" value="{{$name}}" id="tree_name">
<input type="hidden" value="supervision" id="tree_page">
@stop

@section('layer')
@include('admin.project.supervision_edit')
@stop

@section('script')
<!-- <script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop