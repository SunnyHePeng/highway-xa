@extends('admin.layouts.iframe')

@section('container')
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20">
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="mixplant" data-title="添加拌合站" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加拌合站</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">拌合站名称</th>
				<th width="80" class="hidden-xs hidden-sm">数据库类型</th>
				<th width="80" class="hidden-xs hidden-sm">采集状态</th>
				<th width="80">生产能力</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">生产厂家</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">公称容量</th>
				<th width="80" class="hidden-xs hidden-sm">负责人</th>
				<th width="80" class="hidden-xs hidden-sm">联系方式</th>
				<th width="80" class="hidden-xs hidden-sm">登记时间</th>
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
				<td class="hidden-xs hidden-sm">{{$value['database_type']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['status']}}</td>
				<td>{{$value['product_rate']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['capacity']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
				<td class="hidden-xs hidden-sm">{{date('Y-m-d', $value['created_at'])}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="mixplant" data-title="编辑拌合站" data-url="{{url('manage/mixplant/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/mixplant/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@include('admin.project.mixplant_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop