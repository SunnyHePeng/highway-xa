@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div>
  <form id="search_form" data="sec_id,start_date,end_date" method="get" data-url="{{url('snbhz/warn_report')}}">
        时间：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
    	<input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>  	
  </form>
</div>

<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs"> 
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="报警统计" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="报警统计" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
</div>

<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="100" class="hidden-xs">管理单位</th>
				<th width="100" class="hidden-xs">标段名称</th>
				<th width="100">设备编码</th>
				<th width="100">报警次数</th>
				<th width="150">报警时间</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c">
				<td>{{$page_num++}}</td>
				<td class="hidden-xs hidden-sm">{{$value['sup']['name']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['section']['name']}}</td>
				<td>{{$value['device']['dcode']}}</td>
				<td>{{$value['bj_num']}}</td>
				<td>{{$value['date']}}</td>
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
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop