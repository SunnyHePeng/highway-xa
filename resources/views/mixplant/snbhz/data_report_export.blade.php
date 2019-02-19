@extends('admin.layouts.iframe')

@section('container')
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/> 
<article class="cl pd-10">
	<div class="cl pd-5 bg-1 bk-gray"> 
	  <!-- <span class="ml-10 export-file export-pdf" data-name="拌合数据报表" data-obj="table" data-type="pdf"><a href="javascript:;" title="导为pdf文件"><img src="/static/admin/images/pdf.svg"></a></span>
	   --><span class="ml-10 export-file export-excel" data-name="拌合数据报表" data-obj="table" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
	  <!-- <span class="ml-10 export-file export-dy" data-name="拌合数据报表" data-obj="table" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	 --></div>
	<div class="mt-10 dataTables_wrapper">
	  <table id="table" class="table table-border table-bordered table-bg table-sort">
	    <thead>
	      <tr class="text-c">
	        <th colspan="8" rowspan="2">基本信息</th>
	        <th colspan="12">粗细集料（+-2%）</th>
	        <th colspan="6">粉料（+-1%）</th>
	        <th colspan="9">液料（+-1%）</th>
	      </tr>
	      <tr class="text-c">
	      @foreach(Config()->get('common.snbhz_info_detail') as $v)
	        <th colspan="3">{{$v['name']}}</th>
	      @endforeach
	      </tr>
	      <tr class="text-c">
	        <th>序号</th>
	        <th>监理</th>
	        <th>合同段</th>
	        <th>设备编码</th>
	        <th>设备型号</th>
	        <!-- <th>车牌号</th> -->
	        <th>配比号</th>
	        <th>盘方量(吨)</th>
	        <th class="time">生产时间</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	        <th>设定值</th>
	        <th>实际值</th>
	        <th>偏差率</th>
	      </tr>
	    </thead>
	    <tbody>
	      @if($data)
	      @foreach($data as $value)
	        <tr class="text-c">
	          <td>{{$page_num++}}</td>
	          <td>{{$value['sup']['name']}}</td>
	          <td>{{$value['section']['name']}}</td>
	          <td>{{$value['device']['dcode']}}</td>
	          <td>{{$value['device']['model']}}</td>
	          <!-- <td>{{$value['cph']}}</td> -->
	          <td>{{$value['pbbh']}}</td>
	          <td>{{$value['pfl']}}</td>
	          <td>{{date('Y-m-d H:i:s', $value['time'])}}</td>
	          @foreach($value['detail'] as $v)
	          <td>{{$v['design']}}</td>
	          <td>{{$v['fact']}}</td>
	          <td>{{$v['pcl']}}</td>
	          @endforeach
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