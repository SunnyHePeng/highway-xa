@extends('admin.layouts.iframe')

@section('container')
<style>
#table tr th {min-width: 50px;}
#table tr th.time {min-width: 100px;}
</style>
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/> 
<article class="cl pd-10">
	@if($user_is_act)
	<div class="cl pd-5 bg-1 bk-gray noprint"> 
	  <!-- <span class="ml-10 export-file export-pdf" data-name="日志信息" data-obj="table_list" data-type="pdf"><a href="javascript:;" title="导为pdf文件"><img src="/static/admin/images/pdf.svg"></a></span>
	  <span class="r ml-10 export-file export-word" data-name="日志信息" data-obj="table_list" data-type="doc" data-url="{{url('manage/log/0')}}"><a href="javascript:;" title="导为word文件"><img src="/static/admin/images/word.svg"></a></span> -->
	  <span class="ml-10 export-file export-excel" data-name="日志信息" data-obj="table_list" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
	  <span class="ml-10" onclick="javascript:window.print();"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	</div>
	@endif
	<div class="mt-10 dataTables_wrapper">
	  <h2 class="text-c">{{$search['start_date']}}~{{$search['end_date']}}日志信息</h2>
	  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
	    <thead>
	      <tr class="text-c">
	        <th width="70" class="no-left">序号</th>
	        <th width="100">IP</th>
	        <th width="100">地址</th>
	        <th width="150" class="hidden-xs">操作内容</th>
	        <th width="100">操作人</th>
	        <th width="100">操作时间</th>
	      </tr>
	    </thead>
	    <tbody>
	      @if($data)
	      @foreach($data as $value)
	        <tr class="text-c" id="list-{{$value['id']}}">
	          <td class="no-left">{{$page_num++}}</td>
	          <td>{{$value['ip']}}</td>
	          <td>{{$value['addr']}}</td>
	          <td class="hidden-xs">{{$value['act']}}</td>
	          <td>{{$value['name']}}</td>
	          <td>{{date('Y-m-d H:i', $value['created_at'])}}</td>
	        </tr>
	      @endforeach
	      @endif
	    </tbody>
	  </table>
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