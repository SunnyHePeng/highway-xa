@extends('admin.layouts.default')

@section('container')

    @include('remove.remove_stat._remove_set')


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript">
        list.init();
            $('.open-add').on('click',function(){
                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');
                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['70%', '90%'],
                    content: url,
                });
            });

    </script>
@stop