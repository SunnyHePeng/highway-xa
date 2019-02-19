@extends('admin.layouts.iframe')

@section('container')
<style>
#table tr th {min-width: 50px;}
#table tr th.time {min-width: 100px;}
</style>
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/> 
<article class="cl pd-10">
	<div class="cl pd-5 bg-1 bk-gray noprint"> 
	  <span class="ml-10 export-file export-excel" data-name="登录统计" data-obj="table_list" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
	  <span class="ml-10" onclick="javascript:window.print();"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	</div>

	<div class="mt-10 dataTables_wrapper">
		<h2 class="text-c">{{$search['start_date']}}~{{$search['end_date']}} @if($section){{$section}} @endif @if($module){{$module}} @endif登录统计</h2> 
	@if($user['role'] ==2 || $user['role'] ==1)
	  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
	    <thead>
	      <tr class="text-c">
	        <th width="70" class="no-left">序号</th>
	        <th width="100">项目名称</th>
	        <th width="100">项目及施工单位登录总次数</th>
	      </tr>
	    </thead>
	    <tbody>
	      @if($data)
	      @foreach($data as $value)
	        <tr class="text-c">
	          <td class="no-left">{{$page_num++}}</td>
	          <td>{{$value['name']}}</td>
	          <td>{{$value['num']}}</td>
	        </tr>
	      @endforeach
	      @endif
	    </tbody>
	  </table>
	@else
	  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
	    <thead>
	      <tr class="text-c">
	        <th width="70" class="no-left">序号</th>
	        <th width="100">监理</th>
	        <th width="100">标段</th>
	        <th width="100">登录次数</th>
	      </tr>
	    </thead>
	    <tbody>
	      @if($data)
	      @foreach($data as $value)
	        <tr class="text-c">
	          <td class="no-left">{{$page_num++}}</td>
	          <td>{{$value['supervision']['name']}}</td>
	          <td>{{$value['section']['name']}}</td>
	          <td>{{$value['num']}}</td>
	        </tr>
	      @endforeach
	      @endif
	    </tbody>
	  </table>
	@endif
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