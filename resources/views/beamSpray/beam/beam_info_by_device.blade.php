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
                <th width="70">预制梁编号</th>
                <th width="100" class="hidden-xs">开始时间</th>
                <th width="100" class="hidden-xs">结束时间</th>
                <th width="100" class="hidden-xs">工程名称</th>
                <th width="100" class="hidden-xs">工程部位</th>
                <th width="100">养生周期(天)</th>
                <th width="100">是否完成</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $v)
                    <tr class="text-c">
                        <td>{{$from++}}</td>
                        <td>{{$v['beam_num']}}</td>
                        <td>{{$v['start_time']}}</td>
                        <td>{{$v['end_time']}}</td>
                        <td>{{$v['project_name']}}</td>
                        <td>{{$v['project_place']}}</td>
                        <td>{{$v['days_spend']}}</td>
                        <td>
                            @if($v['is_finish']==0)
                                未完成
                            @else
                                完成
                            @endif
                        </td>
                        <td>
                            <input class="btn btn-primary radius open-detail" type="button" data-title="喷淋记录" data-url="{{url('beam_spray/spray_detail').'/'.$v['id']}}" value="喷淋记录">
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        {{--@if($last_page > 1)--}}
        {{--@include('admin.layouts.page')--}}
        {{--@endif--}}
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
        $(".open-detail").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.5,
                area: ['80%', '80%'],
                content: url
            });
        });
    </script>
@stop