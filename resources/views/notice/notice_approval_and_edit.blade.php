@extends('admin.layouts.default')

@section('container')

    @include('notice._notice_approval_and_edit')

@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
//        list.init();
        $(function(){
            //审核
            $('.approval').on('click',function(event){
                event.stopPropagation();

                var url=$(this).attr("data-url");
                layer.confirm('您同意将此公告正式发布吗？', {
                    btn: ['同意','不同意'] //按钮
                }, function(){

                    $.get(url,function(data){
                              if(data.status==0){
                                  layer.msg(data.info, {
                                      icon: 6,
                                      time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                  }, function(){
                                      //do something
                                      parent.parent.parent.location.reload();

                                  });
                              }
                              if(data.status==1){
                                  layer.alert(data.info);
                              }
                    });
                }, function(){

                });
            });
            //修改
            $('.notice-edit').on('click',function(event){
                event.stopPropagation();
                var title=$(this).attr("data-title");
                var url=$(this).attr("data-url");

                layer.open({
                    type: 2,
                    title: title,
                    shade:0.8,
                    shadeClose: true,
                    area: ['95%', '95%'],
                    content: url,
                });

            });
            //详细信息
            $(".details").on('click',function(){
                var url=$(this).attr("data-url");

                layer.open({
                    type: 2,
                    title: '详细信息',
                    shade:0.8,
                    shadeClose: true,
                    area: ['80%', '80%'],
                    content: url,
                });
            });
        });

    </script>
@stop

