@extends('admin.layouts.tree')

@section('container')
<div class="mt-20">
  <!-- <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius" href="javascript:;" data-title="{{$ztree_name}} 试验数据列表" data-url="{{url('lab/lab_data_info?pro_id='.$pro_id.'&sec_id='.$sec_id.'&sup_id='.$sup_id.'&dev_id='.$device_id)}}">试验数据列表</a>
 -->
 <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius" href="javascript:;" data-title="{{$ztree_name}} 试验数据列表" data-url="{{url('lab/lab_data_info?'.$tree_key.'='.$tree_value)}}">试验数据列表</a>
</div>
<div class="mt-20">
  <div id="mixline" style="min-width:380px;height:400px"></div>
</div>
<input id="chart" value="{{$chart}}" type="hidden">

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();
chart.column('{{$chart_title}}', '次', 'mixline', 'chart');
</script>
@stop