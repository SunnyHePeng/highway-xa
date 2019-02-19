@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<style>
#table tr th {min-width: 50px;}
#table tr th.time {min-width: 100px;}
</style>
<div>
  <form id="search_form" data="d_id,start_date,end_date" method="get" data-url="{{url('snbhz/data_report')}}">
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
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>   
  </form>
</div>

<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs"> 
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出Excel</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="拌和数据报表" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="拌和数据报表" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
</div>

<div class="mt-10 dataTables_wrapper" style="overflow-x: scroll;">
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
      <!--   <th colspan="3">中石</th>
        <th colspan="3">小石</th>
        <th colspan="3">砂</th>
        <th colspan="3">水泥</th>
        <th colspan="3">粉煤灰</th>
        <th colspan="3">水</th>
        <th colspan="3">外加剂</th>
        <th colspan="3">引气剂</th> -->
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
    @if($last_page > 1)
      @include('admin.layouts.page')
  @endif
</div>

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop