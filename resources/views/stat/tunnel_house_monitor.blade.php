@extends('admin.layouts.default')

@section('container')
    @if(Auth::user()->role == 5)
    <div class="col-xs-12 col-sm-12 mt-10">
	<span class="l ml-20">
		<a class="btn btn-primary radius add-a" data-title="添加观测数据" href="javascript:;" data-url="{{url('stat/tunnel_house_monitor_add')}}"><i class="Hui-iconfont">&#xe600;</i>添加观测数据</a>
	</span>
    </div>
    @endif
    <div class=" col-xs-12 col-sm-12 mt-20" class="padding-left: 0; ">
        <div class="panel panel-info">
            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="40">序号</th>
                        <th width="100">数据填写时间</th>
                        <th width="100">审核时间</th>
                        <th width="100">填写人</th>
                        <th width="100">驻地办审核人</th>
                        <th width="100">审核状态</th>
                        <th width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                      @if($data)
                        @foreach($data as $k=>$v)
                            <tr class="text-c">
                                <td>{{$k+1}}</td>
                                <td>{{date('Y-m-d H:i:s',$v['created_at'])}}</td>
                                <td>
                                   @if($v['is_check'] == 1)
                                       {{date('Y-m-d H:i:s',$v['check_time'])}}
                                   @endif
                                </td>
                                <td>{{$v['write_user']['name']}}</td>
                                <td>
                                    @if($v['is_check'] == 1)
                                        {{$v['check_user']['name']}}
                                    @endif
                                </td>
                                <td>
                                    @if($v['is_check'])
                                        <input class="btn btn-success radius size-MINI" type="button" value="已审核">
                                    @else
                                        <input class="btn btn-warning radius size-MINI" type="button" value="未审核">
                                    @endif
                                </td>
                                <td>
                                    <input class="btn btn-primary radius open-detail" data-title="观测数据表" data-url="{{url('stat/tunnel_house_monitor_detail'.'/'.$v['id'])}}" type="button" value="观测详细数据">
                                </td>
                            </tr>
                        @endforeach
                      @endif
                    </tbody>
                </table>
                @if($last_page>1)
                    @include('admin.layouts.page')
                @endif
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
            $('.add-a').on('click',function(){
                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');
                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['60%', '95%'],
                    content: url,
                });
            });

            $('.open-detail').on('click',function(){
                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');
                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['90%', '90%'],
                    content: url,
                });
            });
        });

    </script>
@stop
