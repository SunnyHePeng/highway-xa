@extends('admin.layouts.default')

@section('container')

    @include('notice._my_publish')

@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
        list.init();
        $(".add-notice").on('click',function(){
            var title=$(this).attr('data-title');
            var url=$(this).attr('data-url');
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade:0.8,
                area: ['90%', '90%'],
                content: url,
            });
        });
        $(".load-content").on('click',function(){
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