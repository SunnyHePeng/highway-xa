@extends('admin.layouts.default')

@section('container')
<style type="text/css">
#passstr_div {display: none;}
#passStrength{height:8px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
.strengthLv0{background:red;height:8px;width:10%;}
.strengthLv1{background:red;height:8px;width:40%;}
.strengthLv2{background:orange;height:8px;width:70%;}
.strengthLv3{background:green;height:8px;width:100%;}
#cp_div {display: none;}
</style>
<div class="main">
  <table id="list" class="admin mdl-data-table mdl-js-data-table">
    <thead>
      <tr>
        <th class="td2 not_phone">ID</th>
        <th class="td2">用户名</th>
        <th class="td2 not_phone">所属组</th>
        <th class="td2">状态</th>
        <th class="td2">最后登录时间</th>
        <th class="td2 not_phone">创建时间</th>
        <th class="td2" style="width:280px;">操作</th>
      </tr>
    </thead>
    <tbody>
      @foreach($list as $value)
      <tr id="tr_{{ $value['id'] }}">
        <td class="td2 not_phone">{{ $value['id'] }}</td>
        <td class="td2">{{ $value['name'] }}</td>
        <td class="td2 not_phone">{{ $group[$value['role']] }}</td>
        <td class="td2"><span @if($value['status'] == 0) class="red-font" @endif>{{ $status[$value['status']] }}</span></td>
        <td class="td2">{{ date('Y-m-d H:i',$value['updated_at']) }}</td>
        <td class="td2 not_phone">{{ date('Y-m-d H:i',$value['created_at']) }}</td>
        <td class="td2 act-admin" style="width:280px;">
          <a data-url="{{ url('manage/admin/'.$value['id'].'/edit') }}" class="list_edit pass" data-val='pass' href="javascript:;" title="修改密码">修改密码</a>
          @if($role == 1)
          <a data-url="{{ url('manage/admin/'.$value['id'].'/edit') }}" class="list_edit group" data-val='role' href="javascript:;" title="修改组">修改组</a>
          <a data-url="{{ url('manage/admin/'.$value['id']) }}" class="list_edit @if($value['status'] == 0) red-font allow @else deny @endif" data-val='status' data-status="{{ $value['status'] }}" href="javascript:;" title="@if($value['status'] == 1) 禁止 @else 启用 @endif">禁止</a>
          <a data-url="{{ url('manage/admin/'.$value['id']) }}" class="list_del" href="javascript:;" title="删除">删除</a>
          <a href="{{ url('manage/admin?uid='.$value['id']) }}" class="history" title="登陆记录">登陆记录</a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div id="myModal" class="reveal-modal">
  <a class="close-reveal-modal">&#215;</a>
  <form id="form">
    <div>
      <span class="span-name">用户名</span>
      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field">
        <input class="mdl-textfield__input msg_must" type="text" id="name" name="name" placeholder="请输入用户名"/>
        <label class="mdl-textfield__label" for="sample1"></label>
      </div>
    </div>
    <div id="pass_div">
      <span class="span-name">密码</span>
      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field">
          <input class="mdl-textfield__input msg_must" type="text" id="password" name="password" placeholder="请输入密码"/>
          <label class="mdl-textfield__label" for="sample1"></label>
      </div>
    </div>
    <div id="passstr_div">
      <span class="span-name">密码强度</span>
      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field">
          <div id="passStrength"></div>
      </div>
    </div>
    <div id="role_div">
      <span class="span-name">所属组</span>
      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field">
          <select class="mdl-textfield__input msg_must" id="role" name="role" placeholder="请选择所属组">
            <option value="0">请选择</option>
            @foreach($group as $key=>$val )
            <option value="{{ $key }}">{{ $val }}</option>
            @endforeach
          </select>
          <label class="mdl-textfield__label" for="sample1"></label>
      </div>
    </div>
    <span id="formsubmit" data-store-url="{{ url('manage/admin')}}" data-update-url="{{ url('manage/admin')}}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent submit">
      提  交
    </span>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" id="id" name="id" value=""/>
  </form>
</div>
<script type="text/javascript">
  var edit_data = ['id','role','name'];
</script>
@stop

@section('script')
<script type="text/javascript" src="/admin/js/password.js"></script>
<script type="text/javascript">
  $(function(){
    new PasswordStrength('password','passStrength');
  
    $('.close-reveal-modal').on('click',function(){
      setTimeout(function(){
        $('#id').val('');
        $('#pass_div').show();
        $('#passstr_div').show();
        $('#role_div').show();
        $('#role').val(0);
        $('input[type="checkbox"]').attr('checked',false);
        $('#passstr_div').hide();
      },1000);
    });

  });
</script>
@stop