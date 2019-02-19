@extends('admin.layouts.default')

@section('container')

    <article class="cl pd-20">
        <div class="f-14 cl pd-10 bg-1 bk-gray mt-20">
            <p class="c-error">你没有操作该功能的权限</p>
        </div>
    </article>

@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        list.init();


    </script>
@stop