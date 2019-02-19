@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">

<div class="text-c">
	<form id="search_form" data="pro_id,sec_id,num" method="get" data-url="{{url('manage/truck')}}">
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
        <span class="select-box" style="width:auto;">
	        <select name="sec_id" id="sec_id" class="select">
              <option value="">标段</option>
              @if(isset($section))
              @foreach($section as $k=>$v)
              <option value="{{$v['id']}}" @if(isset($search['sec_id']) && $search['sec_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
              @endforeach
              @endif
	        </select>
	    </span>
	    <input type="text" name="num" id="num" placeholder="请输入车牌号" class="input-text search-input" value="@if(isset($search['num']) && $search['num']){{$search['num']}}@endif">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加车辆" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加车辆</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<!-- <th width="80">驻地办</th> -->
				<th width="80">标段名称</th>
				<th width="80">分组编号</th>
				<th width="80">车牌号</th>
				<th width="80" class="hidden-xs hidden-sm">车辆类型</th>
				<th width="80" class="hidden-xs hidden-sm">所属单位</th>
				<th width="80" class="hidden-xs hidden-sm">司机名称</th>														
				<th width="80" class="hidden-xs hidden-sm">联系电话</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">模拟1</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">模拟2</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">模拟3</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">模拟4</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">出厂编号</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">登记时间</th>
				@if($user_is_act)
				<th width="80">操作</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<!-- <td>{{$page_num++}}</td> -->
				<td class="text-l">{{$value['section']['name']}}</td>
				<td>{{$value['group_code']}}</td>
				<td>{{$value['truck_num']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['category']['name']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['unit_name']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['driver_name']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">@if($value['by1_select']){{$select[$value['by1_select']]}}@endif</td>
				<td class="hidden-xs hidden-sm hidden-md">@if($value['by2_select']){{$select[$value['by2_select']]}}@endif</td>
				<td class="hidden-xs hidden-sm hidden-md">@if($value['by3_select']){{$select[$value['by3_select']]}}@endif</td>
				<td class="hidden-xs hidden-sm hidden-md">@if($value['by4_select']){{$select[$value['by4_select']]}}@endif</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory_code']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{date('Y-m-d', $value['created_at'])}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="section" data-title="编辑车辆" data-url="{{url('manage/truck/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/truck/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@include('admin.device.truck_edit')
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