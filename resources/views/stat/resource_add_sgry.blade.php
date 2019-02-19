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
            <label class="form-label col-xs-3 col-sm-3">掌子面开挖施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入掌子面施工人员数量" id="zzmkw" name="zzmkw">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">初期支护施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入初期支护施工人员数量" id="cqzh" name="cqzh">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">仰拱开挖施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入仰拱开挖施工人员数量" id="ygkw" name="ygkw">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">仰拱浇筑施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入仰拱浇筑施工人员数量" id="ygjz" name="ygjz">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">防水板铺挂施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入防水板铺挂施工人员数量" id="fsbpg" name="fsbpg">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">二衬施工人员数量(人)：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入二衬施工人员数量" id="ecjz" name="ecjz">
            </div>
        </div>
        <input type="hidden" name="type" value="1">
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