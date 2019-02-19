@extends('admin.layouts.default')

@section('container')
	<div class="text-l cl pd-5 bg-1 bk-gray mt-20">
		<form id="search_form" data="finish,beam_num" method="get" data-url="{{url('lab/lab_data_info')}}">
			是否完成
			<span class="select-box" style="width:auto; padding: 3px 5px;">
        <select name="finish" id="finish" class="select select2">
             <option value="0" @if($search['finish'] == 0) selected="selected"@endif>请选择</option>
             <option value="1" @if($search['finish'] == 1) selected="selected"@endif>未完成</option>
             <option value="2" @if($search['finish'] == 2) selected="selected"@endif>完成</option>
            </select>
        </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			梁号：
			<input type="text" class="input-text" style="width: 10%;" name="beam_num" id="beam_num" value="{{$search['beam_num']}}">
			@if($search)
				<input type="hidden" name="pro_id" id="pro_id" value="{{$search['pro_id']}}">
				<input type="hidden" name="sup_id" id="sup_id" value="{{$search['sup_id']}}">
				<input type="hidden" name="sec_id" id="sec_id" value="{{$search['sec_id']}}">
			@endif
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
		</form>
	</div>
	<div class="mt-10 dataTables_wrapper">
		<table id="table_list" class="table table-border table-bordered table-bg table-sort">
			<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="100">项目公司</th>
				<th width="100">监理单位</th>
				<th width="100">合同段</th>
				<th width="100">梁场</th>
				<th width="100">工程名称</th>
				<th width="100">工程部位</th>
				<th width="100">墩身编号</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">养生开始时间</th>
				<th width="100" class="hidden-xs hidden-sm hidden-md">养生结束时间</th>
				<th width="100" class="hidden-xs hidden-sm">养生周期(天)</th>
				<th width="100" class="hidden-xs hidden-sm">是否完成</th>
				<th width="80">操作</th>
			</tr>
			</thead>
			<tbody>
			@if($data)
				@foreach($data as $v)
					<tr class="text-c">
						<td>{{$from++}}</td>
						<td>{{$v['project']['name']}}</td>
						<td>{{$v['sup']['name']}}</td>
						<td>{{$v['section']['name']}}</td>
						<td>{{$v['beam_site']['name']}}</td>
						<td>{{$v['project_name']}}</td>
						<td>{{$v['project_place']}}</td>
						<td>{{$v['beam_num']}}</td>
						<td>{{$v['start_time']}}</td>
						<td>{{$v['end_time']}}</td>
						<td>{{$v['days_spend']}}</td>
						<td>
							@if($v['is_finish']==1)
								完成
							@else
								未完成
							@endif
							{{--                              {{$v['is_finish']}}--}}
						</td>
						<td>
							<input class="btn btn-primary radius open-detail" type="button" data-title="喷淋记录" data-url="{{url('beam_spray/spray_detail').'/'.$v['id']}}" value="喷淋记录">
						</td>
					</tr>
				@endforeach
			@endif
			</tbody>
		</table>
		@if($last_page>1)
			@include('admin.layouts.page')
		@endif
	</div>
@stop

@section('script')
	<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
	<script type="text/javascript" src="/static/admin/js/common.js"></script>
	<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
	<script type="text/javascript">
        list.init();
        $(".open-detail").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.4,
                area: ['90%', '90%'],
                content: url
            });
        });
	</script>
@stop