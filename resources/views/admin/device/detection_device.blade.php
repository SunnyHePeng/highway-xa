@extends('admin.layouts.default')

@section('container')

<div class="text-c">
	<form id="search_form" data="keyword" method="get" action="{{url('manage/detection_device')}}">
	  	<input type="text" name="keyword" id="keyword" placeholder="请输入设备名称" class="input-text search-input" value="@if(isset($search['keyword']) && $search['keyword']){{$search['keyword']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  	</form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="mixplant" data-title="添加检测设备" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加检测设备</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="100">设备名称</th>
				<th width="100">设备类型</th>
				<th width="100">设备厂家</th>
				<th width="100" class="hidden-xs">负责人</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">联系方式</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td>{{$value['type']}}</td>
				<td>{{$value['factory_name']}}</td>
				<td class="hidden-xs">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['phone']}}</td>
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="mixplant" data-title="编辑检测设备" data-url="{{url('manage/detection_device/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/detection_device/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@include('admin.device.detection_device_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop