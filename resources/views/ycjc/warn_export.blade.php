@extends('admin.layouts.iframe')

@section('container')
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/> 
<article class="cl pd-10">
	<div class="cl pd-5 bg-1 bk-gray"> 
	  <!-- <span class="ml-10 export-file export-pdf" data-name="扬尘监测报警统计" data-obj="table_list" data-type="pdf"><a href="javascript:;" title="导为pdf文件"><img src="/static/admin/images/pdf.svg"></a></span> -->
	  <span class="ml-10 export-file export-excel" data-name="扬尘监测报警统计" data-obj="table_list" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
	  <span class="ml-10 export-file export-dy" data-name="扬尘监测报警统计" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	</div>

	<div class="mt-10 dataTables_wrapper">
		<table id="table_list" class="table table-border table-bordered table-bg table-sort">
			<thead>
				<tr class="text-c">
					<th width="40">序号</th>
					<th width="100" class="hidden-xs">管理单位</th>
					<th width="100" class="hidden-xs">标段名称</th>
					<th width="100">次数</th>
					<th width="150">时间</th>
				</tr>
			</thead>
			<tbody>
				@if($data)
				@foreach($data as $value)
				<tr class="text-c">
					<td>{{$page_num++}}</td>
					<td class="hidden-xs">{{$value['sup']['name']}}</td>
					<td class="hidden-xs">{{$value['section']['name']}}</td>
					<td>{{$value['num']}}</td>
					<td>{{$value['date']}}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/export/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/static/admin/js/export/jquery.jqprint-0.3.js"></script>

<script src="/lib/tableExport/libs/pdfMake/pdfmake.min.js"></script>
<script src="/lib/tableExport/libs/pdfMake/vfs_fonts.js"></script>
<script type="text/javascript" src="/lib/tableExport/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="/lib/tableExport/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="/lib/tableExport/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="/lib/tableExport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="/lib/tableExport/libs/html2canvas/html2canvas.min.js"></script>
<script type="text/javascript" src="/lib/tableExport/tableExport.js"></script>
<script type="text/javascript" src="/static/admin/js/export.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop