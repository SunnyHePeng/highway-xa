@extends('admin.layouts.tree')

@section('container')
@if($user_is_act)
<div>
	<form id="search_form" data="keyword" method="get" action="{{url('manage/map')}}">
	  	<input type="text" name="keyword" id="keyword" placeholder="请输入名称" class="input-text search-input" value="@if(isset($search['keyword']) && $search['keyword']){{$search['keyword']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  	</form>
</div>
<div class="cl mt-10"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="project" data-title="添加地图标注" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加地图标注</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="120">项目</th>
				<th width="120">名称</th>
				<th width="120">类型</th>
				<th width="120">经纬度</th>
				<th width="100" class="hidden-xs">排序</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['project']['name']}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td class="text-l">{{$type[$value['type']]}}</td>
				<td class="text-l">{{$value['jwd']}}</td>
				<td class="hidden-xs"><input type="text" data-url="{{url('manage/map/'.$value['id'])}}" class="input-text text-c sort-r" value="{{$value['sort']}}"></td>
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="project" data-title="编辑" data-url="{{url('manage/map/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/map/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
	@if($last_page > 1)
	    @include('admin.layouts.page')
	@endif
</div>

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{url('manage/map')}}" id="tree_url">
<input type="hidden" value="section" id="tree_page">
@stop

@section('layer')
@include('admin.project.map_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">

list.init();

</script>
@stop