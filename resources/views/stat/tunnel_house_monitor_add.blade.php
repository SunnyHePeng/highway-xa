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
    <form class="form form-horizontal " id="form_container" data-url="{{url('stat/tunnel_house_monitor_add')}}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><h3 class="c-333">1-5#测点</h3></label>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>1#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入1#测点本次实测高程" id="station1" name="station1">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">1#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station1_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>2#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入2#测点本次实测高程" id="station2" name="station2">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">2#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station2_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>3#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入3#测点本次实测高程" id="station3" name="station3">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">3#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station3_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>4#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入4#测点本次实测高程" id="station4" name="station4">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">4#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station4_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>5#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入5#测点本次实测高程" id="station5" name="station5">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">5#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station5_remark"></textarea>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><h3 class="c-333">6-10#测点</h3></label>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>6#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入6#测点本次实测高程" id="station6" name="station6">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">6#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station6_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>7#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入7#测点本次实测高程" id="station7" name="station7">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">7#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station7_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>8#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入8#测点本次实测高程" id="station8" name="station8">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">8#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station8_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>9#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入9#测点本次实测高程" id="station9" name="station9">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">9#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station9_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>10#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入10#测点本次实测高程" id="station10" name="station10">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">10#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station10_remark"></textarea>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><h3 class="c-333">11-15#测点</h3></label>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>11#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入11#测点本次实测高程" id="station11" name="station11">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">11#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station11_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>12#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入12#测点本次实测高程" id="station12" name="station12">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">12#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station12_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>13#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入13#测点本次实测高程" id="station13" name="station13">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">13#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station13_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>14#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入14#测点本次实测高程" id="station14" name="station14">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">14#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station14_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>15#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入15#测点本次实测高程" id="station15" name="station15">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">15#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station15_remark"></textarea>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-4"><h3 class="c-333">16-21#测点</h3></label>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>16#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入16#测点本次实测高程" id="station16" name="station16">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">16#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station16_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>17#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入17#测点本次实测高程" id="station17" name="station17">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">17#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station17_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">18#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入18#测点本次实测高程" id="station18" name="station18">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">18#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station18_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>19#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入19#测点本次实测高程" id="station19" name="station19">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">19#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station19_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>20#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入20#测点本次实测高程" id="station20" name="station20">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">20#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station20_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>21#测点本次实测高程：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="" placeholder="请输入21#测点本次实测高程" id="station21" name="station21">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4">21#测点备注：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入备注"  name="station21_remark"></textarea>
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-4 col-sm-4"><span class="c-red">*</span>观测结论：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea class="textarea" placeholder="输入观测结论"  name="conclusion"></textarea>
            </div>
        </div>

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
            var status=1;

            $('.sub').on('click',function(){

                if(status==1){
                    $("#form_container").submit(function(e){
                        e.preventDefault();
                    });
                    var data=$("#form_container").serialize();
//                    alert($(".type_select option:selected").text());
                    data.name=$(".type_select option:selected").text();
                    var url=$("#form_container").attr('data-url');
                    status=2;
                    $.post(url,data,function(data){
                        if(data.status==0){

                            layer.msg(data.mess, {
                                icon: 1,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){

                                parent.parent.location.reload();
                            });

                        }
                        if(data.status==1){
                            layer.alert(data.mess);
                            status=1;
                        }
                    });

                }else{
                  layer.alert('正在提交');

                }

            });


        });

    </script>
@stop