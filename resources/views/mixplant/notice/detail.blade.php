@extends('admin.layouts.tree')

@section('container')
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="10" class="hidden-xs hidden-sm">序号</th>
				<th width="50">角色</th>
				<th width="50">公司</th>
				<th width="50">标段</th>
				<th width="50">职位</th>
				<th width="50">姓名</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $key => $user)
			<tr class="text-c">
				<td class="hidden-xs hidden-sm">{{ $users->perPage() * ($users->currentPage() - 1) + ($key + 1) }}</td>
				<td>{{ $user->user->roled->display_name or '-' }}</td>
				<td>{{ $user->user->company->name or '-' }}</td>
				<td>{{ $user->user->section->name or '-' }}</td>
				<td>{{ $user->user->posi->name or '-' }}</td>
				<td>{{ $user->user->name or '-' }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@include('components.paginationAndStat', ['_paginate' => $users])
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