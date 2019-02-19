<style type="text/css">
#passstr_div {display: none;}
#passStrength{height:8px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
.strengthLv0{background:red;height:8px;width:10%;}
.strengthLv1{background:red;height:8px;width:40%;}
.strengthLv2{background:orange;height:8px;width:70%;}
.strengthLv3{background:green;height:8px;width:100%;}
#cp_div {display: none;}
</style>
<article class="page-container" id="layer-edit">
  <form class="form form-horizontal" id="form_container" data-url="{{url('manage/pda')}}">
    <div class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>建设项目：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请先选择建设项目" id="project_name" name="project_name" required>
      </div>
    </div>
    <div class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>单位名称：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入单位名称" id="company" name="company" required>
      </div>
    </div>
    <div class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>姓名：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入姓名" id="name" name="name" required>
      </div>
    </div>
    <div class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>登录账号：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入登录账号" id="username" name="username" required>
      </div>
    </div>
    <div id="pass" class="row cl show-all">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>密码：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="password" class="input-text msg_must" value="" placeholder="请输入密码" placeholder="" id="password" name="password" required>
      </div>
    </div>
    <div id="passstr_div" class="row cl">
      <label class="form-label col-xs-3 col-sm-3">密码强度：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <div id="passStrength"></div>
      </div>
    </div>
    <div class="row cl show-all hidden-pass">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入联系方式" id="phone" name="phone" required>
      </div>
    </div>
    <div class="row cl show-all hidden-pass">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>手机型号：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入手机型号" id="phone_model" name="phone_model" required>
      </div>
    </div>
    <div class="row cl show-all hidden-pass">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>手机系统：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入手机系统" id="phone_system" name="phone_system" required>
      </div>
    </div>
    <div class="row cl show-all hidden-pass">
      <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>手机像素：</label>
      <div class="formControls col-xs-8 col-sm-8">
        <input type="text" class="input-text msg_must" value="" placeholder="请输入手机像素" id="phone_px" name="phone_px" required>
      </div>
    </div>
    <div id="status" class="row hidden-pass cl">
      <label class="form-label col-xs-3 col-sm-3">状态：</label>
      <div class="formControls skin-minimal col-xs-8 col-sm-8">
        <div class="radio-box">
          <input type="radio" id="status" name="status" value="1">
          <label>正常</label>
        </div>
        <div class="radio-box">
          <input type="radio" id="status" name="status" value="0">
          <label>禁止</label>
        </div>
      </div>
    </div>
    <input type="hidden" value="" id="id" name="id">
    <input type="hidden" value="" id="act_type" name="act_type">
    <input type="hidden" value="" id="project_id" name="project_id" placeholder="请先选择项目信息">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
  </form>
</article>