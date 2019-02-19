@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">

<div class="text-c">
  <form id="search_form" data="pro_id" method="get" data-url="{{url('manage/pda')}}">
      建设项目
      <span class="select-box inline">
        <select name="pro_id" id="pro_id" class="select select2">
            @if(isset($project))
            @foreach($project as $k=>$v)
            <option value="{{$v['id']}}" @if(isset($search['pro_id']) && $search['pro_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
            @endforeach
            @endif
        </select>
      </span>
      <input type="text" name="name" id="name" placeholder="请输入姓名" class="input-text search-input" value="@if(isset($search['name']) && $search['name']){{$search['name']}}@endif">
      <input type="text" name="tel" id="tel" placeholder="请输入联系方式" class="input-text search-input" value="@if(isset($search['tel']) && $search['tel']){{$search['tel']}}@endif">
      <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
  </form>
</div>
@if($user_is_act)
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
  <span class="l">
    <a class="btn btn-primary radius add-r" data-for="admin" data-title="添加PDA用户" data-url="{{url('manage/pda/create')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加PDA用户</a>
  </span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th width="70">序号</th>
        <th width="100" class="hidden-xs">单位名称</th>
        <th width="100" class="hidden-xs">用户名称</th>
        <th width="100">登录账号</th>
        <th width="100">联系方式</th>
        <th width="100">手机型号</th>
        <th width="100" class="hidden-xs">手机系统</th>
        <th width="100" class="hidden-xs">手机像素</th>
        <th width="100" class="hidden-xs">状态</th>
        @if($user_is_act)
        <th width="150">操作</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
        <tr class="text-c" id="list-{{$value['id']}}">
          <td>{{$page_num++}}</td>
          <td class="hidden-xs">{{$value['company']}}</td>
          <td class="hidden-xs">{{$value['name']}}</td>
          <td>{{$value['username']}}</td>
          <td>{{$value['phone']}}</td>
          <td>{{$value['phone_model']}}</td>
          <td class="hidden-xs">{{$value['phone_system']}}</td>
          <td class="hidden-xs">{{$value['phone_px']}}</td>
          <td class="hidden-xs td-status"><span class="label @if($value['status'] == 1) label-success @else label-danger @endif radius">{{$status[$value['status']]}}</span></td>
          @if($user_is_act)
          <td class="f-14 product-brand-manage td-manage">
            <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/pda/'.$value['id'].'/edit')}}" class="mt-5 edit-r btn btn-secondary radius size-MINI" href="javascript:;" data-type="edit" data-title="修改PDA用户" title="修改"><i class="Hui-iconfont">&#xe6df;</i></a> 
            <a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/pda/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
            <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/pda/'.$value['id'].'/edit')}}" class="mt-5 edit-r btn btn-success radius size-MINI" href="javascript:;" data-type="pass" data-title="修改PDA用户密码" title="修改密码"><i class="Hui-iconfont">&#xe63f;</i></a> 
            <a style="text-decoration:none" data-for="admin" data-url="{{url('manage/pda/'.$value['id'])}}" class="mt-5 status-r btn btn-success radius size-MINI" href="javascript:;" data-type="status" data-value="禁止,正常" data-status="{{$value['status']}}" @if($value['status']==1) title="改状态为禁止"><i class="Hui-iconfont">&#xe631;</i>@else title="改状态为正常"><i class="Hui-iconfont">&#xe615;</i> @endif</a>
          </td>
          @endif
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
@include('admin.admin.pda_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/password.js"></script>
<script type="text/javascript">
$('#pro_id').select2().val({{$search['pro_id']}});

list.init();

$('.skin-minimal input').iCheck({
  checkboxClass: 'icheckbox-blue',
  radioClass: 'iradio-blue',
  increaseArea: '20%'
});
new PasswordStrength('password','passStrength');
</script>
@stop