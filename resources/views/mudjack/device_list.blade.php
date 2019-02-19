@extends('admin.layouts.default')

@section('container')

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="40" class="hidden-xs hidden-sm">序号</th>
                <th width="100">监理名称</th>
                <th width="100">标段名称</th>
                <th width="100">梁场名称</th>
                <th width="100">设备名称</th>
                <th width="100">设备型号</th>
                <th width="100">设备编码</th>
                <th width="100" class="hidden-xs hidden-sm hidden-md">生产厂家</th>
                <th width="100" class="hidden-xs hidden-sm hidden-md">出厂日期</th>
                <th width="100" class="hidden-xs hidden-sm">负责人</th>
                <th width="100" class="hidden-xs hidden-sm">联系方式</th>
            </tr>
            </thead>
            <tbody>
            @if($device_data)
                @foreach($device_data as $k=>$v)
                    <tr class="text-c">
                        <td class="hidden-xs hidden-sm">
                            {{$k+1}}
                        </td>
                        <td>{{$v->sup->name}}</td>
                        <td>{{$v->section->name}}</td>
                        <td>{{$v->beam_site->name}}</td>
                        <td>{{$v->name}}</td>
                        <td>{{$v->model}}</td>
                        <td>{{$v->dcode}}</td>
                        <td>{{$v->factory_name}}</td>
                        <td>{{$v->factory_date}}</td>
                        <td>{{$v->fzr}}</td>
                        <td>{{$v->phone}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

        @stop

        @section('script')
            <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
            <script type="text/javascript" src="/static/admin/js/common.js"></script>
            <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
            <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
            <script type="text/javascript" src="/static/admin/js/chart.js"></script>
            <script type="text/javascript">
                list.init();

            </script>
@stop
