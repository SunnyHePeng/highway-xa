@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div>
    <form id="search_form" data="start_date,end_date" method="get" action="/{{ request()->path() }}">
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="{{ request('start_date') }}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="{{ request('end_date') }}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  </form>
</div>

<div class="mt-20">
  <div id="mixline" style="min-width:380px;height:400px"></div>
</div>
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
    $('select[name=section]').change(function(){
        $(this).parent('form').submit();
    })
   var tooltip = {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
         '<td style="padding:0"><b>{point.y} 次</b></td></tr>',
      footerFormat: '</table>',
      shared: true,
      useHTML: true
   }; 
      
   var json = {
       chart: {
           type: 'column'
        },
       title: {
        text: '各类型试验次数统计' 
       },
       subtitle:{
           text: '{{ $subtitle }}'
       },       
       tooltip: tooltip,
       xAxis: {
        categories: {!! json_encode($legend) !!},
        crosshair: true           
       },
       yAxis: {
        min: 0,
        title: {
            text: '次数'         
        }             
       },
       series: {!! json_encode($series) !!},
       credits: {
        enabled: false
       },
       plotOptions:{
            column: {
                dataLabels: {
                    enabled: true
                }
            }           
       }       
   };   

   $('#mixline').highcharts(json);
</script>
@stop