@extends('admin.layouts.default')

@section('container')
<div class="text-c">
	<form id="search_form" data="keyword" method="get" action="{{url('manage/factory')}}">
	  	<input type="text" name="keyword" id="keyword" placeholder="请输入厂家名称" class="input-text search-input" value="@if(isset($search['keyword']) && $search['keyword']){{$search['keyword']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  	</form>
</div>
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="mixplant" data-title="添加厂家" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加厂家</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">厂家名称</th>
				<th width="80">总行数</th>
				<th width="80" class="hidden-xs hidden-sm">分析类型</th>
				<th width="80" class="hidden-xs hidden-sm">日期位置行</th>							
				<th width="80" class="hidden-xs hidden-sm">时间位置行</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">配比编号位置行</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">实际油石比位置行</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">混合料温度位置行</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">产量位置行</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td>{{$value['data_row']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['data_analyse_type']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['date_position_row']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['time_position_row']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['pb_number_position_row']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['fact_ysb_position_row']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['hhlwd_position_row']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['cl_position_row']}}</td>
				<td class="f-14 td-manage">
					@if($user_is_act)
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="mixplant" data-title="编辑厂家" data-url="{{url('manage/factory/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/factory/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					@endif
					<a style="text-decoration:none" class="mt-5 ml-5 get-info btn btn-success radius size-MINI" href="javascript:;" data-info="factory" data-url="{{url('manage/factory/'.$value['id'])}}" title="详情">详情</a>
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
@include('admin.factory.factory_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop