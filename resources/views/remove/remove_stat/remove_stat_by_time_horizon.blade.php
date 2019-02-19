@extends('admin.layouts.default')

@section('container')

    @include('remove.remove_stat._remove_stat_by_time_horizon')


@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
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
        $('.open-print').on('click', function () {
            bdhtml = window.document.body.innerHTML;
            sprnstr = "<!--startprint-->";
            eprnstr = "<!--endprint-->";
            prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
            prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
            window.document.body.innerHTML = prnhtml;
            window.print();
            location.reload();
        });
    </script>
@stop