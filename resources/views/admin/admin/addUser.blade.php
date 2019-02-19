@extends('admin.layouts.iframe')

@section('container')
<style>
#passstr_div {display: none;}
#passStrength{height:8px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
.strengthLv0{background:red;height:8px;width:10%;}
.strengthLv1{background:red;height:8px;width:40%;}
.strengthLv2{background:orange;height:8px;width:70%;}
.strengthLv3{background:green;height:8px;width:100%;}
.sub{
    width:60px;
    height: 30px;
    margin-left: 20%;
} 
</style>
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">
<form class="form form-horizontal" id="form_container" data-url="{{url('manage/addUser')}}">
    <div class="row cl show-all hidden-pass">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>角色：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="role" name="role" placeholder="请选择角色" data-url="{{url('manage/get_pos')}}">
                    <option value="0">请选择</option>
                    @foreach($roles as $key=>$val )
                    <option value="{{ $val['id'] }}">{{ $val['display_name'] }}</option>
                    @endforeach
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>建设项目：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <!-- <span class="select-box"> -->
            <select class="select msg_must select2" id="project_id" name="project_id[]" placeholder="请先选择建设项目" data-url="{{url('manage/get_sup')}}" multiple="multiple">
                <option value="0">请选择项目</option>
                @if(isset($project))
                @foreach($project as $key=>$val )
                <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                @endforeach
                @endif
            </select>
            <!-- </span> -->
        </div>
    </div>
    <div class="row cl show-all hidden-jtyh">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>单位名称：</label>
        <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select company_select" id="company_id" name="company_id" placeholder="请先选择单位名称">
                    <option value="0">请选择单位名称</option>
                    @if(isset($company))
                    @foreach($company as $key=>$val )
                    <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                    @endforeach
                    @endif
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all hidden-jtyh">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>部门名称：</label>
        <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select department_select" id="department_id" name="department_id" placeholder="请先选择部门">
                    <option value="0">请选择部门</option>
                    @if(isset($department))
                        @foreach($department as $val )
                            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属职位：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="position_id" name="position_id" placeholder="请先选择所属职位">
                    <option value="0">请选择职位</option>
                    @if(isset($position))
                    @foreach($position as $key=>$val )
                    <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                    @endforeach
                    @endif
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>姓名：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <input type="text" class="input-text msg_must" value="" placeholder="请输入姓名" id="name" name="name" required>
        </div>
    </div>
    <div class="row cl show-all hidden-pass">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <input type="text" class="input-text msg_must" value="" placeholder="请输入联系方式" id="phone" name="phone" required>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>登录账号：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <input type="text" class="input-text msg_must" value="" placeholder="请输入登录账号" id="username" name="username" required>
        </div>
    </div>
    <div id="pass" class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>密码：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <input type="password" class="input-text msg_must" value="" placeholder="请输入密码" placeholder="" id="password" name="password" required>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3">身份证号：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <input type="text" class="input-text" value="" placeholder="身份证号" id="IDNumber" name="IDNumber">
        </div>
    </div>
    <div id="passstr_div" class="row cl">
        <label class="form-label col-xs-3 col-sm-3">密码强度：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <div id="passStrength"></div>
        </div>
    </div>
    <div class="row cl show-all hidden-pass hidden-xtgly">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理信息：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select" id="supervision_id" name="supervision_id" placeholder="请选择监理信息" data-url="{{url('manage/get_sec')}}">
                    <option value="0">请选择</option>
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all hidden-pass hidden-xtgly hidden-zjb">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段：</label>
        <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select" id="section_id" name="section_id" placeholder="请选择标段">
                    <option value="0">请选择</option>
                </select>
            </span>
        </div>
    </div>
    <div class="row cl show-all">
        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>子系统：</label>
        <div class="formControls skin-minimal col-xs-3 col-sm-5">
            @if($module)
            @foreach($module as $info)
            <div class="check-box">
                <input type="checkbox" name="mod_id[]" value="{{$info['id']}}">
                <label for="radio-2">{{$info['name']}}</label>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <!-- <div class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>短信验证码</label>
      <div class="formControls col-xs-5 col-sm-5" style="padding-right: 0;">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入短信验证码" id="verify" name="verify">
      </div>
      <div class="formControls col-xs-3 col-sm-3" style="padding-left: 0;">
        <span class="phone_yzm" data-url="{{url('pcode/'.time())}}">点击获取</span>
      </div>
    </div> -->
    <input type="hidden" value="" id="id" name="id">
    <input type="hidden" value="" id="act_type" name="act_type">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <br><br>
    <input class="btn btn-primary radius login-button sub" readonly="readonly"  value="提交">
</form>
@stop

@section('script')
<script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/common/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/js/H-ui.admin.page.js"></script> 
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/password.js"></script>
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript">
$('#project_id').select2();

list.init();

$('.skin-minimal input').iCheck({
    checkboxClass: 'icheckbox-blue',
    radioClass: 'iradio-blue',
    increaseArea: '20%'
});
new PasswordStrength('password', 'passStrength');

register.init();

var button_status=1;

$("#form_container .login-button").on('click', function () {


//    var data = $("#form_container").serialize();
//    common.doAjax($('#form_container'), 'POST');
     function doAjax(obj, type, data, index){
        var url = obj.attr('data-url');
        var is_reload = 1;
        if(data){		//修改排序，
            is_reload = 0;
        }else{
            var data = obj.serialize();
            var id = obj.find('#id').val();
            if(id){
                if(!type){
                    type = 'PUT';
                }
                url += '/' + id;
            }
        }
        //判断必填字段是否填写  $msg[i].value==0 ||
        var $msg = obj.find('.msg_must');
        for(var i=0;i<$msg.length;i++){
            console.info($($msg[i]).parent().parent().css('display'));
            if($($msg[i]).parent().parent().css('display') == 'block'){
                if($msg[i].value==0 ||$msg[i].value=='' || $msg[i].value==undefined || $msg[i].value==$msg[i].getAttribute('placeholder').toString()){
                    common.alert($msg[i].getAttribute('placeholder'));
                    list.is_click = true;
                    button_status=1;
                    return false;
                }
            }
        }
        //如果是admin  再次验证
        if(url.indexOf('manage/admin')>0 || url.indexOf('manage/register')>0){
            var role = obj.find('#role').val();
            if(role){
                if(!admin.checkData(role, obj)){
                    list.is_click = true;
                    button_status=1;
                    return false;
                }
            }
        }
        button_status=2;
        $.ajax({
            url: url,
            type: type,
            data: data,
            dataType: 'json',
            success:function(msg){
                if(msg.status){
                    if(is_reload){
                        if(index){
                            layer.close(index);
                            window.location.href=msg.url;
                        }else{
                            common.alert(msg.info, is_reload, msg.url);
                        }
                    }else{
                        common.message(msg.info);

                    }
                }else{
                    common.alert(msg.info);
                    button_status=1;
                    list.is_click = true;
                }
            },
            error: function(){
                common.alert('提交出错...');
                list.is_click = true;
            }
        })
    }
    if(button_status==1){
         layer.msg('好的,正在执行注册流程,请稍等');

        doAjax($('#form_container'), 'POST');

    }else{

        layer.alert('正在注册中，请稍后');
    }

});

</script>
@stop