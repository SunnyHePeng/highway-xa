@extends('admin.layouts.iframe')

@section('container')
    <style>
        .sub{
            width:60px;
            height: 30px;
            margin-left: 20%;
        }
    </style>
    <link rel="stylesheet" href="/lib/select2/css/select2.min.css">
    <form class="form form-horizontal " id="form_container" data-url="">
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>合同段：</label>
            <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="section_id" name="section_id" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="{{$section['id']}}" selected="selected">{{$section['name']}}</option>
                </select>
            </span>
            </div>
        </div>
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>位置：</label>
            <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select site_select" id="site" name="site" placeholder="请选择位置">
                    <option value="0">请选择</option>
                    <option value="1">左洞</option>
                    <option value="2">右洞</option>
                </select>
            </span>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">湿喷机械手(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入湿喷机械手数量" id="spjxs" name="spjxs" required>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">防水板铺挂台车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入防水板铺挂台车数量" id="fsbpgtc" name="fsbpgtc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">二衬台车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入二衬台车数量" id="ectc" name="ectc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">二衬养生台车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入二衬养生台车数量" id="ecystc" name="ecystc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">自行式整体栈桥液压仰拱模板台车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入自行式整体栈桥液压仰拱模板台车数量" id="ygmbtc" name="ygmbtc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">液压水沟电缆槽台车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入液压水沟电缆槽台车数量" id="sgdlctc" name="sgdlctc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">雾炮车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入雾炮车数量" id="wpc" name="wpc">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">挖掘机(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入挖掘机数量" id="wjj" name="wjj">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">装载机(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入装载机数量" id="zzj" name="zzj">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">自卸车(台)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入自卸车数量" id="zxc" name="zxc">
            </div>
        </div>
        <input type="hidden" name="type" value="2">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <br><br>
        <input class="btn btn-primary radius login-button sub" readonly="readonly" value="提交">
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
        list.init();
        $(function(){
            $('.sub').on('click',function(){

                layer.confirm('数据一旦提交之后不可更改,确定提交吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    var data=$("#form_container").serialize();

//                   layer.alert(data);
                    var url=$("#form_container").attr('data-url');
                    $.post(url,data,function(data){
                        if(data.status==0){
                            layer.alert(data.mess);
                        }
                        if(data.status==1){
                            layer.msg(data.mess, {
                                icon: 1,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){

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