@extends('admin.layouts.iframe')

@section('container')
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/> 
<article class="cl pd-10">
	<div class="cl pd-5 bg-1 bk-gray noprint"> 
	  <!-- <span class="ml-10 export-file export-pdf" data-name="试验室周报表" data-obj="table_list" data-type="pdf"><a href="javascript:;" title="导为pdf文件"><img src="/static/admin/images/pdf.svg"></a></span> -->
	  <span class="ml-10 export-file export-excel" data-name="试验室周报表" data-obj="table_list" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
	  <span class="ml-10" onclick="javascript:window.print();"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	</div>
	<div class="mt-10 dataTables_wrapper">
		<h2 class="text-c">{{$search['start_date']}}~{{$search['end_date']}}试验室周报表</h2>
		@include('lab._stat_week')
	</div>
</article>
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