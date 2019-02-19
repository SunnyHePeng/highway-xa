@extends('admin.layouts.default')

@section('container')

    @include('notice._notice_read_download_condition')

@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
        list.init();
        $(".show-read").on('click',function(){

            var url=$(this).attr('data-url');
            var title=$(this).attr('title');
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                area: ['90%', '90%'],
                content: url,
            });

        });
        $(".show-download").on('click',function(){

            var url=$(this).attr('data-url');
            var title=$(this).attr('title');
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