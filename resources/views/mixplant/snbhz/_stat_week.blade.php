<table id="table_list" class="table table-border table-bordered table-bg table-sort">
	<thead>
		<tr class="text-c">
			<th width="40">序号</th>
			<th width="100" class="hidden-xs">管理单位</th>
			<th width="100" class="hidden-xs">标段名称</th>
			<th width="100">设备名称</th>
			<th width="100">生产总量(吨)</th>
			<th width="100">生产次数</th>
			<th width="100">报警次数</th>
			<th width="100">处理次数</th>
			<th width="100">不合格率</th>
			<th width="100">周次</th>
			<th width="150">统计区间</th>
		</tr>
	</thead>
	<tbody>
		@if($data)
		@foreach($data as $value)
		<tr class="text-c">
			<td class="hidden-xs hidden-sm">{{$page_num++}}</td>
			<td class="hidden-xs hidden-sm">{{$value['sup']['name']}}</td>
			<td>{{$value['section']['name']}}</td>
			<td>{{$value['device']['name']}}-{{$value['device']['dcode']}}</td>
			<td>{{round($value['scl']/1000, 2)}}</td>
			<td>{{$value['sc_num']}}</td>
			<td>{{$value['bj_num']}}</td>
			<td>{{$value['cl_num']}}</td>
			<td>{{$value['bhgl']}}%</td>
			<td>{{$value['week']}}</td>
			@if($value['week'] == date('W'))
			<?php 
			$d = date("w"); 
			if($d == 0){  //周日
				$d = 7;
			}
			?>
			<td>@if($d > 1){{date('Y.m.d', $value['created_at']-86400*($d-1))}}-{{date('Y.m.d', $value['created_at']-86400)}}@endif</td>
			@else
			<td>{{date('Y.m.d', $value['created_at']-86400*7)}}-{{date('Y.m.d', $value['created_at']-86400)}}</td>
			@endif
		</tr>
		@endforeach
		@endif
	</tbody>
</table>