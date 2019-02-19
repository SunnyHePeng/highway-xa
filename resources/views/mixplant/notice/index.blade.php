@extends('admin.layouts.tree')

@section('container')
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn open-iframe btn-primary radius" data-is-reload="1" data-title="添加通知人员" data-url="{{ url('snbhz/notice/create') }}" href="javascript:;">
		  <i class="Hui-iconfont"></i>添加通知人员
		</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="10" class="hidden-xs hidden-sm">序号</th>
				<th width="50">角色</th>
				<th width="50">公司</th>
				<th width="50">标段</th>
				<th width="50">职位</th>
				<th width="50">姓名</th>
				<th width="50">操作</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $key => $msg_user)
			<tr class="text-c">
				<td class="hidden-xs hidden-sm">{{ $users->perPage() * ($users->currentPage() - 1) + ($key + 1) }}</td>
				<td>{{ $msg_user->user->roled->display_name or '-' }}</td>
				<td>{{ $msg_user->user->company->name or '-' }}</td>
				<td>{{ $msg_user->user->section->name or '-' }}</td>
				<td>{{ $msg_user->user->posi->name or '-' }}</td>
				<td>{{ $msg_user->user->name or '-' }}</td>
				<td>
					<a style="text-decoration:none" class="mt-5 ml-5 user-del btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$msg_user->id}}" data-url="{{url('snbhz/notice/del'.'/'.$msg_user->id)}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@include('components.paginationAndStat', ['_paginate' => $users])
</div>

@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">

list.init();
$(".user-del").on('click',function(){

    var url=$(this).attr('data-url');
    layer.confirm('确定要删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.get(url,function(data){
            if(data.status==1){
                layer.alert(data.info);
                var id='data-id='+data.id;

                $("tr ["+id+"]").parent().parent().remove();
            }
            if(data.status==0){
                layer.alert(data.info);
            }

        });

    }, function(){

    });
});
</script>
@stop