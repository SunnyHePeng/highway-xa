@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<style>
#table tr th {min-width: 60px;}
#table tr th.time {min-width: 120px;}
</style>
<div>
  <form id="search_form" data="sec_id,start_date,end_date" method="get" data-url="{{url('snbhz/tzlb')}}">
        标段
        <span class="select-box" style="width:auto; padding: 3px 5px;">
          <select name="sec_id" id="sec_id" class="select select2">
              <option value="0">全部</option>
              @if(isset($section))
              @foreach($section as $v)
              <option value="{{$v['id']}}" @if(isset($search['sec_id']) && $search['sec_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
              @endforeach
              @endif
          </select>
        </span>
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>   
  </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs"> 
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="处理台账列表" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="处理台账列表" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
  @if($user['role'] == 3)
  <span class="btn btn-success radius open-iframe" data-title="标段处理台账图表" data-url="{{url('snbhz/tzlb?type=sec')}}">标段处理台账图表</span>
  @endif
</div>
<div class="mt-10 dataTables_wrapper">
  <table id="table" class="table table-border table-bordered table-bg table-sort">
  	<thead>
  		<tr class="text-c">
  			<th rowspan="2">序号</th>
  			<th rowspan="2" class="time">报警时间</th>
  			<th rowspan="2">不合格项目</th>
  			<th rowspan="2">报警级别</th>
  			<th colspan="3">施工单位</th>
  			<th colspan="3">监理单位</th>
  			<th colspan="3">建设单位</th>
  			<th colspan="3">签字</th>
  			<th rowspan="2">备注</th>
  		</tr>
  		<tr class="text-c">
  			<th class="time">原因分析及处理意见</th>
  			<th>处理人</th>
  			<th class="time">处理时间</th>
  			<th class="time">原因分析及处理意见</th>
  			<th>处理人</th>
  			<th class="time">处理时间</th>
  			<th class="time">原因分析及处理意见</th>
  			<th>处理人</th>
  			<th class="time">处理时间</th>
  			<th>施工单位</th>
  			<th>监理单位</th>
  			<th>建设单位</th>
  		</tr>
  	</thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
      <?php 
        if(($user['role']==4 && !$value['sup_info']) || ($user['role']==5 && !$value['sec_info']) || ($user['role']==3 && !$value['pro_info'])){
          $is_cl = false;
        }else{
          $is_cl = true;
        }
      ?>
        <tr class="text-c @if(!$is_cl) red-line @endif">
          	<td>{{$page_num++}}</td>
          	<td>{{date('Y-m-d H:i:s', $value['time'])}}</td>
          	<td>{{$value['warn_info']}}</td>
          	<td>
              	@if($value['warn_sx_level'] && $value['warn_sx_level'] > $value['warn_level'])
					{{$level[$value['warn_sx_level']]}}({{$value['warn_sx_info']}})
				@else
					{{$level[$value['warn_level']]}}
				@endif
          	</td>
          	<td>{{$value['sec_info']}}</td>
	      	<td>{{$value['sec_name']}}</td>
	      	<td>@if($value['sec_time']){{date('Y-m-d H:i:s', $value['sec_time'])}}@endif</td>
	      	<td>{{$value['sup_info']}}</td>
	      	<td>{{$value['sup_name']}}</td>
	      	<td>@if($value['sup_time']){{date('Y-m-d H:i:s', $value['sup_time'])}}@endif</td>
	      	<td>{{$value['pro_info']}}</td>
	      	<td>{{$value['pro_name']}}</td>
	      	<td>@if($value['pro_time']){{date('Y-m-d H:i:s', $value['pro_time'])}}@endif</td>
	      	<td></td>
	        <td></td>
	      	<td></td>
	      	<td></td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop