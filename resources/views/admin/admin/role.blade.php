@extends('admin.layouts.default')

@section('container')

<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="role" data-title="添加角色" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加角色</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="100">关键字(唯一)</th>
				<th width="100">角色名称(显示用)</th>
				<th width="100" class="hidden-xs">角色描述</th>
				<th width="100" class="hidden-xs">创建时间</th>
				<th width="100" class="hidden-xs">更新时间</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td>{{$value['name']}}</td>
				<td>{{$value['display_name']}}</td>
				<td class="hidden-xs">{{$value['description']}}</td>
				<td class="hidden-xs">{{date('Y-m-d H:i', $value['created_at'])}}</td>
				<td class="hidden-xs">{{date('Y-m-d H:i', $value['updated_at'])}}</td>
				<td class="f-14 product-brand-manage">
					<a style="text-decoration:none" data-for="role" data-title="编辑角色" data-url="{{url('manage/role/'.$value['id'].'/edit')}}" class="edit-r btn btn-secondary radius size-MINI" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					@if($value['id'] > 6 )
					<a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/role/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					@endif
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
@stop

@section('layer')
@include('admin.admin.role_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();

list.checkPermission();
</script>
@stop