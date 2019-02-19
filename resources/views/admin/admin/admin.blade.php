@extends('admin.layouts.default')

@section('container')
<div>
    <form id="search_form" data="pro_id,type,name,tel" method="get" data-url="{{url('manage/admin')}}">
        建设项目
        <span class="select-box" style="width:auto;">
            <select name="pro_id" id="pro_id" class="select">
                @if(isset($project))
                @foreach($project as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['pro_id']) && $search['pro_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        <span class="select-box" style="width:auto;">
            <select name="role" id="role" class="select">
                <option value="">用户角色</option>
                @if(isset($roles))
                @foreach($roles as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['role']) && $search['role'] == $v['id']) selected @endif>{{$v['display_name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        <input type="text" name="name" id="name" placeholder="请输入姓名" class="input-text search-input" value="@if(isset($search['name']) && $search['name']){{$search['name']}}@endif">
        <input type="text" name="tel" id="tel" placeholder="请输入联系方式" class="input-text search-input" value="@if(isset($search['tel']) && $search['tel']){{$search['tel']}}@endif">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius open-iframe"  data-url="{{url('manage/addUser')}}" data-title="添加用户" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加用户</a>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
            <tr class="text-c">
                <th width="60">序号</th>
                <th width="100" class="hidden-xs">单位名称</th>
                <th width="60" class="hidden-xs">职位名称</th>
                <th width="60" class="hidden-xs">姓名</th>
                <th width="60">登录名</th>
                <th width="60">角色</th>
                <th width="60" class="hidden-xs">审核权限</th>
                <th width="60" class="hidden-xs">状态</th>
                <th width="100">操作</th>
            </tr>
        </thead>
        <tbody>
            @if($data)
            @foreach($data as $value)
            <tr class="text-c" id="list-{{$value['id']}}">
                <td>{{$page_num++}}</td>
                <td class="hidden-xs">{{$value['company_name']}}</td>
                <td class="hidden-xs">{{$value['position_name']}}</td>
                <td class="hidden-xs">{{$value['name']}}</td>
                <td>{{$value['username']}}</td>
                <td>
                    {{$value['display_name']}}
                </td>
                <td class="hidden-xs td-status"><span class="label @if($value['has_sh'] == 1) label-success @else label-danger @endif radius">{{$has_sh[$value['has_sh']]}}</span></td>
                <td class="hidden-xs"><span class="label @if($value['status'] == 1) label-success @else label-danger @endif radius">{{$status[$value['status']]}}</span></td>
                <td class="f-14 product-brand-manage td-manage">
                    @if($user_is_act)
                    <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/admin/'.$value['id'].'/edit')}}" class="mt-5 edit-r btn btn-secondary radius size-MINI" href="javascript:;" data-type="edit" data-title="修改操作员" title="修改"><i class="Hui-iconfont">&#xe6df;</i></a> 
                    <a style="text-decoration:none" class="mt-5 ml-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/admin/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                    <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/admin/'.$value['id'].'/edit')}}" class="mt-5 ml-5 edit-r btn btn-success radius size-MINI" href="javascript:;" data-type="pass" data-title="修改操作员密码" title="修改密码"><i class="Hui-iconfont">&#xe63f;</i></a> 
                    <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/admin/'.$value['id'])}}" class="mt-5 ml-5 status-r btn btn-success radius size-MINI" href="javascript:;" data-type="has_sh" data-title="禁止审核权限,开放审核权限" data-span="没有,有" data-status="{{$value['has_sh']}}" @if($value['has_sh']==1) title="禁止审核权限"><i class="Hui-iconfont">&#xe631;</i>@else title="开放审核权限"><i class="Hui-iconfont">&#xe615;</i> @endif</a>
                    <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="设置权限" data-url="{{url('manage/user_mod?u_id='.$value['id'])}}" title="子系统权限">权限</a>
                    @endif
                    <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-is-reload="1" data-title="审核用户" data-url="{{url('manage/user_info?u_id='.$value['id'])}}" title="审核用户">审核</a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    @if($last_page > 1)
    @include('admin.layouts.page')
    @endif
</div>
@stop

@section('layer')
@include('admin.admin.admin_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/password.js"></script>
<script type="text/javascript">
/*$('#pro_id').select2().val({{$search['pro_id']}});*/

list.init();

$('.skin-minimal input').iCheck({
    checkboxClass: 'icheckbox-blue',
    radioClass: 'iradio-blue',
    increaseArea: '20%'
});
new PasswordStrength('password', 'passStrength');
</script>
@stop