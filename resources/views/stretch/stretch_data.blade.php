@extends('admin.layouts.default')

@section('container')

@include('stretch._stretch_data')


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
        list.init();
        $(".open-detail-data").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                area: ['90%', '90%'],
                content: url,
            });
        });
    </script>
@stop