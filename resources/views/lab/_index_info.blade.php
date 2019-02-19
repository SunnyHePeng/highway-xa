<div class="row cl mb-20">
	<div class="col-xs-12 col-sm-2">
		<div class="panel panel-primary">
			<div class="panel-header" style="text-align:center;">试验设备</div>
			<a href="{{url('lab/lab_data?pro_id='.$pro_id)}}">
			<div class="panel-body">
				总数 {{$device_count ? $device_count : 0}}台<br><br>
				在线 {{$device_online ? $device_online : 0}}台
			</div>
			</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-2 ">
		<div class="panel panel-warning">
			<div class="panel-header" style="text-align:center;">当天试验信息</div>
			<a href="{{url('lab/warn_data?pro_id='.$pro_id)}}">
			<div class="panel-body">
				试验数 {{$sc_num ? $sc_num : 0}}<br>
				报警数 {{$warn_num ? $warn_num : 0}}<br>
				未处理报警 {{$not_deal_warn ? $not_deal_warn : 0}}
			</div>
			</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-danger">
			<div class="panel-header" style="text-align:center;">待处理报警( <span class="warn_num">0</span> ) <img src="../../static/admin/images/news02.gif"  class="news_mess" style="display: none"></div>
			<div class="panel-body warn_mess">

			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-2 col-sm-offset-2 hidden-sm hidden-xs" style=";height: 50px;text-align:right">
		<a style="text-decoration:none" class="mt-5 ml-5  open-iframe btn btn-secondary radius size-L" href="javascript:;" data-title="所有试验室视频" data-url="{{url('lab/video').'/all'}}">所有试验室实时视频</a>
	</div>
</div>		

<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">监理合同段</th>
				<th width="80">标段名称</th>
				<th width="80">设备名称</th>
				<!-- <th width="80">设备编码</th> -->
				<th width="60">监控设备在线状态</th>
				<!-- <th width="60">网络状态</th>
				<th width="60">供电状态</th>
				<th width="60">故障报警</th> -->
				<th width="60" class="hidden-xs hidden-sm">摄像头状态</th>
				<th width="100" class="hidden-xs hidden-sm">设备状态最新上报时间</th>
				<th width="100" class="hidden-xs hidden-sm">试验数据最新上报时间</th>
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
				<td>{{$value['model']}}{{$value['category']['name']}}</td>
				<!-- <td>{{$value['dcode']}}</td> -->
				<td><span class="label @if($value['J1'] == 1) label-success @else label-danger @endif radius">{{$J1[$value['J1']]}}</span></td>
				<!-- <td><span class="label @if($value['J2'] == 1) label-success @else label-danger @endif radius">{{$J2[$value['J2']]}}</span></td>
				<td><span class="label @if($value['J3'] == 1) label-success @else label-danger @endif radius">{{$J3[$value['J3']]}}</span></td>
				<td><span class="label @if($value['J4'] == 1) label-success @else label-danger @endif radius">{{$J4[$value['J4']]}}</span></td> -->
				<td class="hidden-xs hidden-sm">
					@if($value['J5'] == 2) 
					<span class="label label-success">{{$J5[$value['J5']]}}</span>
					@else
					<span class="label @if($value['J5'] == 1) label-success @elseif($value['J5'] == 0) label-danger @endif radius">{{$J5[$value['J5']]}}</span>
					@endif
				</td>
				<td class="hidden-xs hidden-sm">@if($value['status_time']){{date('Y-m-d H:i:s', $value['status_time'])}}@endif</td>
				<td class="hidden-xs hidden-sm">@if($value['last_time']){{date('Y-m-d H:i:s', $value['last_time'])}}@endif</td>
				<td class="f-14 product-brand-manage td-manage"> 
				  	<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="{{$value['section']['name']}}-{{$value['model']}}{{$value['category']['name']}} 试验数据" data-url="{{url('lab/lab_data_info?device_id='.$value['id'].'&pro_id='.$value['project_id'])}}">试验数据</a>
                      @if($value['camera1'])
          			<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI hidden-sm hidden-xs" href="javascript:;" data-title="{{$value['section']['name']}}-{{$value['model']}}{{$value['category']['name']}} 实时视频" data-url="{{url('lab/video/'.$value['id'].'?pro_id='.$value['project_id'])}}">实时视频</a>
                      @endif
          		</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>