@extends('admin.layouts.iframe')
<div class="cl pd-5 mt-20 ml-20">
	<span class="l">
		<a class="btn btn-primary radius add" data-title="添加人员" href="javascript:;" data-url="{{url('snbhz/add_device_failure_push_user')}}"><i class="Hui-iconfont">&#xe600;</i>添加人员</a>
	</span>
</div>

@section('container')
    <div class="mt-10 dataTables_wrapper">
        <h3 class="text-c">拌合站设备端和采集端连接异常时通知人员信息</h3>
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="40">序号</th>
                <th width="100">电话</th>
                <th width="100">姓名</th>
                <th width="100">登陆账号</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
             @if($push_user_data)
                @foreach($push_user_data as$k=> $v)
                    <tr class="text-c" id="list-{{$v->id}}">
                        <td>{{$k+1}}</td>
                        <td>{{$v->user->phone}}</td>
                        <td>{{$v->user->name}}</td>
                        <td>{{$v->user->username}}</td>
                        <td>
                            <a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$v->id}}" data-url="{{url("snbhz/del_device_failure_push_user").'/'.$v->id}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                        </td>
                    </tr>
                @endforeach
             @endif
            </tbody>
        </table>
    </div>
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript">
        list.init();
        $(".add").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.5,
                area: ['80%', '80%'],
                content: url
            });
        });
    </script>
@stop