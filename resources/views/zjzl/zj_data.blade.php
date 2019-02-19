@extends('admin.layouts.tree')

@section('container')

<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="40">状态</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">监理名称</th>
				<th width="100">标段名称</th>
				<th width="100">设备名称</th>
				<th width="100">设备型号</th>
				<th width="100">设备编码</th>
				<th width="100">最新上报时间</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c">
				<?php 
					$status_info = Cache::get('device_status_'.$value['id']);
					if($status_info && $status_info['J1'] == 1){
						$is_online = 1;
						$status = '在线';
					}else{
						$is_online = 0;
						$status = '离线';
					}
				?>
				<td class="hidden-xs hidden-sm">{{$page_num++}}</td>
				<td><span class="label @if($is_online == 1) label-success @else label-danger @endif radius">{{$status}}</span></td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['sup']['name']}}</td>
				<td>{{$value['section']['name']}}</td>
				<td>{{$value['name']}}</td>
				<td>{{$value['model']}}</td>
				<td>{{$value['dcode']}}</td>
				<td>@if($value['last_time']){{date('Y-m-d H:i:s', $value['last_time'])}}@endif</td>
				<td class="f-14 product-brand-manage td-manage"> 
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="{{$value['project']['name']}}-{{$value['name']}} 桩基数据" data-url="{{url('zjzl/zj_data_info/'.$value['id'].'?pro_id='.$value['project_id'].'&cat_id='.$value['cat_id'])}}">桩基数据</a>
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