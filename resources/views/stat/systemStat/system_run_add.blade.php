@extends('admin.layouts.iframe')

@section('container')
    <article class="page-container">
        @if($status==0)
        <div class="cl pd-5 bg-1 bk-gray mt-20"> 
                <span class="l ml-10 pt-5 c-error">
                  数据填报时间为每天的下午4:00-5:00之间
                </span>
              </div>
        @else
        <form class="form form-horizontal" id="form_container" data-url="{{url('stat/system_run_add')}}">
                    {{--试验数据监控系统--}}
                    <div class="row cl show-all text-c">
                        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>试验数据监控系统：</label>
                        <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="lab_data_monitor_status" name="lab_data_monitor_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                        </div>
                    </div>
                    <div class="row cl remark lab_data_monitor_remark">
                        <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>试验数据监控系统备注(异常原因)：</label>
                        <div class="formControls col-xs-3 col-sm-3">
                            <textarea class="textarea" placeholder="输入异常原因" id="" name="lab_data_monitor_remark"></textarea>
                        </div>
                    </div>
            {{--拌和数据监控系统--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌和数据监控系统：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="blend_data_monitor_status" name="blend_data_monitor_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark blend_data_monitor_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌和数据监控系统备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="blend_data_monitor_remark"></textarea>
                </div>
            </div>
             {{--视频监控系统--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>视频监控系统：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="video_monitor_status" name="video_monitor_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark video_monitor_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>视频监控系统备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="video_monitor_remark"></textarea>
                </div>
            </div>
            {{--隧道定位系统--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>隧道定位系统：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="tunnel_location_status" name="tunnel_location_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark tunnel_location_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>隧道定位系统备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="tunnel_location_remark"></textarea>
                </div>
            </div>
            {{--高边坡监测系统--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>高边坡监测系统：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="high_side_monitor_status" name="high_side_monitor_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark high_side_monitor_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>高边坡监测系统备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="high_side_monitor_remark"></textarea>
                </div>
            </div>
            {{--电子档案管理系统--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>电子档案管理系统：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="electronic_recode_status" name="electronic_recode_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark electronic_recode_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>电子档案管理系统备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="electronic_recode_remark" name="electronic_recode_remark"></textarea>
                </div>
            </div>
            {{--试验数据报警--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>试验数据报警：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="lab_data_alarm_status" name="lab_data_alarm_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark lab_data_alarm_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>试验数据报警备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="lab_data_alarm_remark"></textarea>
                </div>
            </div>
            {{--拌和数据报警--}}
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌和数据报警：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select status" id="blend_data_alarm_status" name="blend_data_alarm_status" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="1">正常</option>
                    <option value="2">异常</option>
                </select>
            </span>
                </div>
            </div>
            <div class="row cl remark blend_data_alarm_remark">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌和数据报警备注(异常原因)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入异常原因" id="" name="blend_data_alarm_remark"></textarea>
                </div>
            </div>


            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"></label>
                <div class="formControls col-xs-8 col-sm-9">
                    <button class="btn btn-primary radius sub" type="submit">确 定</button>
                </div>
            </div>
            <input type="hidden" value="{{$type}}" name="type">
            <input type="hidden" value="{{$unit_id}}" id="sign" name="unit_id">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        @endif
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();
        $(function(){
            //隐藏备注栏
            $(".remark").hide();
            //下拉框改变，如果选为异常，显示备注输入框
                 //试验数据监控
            $("#lab_data_monitor_status").on('change',function(){
               if($(this).val()==2){
                   $(".lab_data_monitor_remark").show();
               }
               if($(this).val()==1||$(this).val()==0){
                   $(".lab_data_monitor_remark").hide();
               }
            });
                 //拌和数据监控
            $("#blend_data_monitor_status").on('change',function(){
                if($(this).val()==2){
                    $(".blend_data_monitor_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".blend_data_monitor_remark").hide();
                }
            });
                 //视频监控
            $("#video_monitor_status").on('change',function(){
                if($(this).val()==2){
                    $(".video_monitor_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".video_monitor_remark").hide();
                }
            });
                 //隧道定位
            $("#tunnel_location_status").on('change',function(){
                if($(this).val()==2){
                    $(".tunnel_location_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".tunnel_location_remark").hide();
                }
            });

              //高边坡监测
            $("#high_side_monitor_status").on('change',function(){
                if($(this).val()==2){
                    $(".high_side_monitor_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".high_side_monitor_remark").hide();
                }
            });

             //电子档案管理
            $("#electronic_recode_status").on('change',function(){
                if($(this).val()==2){
                    $(".electronic_recode_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".electronic_recode_remark").hide();
                }
            });
            //试验数据报警
            $("#lab_data_alarm_status").on('change',function(){
                if($(this).val()==2){
                    $(".lab_data_alarm_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".lab_data_alarm_remark").hide();
                }
            });
            //拌合数据报警
            $("#blend_data_alarm_status").on('change',function(){
                if($(this).val()==2){
                    $(".blend_data_alarm_remark").show();
                }
                if($(this).val()==1||$(this).val()==0){
                    $(".blend_data_alarm_remark").hide();
                }
            });


            //表单提交
            $(".sub").on('click',function(event){
                    //取消表单默认提交
                    event.preventDefault();
                    //收集表单数据
                    var data=$("#form_container").serialize();
                    var url=$("#form_container").attr("data-url");

                    $.post(url,data,function(data){
                        if(data.status==0){
                            //录入成功
                            layer.msg(data.mess, {
                                icon: 1,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){
                                //do something
                                parent.parent.location.reload();
                            });

                        }
                        if(data.status==1){
                            //录入失败
                            layer.alert(data.mess);

                        }

                    });

            });

        });




    </script>
@stop