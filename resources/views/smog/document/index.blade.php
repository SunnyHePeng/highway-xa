@extends('admin.layouts.tree')

@section('container')
<div class="cl pd-15 mt-20">
	<span class="l">
		<a class="btn btn-primary radius open-iframe" data-title="创建" data-url="{{ url('smog/document/create') }}" href="javascript:;"><i class="Hui-iconfont"></i>创建</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="10" class="hidden-xs hidden-sm">序号</th>
				<th width="50">标题</th>
				<th width="300">简述</th>
				<th width="20" class="hidden-xs hidden-sm">发布日期</th>
				<th width="10" class="hidden-xs hidden-sm">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach($documents as $key => $document)
			<tr class="text-c">
			<td class="hidden-xs hidden-sm">{{ $documents->perPage() * ($documents->currentPage() - 1) + ($key + 1) }}</td>
				<td>{{ $document->title }}</td>
				<td class="{{ is_file(public_path($document->path)) ? 'open-iframe' : '' }}" data-title="文档详情" data-url="{{ asset($document->path) }}">{{ $document->description }}</td>
				<td class="hidden-xs hidden-sm">{{ $document->created_at }}</td>
				<td class="hidden-xs hidden-sm"><a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{ $document->id }}" data-url="{{ url("smog/document/destroy/{$document->id}") }}" title="删除"><i class="Hui-iconfont"></i></a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@include('components.paginationAndStat', ['_paginate' => $documents])
</div>

@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop