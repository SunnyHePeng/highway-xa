<div class="row cl mb-20">
	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-primary">
			<div class="panel-header" style="text-align:center;">桩基设备</div>
			<a href="{{url('zjzl/zj_data?pro_id='.$pro_id)}}">
			<div class="panel-body">
				总数 {{$device_count ? $device_count : 0}}台<br><br>
				在线 {{$device_online ? $device_online : 0}}台
			</div>
			</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-warning">
			<div class="panel-header" style="text-align:center;">当天操作信息</div>
			<a href="{{url('zjzl/warn_info?pro_id='.$pro_id)}}">
			<div class="panel-body">
				桩基数 {{$sc_num ? $sc_num : 0}}<br>
				报警数 {{$warn_num ? $warn_num : 0}}<br>
				未处理报警 {{$not_deal_warn ? $not_deal_warn : 0}}
			</div>
			</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-danger">
			<div class="panel-header" style="text-align:center;">待办事项</div>
			<a href="{{url('zjzl/warn_info?pro_id='.$pro_id.'&type='.$type)}}">
			<div class="panel-body">
				待处理报警 {{$wait_deal_warn ? $wait_deal_warn : 0}}
			</div>
			</a>
		</div>
	</div>
</div>		

<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">监理名称</th>
				<th width="80">标段名称</th>
				<th width="80">设备名称</th>
				<th width="60">设备在线状态</th>
				<th width="60">网络状态</th>
				<th width="60">供电状态</th>
				<th width="60">故障报警</th>
				<th width="100">设备状态最新上报时间</th>
				<th width="100">桩基数据最新上报时间</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($device)
			@foreach($device as $key=>$value)
			<tr class="text-c">
				<td class="hidden-xs hidden-sm">{{$key+1}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['sup']['name']}}</td>
				<td>{{$value['section']['name']}}</td>
				<td>{{$value['name']}}</td>
				<td><span class="label @if($value['J1'] == 1) label-success @else label-danger @endif radius">{{$J1[$value['J1']]}}</span></td>
				<td><span class="label @if($value['J2'] == 1) label-success @else label-danger @endif radius">{{$J2[$value['J2']]}}</span></td>
				<td><span class="label @if($value['J3'] == 1) label-success @else label-danger @endif radius">{{$J3[$value['J3']]}}</span></td>
				<td><span class="label @if($value['J4'] == 1) label-success @else label-danger @endif radius">{{$J4[$value['J4']]}}</span></td>
				<td>@if($value['status_time']){{date('Y-m-d H:i:s', $value['status_time'])}}@endif</td>
				<td>@if($value['last_time']){{date('Y-m-d H:i:s', $value['last_time'])}}@endif</td>
				<td class="f-14 product-brand-manage td-manage"> 
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="{{$value['project']['name']}}-{{$value['name']}} 桩基数据" data-url="{{url('zjzl/zj_data_info/'.$value['id'].'?pro_id='.$value['project_id'].'&cat_id='.$value['cat_id'])}}">桩基数据</a>
          		</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>