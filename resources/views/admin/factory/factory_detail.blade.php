@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">

<div class="text-c">
	<form id="search_form" data="pro_id" method="get" data-url="{{url('manage/factory_detail')}}">
	  	厂家名称
	  	<span class="select-box inline">
	  		<select name="fac_id" id="fac_id" class="select select2">
                @if(isset($factory))
                @foreach($factory as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['fac_id']) && $search['fac_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
    </form>
</div>
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="factory" data-title="添加厂家明细" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加厂家明细</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">				
				<th width="40">序号</th>
				<th width="80">材料名称</th>
				<th width="80">顺序号</th>
				<th width="80" class="hidden-xs hidden-sm">材料位置行</th>
				<th width="80" class="hidden-xs hidden-sm">材料位置列</th>
				<th width="80" class="hidden-xs hidden-sm">实际值采集计算</th>
				<th width="80" class="hidden-xs hidden-sm">实际值位置行</th>
				<th width="80" class="hidden-xs hidden-sm">实际值位置列</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">设计值采集计算</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">设计值位置行</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">设计值位置列</th>
				<th width="120">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['material']['name']}}</td>
				<td>{{$value['order_num']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['cl_position_row']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['cl_position_col']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['fact_z_cjjs']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['fact_z_position_row']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['fact_z_position_col']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['design_z_cjjs']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['design_z_position_row']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['design_z_position_col']}}</td>
				<td class="f-14 td-manage">
					@if($user_is_act)
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="factory" data-title="编辑厂家明细" data-url="{{url('manage/factory_detail/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/factory_detail/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					@endif
					<a style="text-decoration:none" class="mt-5 ml-5 get-info btn btn-success radius size-MINI" href="javascript:;" data-info="factory_detail" data-url="{{url('manage/factory_detail/'.$value['id'])}}" title="详情">详情</a>
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
@include('admin.factory.factory_detail_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
$('#fac_id').select2().val({{$search['fac_id']}});

/*$('#material_id').select2();*/

list.init();
</script>
@stop