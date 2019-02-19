@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">

<div class="text-c">
	<form id="search_form" data="pro_id" method="get" data-url="{{url('manage/collection')}}">
	  	建设项目
	  	<span class="select-box inline">
	  		<select name="pro_id" id="pro_id" class="select select2">
                @if(isset($project))
                @foreach($project as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['pro_id']) && $search['pro_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加采集点" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加采集点</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">标段名称</th>
				<th width="80">采集点名称</th>
				<th width="150">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['section']['name']}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="section" data-title="编辑采集点" data-url="{{url('manage/collection/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/collection/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="设置标段" data-url="{{url('manage/collection_cat?col_id='.$value['id'])}}">设备分类</a>
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
@include('admin.device.collection_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
$('#pro_id').select2().val({{$search['pro_id']}});

list.init();
</script>
@stop