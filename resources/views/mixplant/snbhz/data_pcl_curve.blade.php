@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div>
  <form id="search_form" data="d_id,data,start_date,end_date" method="get" data-url="{{url('snbhz/deviation_curve')}}">
        设备编码
        <span class="select-box" style="width:auto; padding: 3px 5px;">
            <select name="d_id" id="d_id" class="select select2">
                @if(isset($device))
                @foreach($device as $v)
                <option value="{{$v['id']}}" @if(isset($search['d_id']) && $search['d_id'] == $v['id']) selected @endif>{{$v['name']}}-{{$v['dcode']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        日期
        <input name="date" placeholder="请输入日期" value="@if(isset($search['date']) && $search['date']){{$search['date']}}@endif" type="text" onfocus="WdatePicker()" id="date" class="input-text Wdate">
        时间：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker({dateFmt:'HH:mm'})" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker({dateFmt:'HH:mm'})" id="end_date" class="input-text Wdate">
        <input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>   
  </form>
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
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
chart.mixline('拌和数据偏差率曲线', '百分比%', 'mixline', 'chart');
</script>
@stop