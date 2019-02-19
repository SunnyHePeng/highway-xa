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
    <form class="form form-horizontal " id="form_container" data-url="{{url('stat/sd_set_add')}}">
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>合同段：</label>
            <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="section_id" name="section_id" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="19">LJ-13</option>
                    <option value="20">LJ-14</option>
                </select>
            </span>
            </div>
        </div>
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>位置：</label>
            <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select site_select" id="site" name="site" placeholder="请选择位置">
                    <option value="3">请选择</option>
                    <option value="0">土方开挖</option>
                    <option value="1">左洞</option>
                    <option value="2">右洞</option>
                </select>
            </span>
            </div>
        </div>
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目：</label>
            <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select type_select" id="type" name="type" placeholder="请选择项目">
                    <option value="0">请选择</option>
                    <option value="tfkw">土方开挖</option>
                    <option value="adjj">暗洞掘进</option>
                    <option value="cqzh">初期支护</option>
                    <option value="ygkw">仰拱开挖</option>
                    <option value="ygkw">仰拱浇筑</option>
                    <option value="fsbpg">防水板铺挂</option>
                    <option value="ecjz">二衬浇筑</option>
                </select>
            </span>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">总量：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="" placeholder="请输入总量" id="zl" name="zl">
            </div>
        </div>
        <input type="hidden" name="type_name" value="" class="type_name">
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
        $('#project_id').select2();
        list.init();
        $(function(){
            $('.sub').on('click',function(){
                $("#form_container").submit(function(e){
                    e.preventDefault();
                });
                var data=$("#form_container").serialize();
//                    alert($(".type_select option:selected").text());
                data.name=$(".type_select option:selected").text();
                var url=$("#form_container").attr('data-url');
                $.post(url,data,function(data){
                    if(data.status==0){
                        layer.alert(data.mess);
                    }
                    if(data.status==1){
                        layer.alert(data.mess);
                    }
                });
            });
            $('.site_select').on('change',function(){

               if($(".site_select option:selected").val()==0){

                   $('.type_select').empty().append('<option value="0">请选择</option><option value="tfkw">土方开挖</option>');
               }
            });
            $('.type_select').on('change',function(){
               $('.type_name').attr('value',$(".type_select option:selected").text());
            });

        });

    </script>
@stop