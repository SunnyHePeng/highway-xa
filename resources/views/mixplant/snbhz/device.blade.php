@extends('admin.layouts.tree')

@section('container')
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="100">监理名称</th>
				<th width="100">标段名称</th>
				<th width="100">设备名称</th>
				<th width="100">设备型号</th>
				<th width="100">设备编码</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">生产厂家</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">出厂日期</th>
				<th width="100" class="hidden-xs hidden-sm">负责人</th>
				<th width="100" class="hidden-xs hidden-sm">联系方式</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c">
				<td class="hidden-xs hidden-sm">{{$page_num++}}</td>
				<td>{{$value['sup']['name']}}</td>
				<td>{{$value['section']['name']}}</td>
				<td>{{$value['name']}}</td>
				<td>{{$value['model']}}</td>
				<td>{{$value['dcode']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory_name']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory_date']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
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
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
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