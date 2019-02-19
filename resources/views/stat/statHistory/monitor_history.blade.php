@extends('admin.layouts.default')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-l">
        <form id="search_form" data="start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>
    <span class="dropDown mt-20 ml-20"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="隧道监控量测历史数据" data-url="{{$url.'&d=cur'.'&page='}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="隧道监控量测历史数据" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
    </span>
    <h3 class="text-c">隧道监控量测历史</h3>
    @include('stat.statHistory._monitor_history')


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();

    </script>
@stop
