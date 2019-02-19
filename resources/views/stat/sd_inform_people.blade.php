@extends('admin.layouts.default')

@section('container')
    <div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l">
    <a class="btn open-add btn-primary radius" data-is-reload="1" data-title="添加微信推送通知人员" data-url="{{url('stat/sd_inform_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加微信推送通知人员</a>
  </span>
    </div>
    <div class=" col-xs-12 col-sm-12 mt-20" class="padding-left: 0; ">
        <div class="panel panel-info">

            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="80" class="hidden-xs hidden-sm">序号</th>
                        <th width="200" class="hidden-xs hidden-sm">单位</th>
                        <th width="200">登陆账号</th>
                        <th width="200">用户名</th>
                        <th width="150">电话</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                       @if($data)
                           @foreach($data as $k=>$v)
                               <tr class="text-c">
                                   <td>{{$k+1}}</td>
                                   <td>{{$v['user']['company']['name']}}</td>
                                   <td>{{$v['user']['username']}}</td>
                                   <td>{{$v['user']['name']}}</td>
                                   <td>{{$v['user']['phone']}}</td>
                                   <td>
                                       <a style="text-decoration:none" class="ml-5 del-sd btn btn-danger radius size-MINI" href="javascript:;" data-url="{{url('stat/sdtj_inform_del').'?id='.$v['id']}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                   </td>
                               </tr>
                           @endforeach
                       @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        list.init();
        $(function(){
            $('.open-add').on('click',function(){
                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');
                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['50%', '70%'],
                    content: url,
                });
            });
            $('.del-sd').on('click',function(){
                var that=$(this);
                layer.confirm('确定要删除吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var url=that.attr('data-url');

                    $.get(url,function(data){
                        if(data.status==0){
                            layer.alert(data.mess);
                        }
                        if(data.status=1){
                            layer.msg(data.mess, {
                                icon: 1,
                                time: 2000
                            }, function(){
                                //do something
                                parent.parent.location.reload();
                            });
                        }

                    });
                }, function(){

                });
            });

        });

    </script>
@stop
