@extends('admin.layouts.iframe')

@section('container')
	<article class="cl pd-10">
		<div class="mt-10 dataTables_wrapper">
			<table id="table_list" class="table table-border table-bordered table-bg table-sort">
				<thead>
				<tr class="text-c">
					<th width="40">序号</th>
					<th width="100" class="hidden-xs">开始时间</th>
					<th width="100" class="hidden-xs">结束时间</th>
					<th width="100">喷淋时间(s)</th>
					<th width="100">喷淋间隔(h)</th>
					<th width="100">温度(℃)</th>
					<th width="100">湿度(%)</th>
					<th width="100">更新时间</th>
				</tr>
				</thead>
				<tbody>
				@foreach($records as $key => $record)
					<tr class="text-c">
						<td class="hidden-xs hidden-sm">{{ $key+1 }}</td>
						<td class="hidden-xs hidden-sm">{{ $record->start_time or '-'}}</td>
						<td>{{ $record->end_time or '-'}}</td>
						<td>{{ $record->time_count or '-'}}</td>
						<td>{{ $record->time_interval or '-'}}</td>
						<td>{{ $record->temperature or '-'}}</td>
						<td>{{ $record->moisture or '-'}}</td>
						<td>{{ $record->created_at }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</article>
@stop
