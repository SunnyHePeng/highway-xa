@extends('admin.layouts.default')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <link href="/static/admin/css/style.css" rel="stylesheet" type="text/css">
    <div>
        <form id="search_form" data="start_date,end_date" method="get" data-url="{{$url}}">

            时间：
            <input name="start_date" placeholder="请输入开始时间" value="{{date('Y-m-d',$search['start_time'])}}" type="text" onfocus="WdatePicker({minDate:'2018-07-18'})" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{date('Y-m-d',$search['end_time']-86400)}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">

            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class=" mt-5 cl pd-5 bg-0 bk-gray noprint text-l ">
        <span class="ml-10 export-file export-dy open-print" data-name="详细天气信息" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
    </div>
    <!--startprint-->
    <h3 class="text-c">西安外环高速南段{{date('Y-m-d',$search['start_time'])}}~{{date('Y-m-d',$search['end_time']-86400)}}详细天气信息</h3>
    <table id="table_list" class="table table-border table-bordered table-bg table-sort mt-10 ">
        <thead>
        <tr class="text-c">
            <th width="40">序号</th>
            <th width="100">日期</th>
            <th width="100">详细天气</th>
            <th width="100">温度</th>
            <th width="100">施工情况</th>
        </tr>
        </thead>
        <tbody>
           @if($data)
             @foreach($data as $v)
                 <tr class="text-c">
                     <td>{{$from++}}</td>
                     <td>{{date('Y-m-d',$v['time'])}}</td>
                     <td>
                         <img src="/static/admin/images/weather/{{$v['code']}}.png" alt="">
                         {{$v['weather_text']}}
                     </td>
                     <td>{{$v['temperature_low']}}-{{$v['temperature_high']}}℃</td>
                     <td>施工</td>
                 </tr>
             @endforeach
           @endif
        </tbody>
    </table>
    <!--endprint-->
    @if($last_page>1)
        @include('admin.layouts.page')
    @endif
@stop



@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        list.init();
        $('.open-print').on('click',function(){
            bdhtml = window.document.body.innerHTML;
            sprnstr = "<!--startprint-->";
            eprnstr = "<!--endprint-->";
            prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
            prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
            window.document.body.innerHTML = prnhtml;
            window.print();
            location.reload();
        });

    </script>
@stop
