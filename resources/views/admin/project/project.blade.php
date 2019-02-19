@extends('admin.layouts.default')

@section('container')
@if($user_is_act)
<div>
	<form id="search_form" data="keyword" method="get" action="{{url('manage/project')}}">
	  	<input type="text" name="keyword" id="keyword" placeholder="请输入项目名称" class="input-text search-input" value="@if(isset($search['keyword']) && $search['keyword']){{$search['keyword']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  	</form>
</div>
<div class="cl mt-10"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="project" data-title="添加项目" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加项目</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="120">项目名称</th>
				<th width="100">总里程</th>
				<th width="100" class="hidden-xs">监理数量</th>
				<th width="100">标段数量</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">项目概况</th>
				<th width="100" class="hidden-xs hidden-sm">添加时间</th>
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
				<td>{{$value['mileage']}}</td>
				<td class="hidden-xs">{{$value['supervision_num']}}</td>
				<td>{{$value['section_num']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['summary']}}</td>
				<td class="hidden-xs hidden-sm">{{date('Y-m-d H:i', $value['created_at'])}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="project" data-title="编辑项目" data-url="{{url('manage/project/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/project/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@include('admin.project.project_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop