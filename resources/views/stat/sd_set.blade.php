@extends('admin.layouts.default')

@section('container')
   @include('stat.sd_set_info')
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        list.init();
        $(function() {

            $('.add-a').on('click', function () {
                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');

                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['70%', '70%'],
                    content: url,
                });
            });
        })

    </script>
@stop
