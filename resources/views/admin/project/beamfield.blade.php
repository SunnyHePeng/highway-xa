@extends('admin.layouts.iframe')

@section('container')
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20">
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="project" data-title="添加梁场" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加梁场</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="50">序号</th>
				<th width="100">梁场名称</th>
				<th width="100">台座数量</th>
				<th width="100" class="hidden-xs">状态</th>
				@if($user_is_act)
				<th width="100">操作</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td>{{$value['tz_num']}}</td>
				<td class="hidden-xs">{{$value['status']}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="project" data-title="编辑梁场" data-url="{{url('manage/beamfield/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/beamfield/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
				@endif
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
@include('admin.project.beamfield_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop