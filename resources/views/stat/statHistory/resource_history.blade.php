@extends('admin.layouts.default')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-l ml-20">
        <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">

        <span class="select-box" style="width:auto; padding: 3px 5px;">
	  		<select name="data_type" id="data_type" class="select select2">
                <option value="1" @if($search['type']==1) selected="selected"@endif>施工人员数量</option>
                <option value="2" @if($search['type']==2) selected="selected"@endif>机械设备数据</option>
            </select>
        </span>
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>
        <span class="dropDown mt-20 ml-20"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="资源配置统计历史数据" data-url="{{$url.'&d=cur'.'&page='.$search['page']}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="资源配置统计历史数据" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
    <h3 class="text-c">资源配置统计历史</h3>
    @include('stat.statHistory._resource_history')


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();

    </script>
@stop
