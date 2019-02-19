@extends('admin.layouts.iframe')

@section('container')
<style>
#table tr th {min-width: 60px;}
#table tr th.time {min-width: 80px;}
</style>
<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs noprint"> 
  <span class="ml-10 export-file export-excel" data-name="试验室信息化不合格处理台账" data-obj="table" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
  <span onclick="javascript:window.print();" class="ml-10" data-name="试验室信息化不合格处理台账"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
</div>
<!--startprint-->
<div class="mt-10 dataTables_wrapper">
  <h2 class="text-c" style="width:1730px;">{{$sec_name}} {{$start_date}}~{{$end_date}}试验室信息化不合格处理台账</h2>
  <table id="table" class="table table-border table-bordered table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th rowspan="2">序号</th>
        <th rowspan="2" class="time">试验日期</th>
        <th rowspan="2" class="time">报警日期</th>
        <th rowspan="2" class="time">试验报告名称</th>
        <th rowspan="2" class="time">报告编号</th>
        <th rowspan="2">不合格项目</th>
        <th rowspan="2">报警级别</th>
        <th colspan="3">施工单位</th>
        <th colspan="3">监理单位</th>
        <th colspan="3">建设单位</th>
        <th colspan="3">签字</th>
        <th rowspan="2">备注</th>
      </tr>
      <tr class="text-c">
        <th class="time">原因分析及处理意见</th>
        <th>处理人</th>
        <th class="time">处理时间</th>
        <th class="time">原因分析及处理意见</th>
        <th>处理人</th>
        <th class="time">处理时间</th>
        <th class="time">原因分析及处理意见</th>
        <th>处理人</th>
        <th class="time">处理时间</th>
        <th>施工单位</th>
        <th>监理单位</th>
        <th>建设单位</th>
      </tr>
    </thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
        <tr class="text-c">
            <td>{{$page_num++}}</td>
            <td>{{date('Y-m-d', $value['time'])}}</td>
            <td>{{date('Y-m-d', $value['created_at'])}}</td>
            <td>
              @if(in_array($value['sylx'],[1,2,3,4,5,6,7,8,9,10]))
                {{$symc[$value['sylx']]}}
              @else 
                {{$value['sylx']}}
              @endif
            </td>
            <td>{{$value['sybh']}}</td>
            <td>{{$value['warn_info']}}</td>
            <td>
                @if($value['warn_sx_level'] && $value['warn_sx_level'] > $value['warn_level'])
          {{$level[$value['warn_sx_level']]}}({{$value['warn_sx_info']}})
        @else
          {{$level[$value['warn_level']]}}
        @endif
            </td>
            <td>{{$value['sec_info']}}</td>
          <td>{{$value['sec_name']}}</td>
          <td>@if($value['sec_time']){{date('Y-m-d H:i:s', $value['sec_time'])}}@endif</td>
          <td>{{$value['sup_info']}}</td>
          <td>{{$value['sup_name']}}</td>
          <td>@if($value['sup_time']){{date('Y-m-d H:i:s', $value['sup_time'])}}@endif</td>
          <td>{{$value['pro_info']}}</td>
          <td>{{$value['pro_name']}}</td>
          <td>@if($value['pro_time']){{date('Y-m-d H:i:s', $value['pro_time'])}}@endif</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>
<!--endprint-->
@stop

@section('script')
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