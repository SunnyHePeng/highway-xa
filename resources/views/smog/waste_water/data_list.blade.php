@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <div class="text-c">
        <form id="search_form" data="start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="70">序号</th>
                <th width="70">时间</th>
                <th width="70">入口瞬时流量</th>
                <th width="70">出口瞬时流量</th>
                <th width="70">电压</th>
                <th width="70">水温</th>
                <th width="70">进水BOD</th>
                <th width="70">出水BOD</th>
                <th width="70">pH值</th>
                <th width="70">色度</th>
            </tr>
            </thead>
            <tbody>
               @if($data)
                 @foreach($data as$k=> $v)
                     <tr class="text-c">
                        <td>{{$from++}}</td>
                         <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                         <td>{{$v['enter_instantaneous_flow']}}</td>
                         <td>{{$v['exit_instantaneous_flow']}}</td>
                         <td>{{$v['voltage']}}</td>
                         <td>{{$v['water_temperature']}}</td>
                         <td>{{$v['enter_BOD']}}</td>
                         <td>{{$v['exit_BOD']}}</td>
                         <td>{{$v['pH']}}</td>
                         <td>{{$v['chrominance']}}</td>
                     </tr>
                 @endforeach
               @endif
            </tbody>
        </table>
        @if($last_page > 1)
            @include('admin.layouts.page')
        @endif
    </div>

@stop

@section('layer')

@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>

    <script type="text/javascript">
        list.init();


    </script>
@stop
