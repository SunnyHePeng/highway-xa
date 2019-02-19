@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">

<div class="text-c">
	<form id="search_form" data="pro_id" method="get" data-url="{{url('manage/dev_type')}}">
	  	设备分类
	  	<span class="select-box inline">
	  		<select name="cat_id" id="cat_id" class="select select2">
                @if(isset($category))
                @foreach($category as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['cat_id']) && $search['cat_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="device_type" data-title="添加设备类型" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加设备类型</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="100">类型名称</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="device_type" data-title="编辑设备类型" data-url="{{url('manage/dev_type/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/dev_type/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@include('admin.device.device_type_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
$('#cat_id').select2().val({{$search['cat_id']}});

list.init();
</script>
@stop