@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-c">
        <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">

            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            {{--<input type="hidden" value="{{$pro_id}}" name="pro_id">--}}
            {{--<input type="hidden" value="{{$sup_id}}" name="sup_id">--}}
            {{--<input type="hidden" value="{{$sec_id}}" name="sec_id">--}}
            {{--<input type="hidden" value="{{$device_id}}" name="device_id">--}}
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list"  class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="70">序号</th>
                <th width="70">喷淋开始时间</th>
                <th width="100" class="hidden-xs">喷淋结束时间</th>
                <th width="100" class="hidden-xs">喷淋时间(s)</th>
                <th width="100" class="hidden-xs">喷淋间隔(h)</th>
                <th width="100" class="hidden-xs">温度(℃)</th>
                <th width="100">湿度(%)</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $v)
                    <tr class="text-c">
                        <td>{{$from++}}</td>
                        <td>{{$v['start_time']}}</td>
                        <td>{{$v['end_time']}}</td>
                        <td>{{$v['time_count']}}</td>
                        <td>{{$v['time_interval']}}</td>
                        <td>{{$v['temperature']}}</td>
                        <td>{{$v['moisture']}}</td>
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