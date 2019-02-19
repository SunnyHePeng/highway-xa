@extends('admin.layouts.iframe')

@section('container')
    <article class="page-container">
        <form class="form form-horizontal" id="form_container" data-url="{{url('stat/people_device_day_add')}}">
            {{--13标--}}
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><h3 class="c-333">LJ-13合同段：</h3></label>
            </div>
            {{--13标左洞--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标左洞人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标左洞人数，只需填入数字。" id="l_people_13" name="l_people_13" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标左洞施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标左洞施工时长，只需填入数字。" id="l_construction_duration_13" name="l_construction_duration_13" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标左洞备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入13标左洞备注" id="l_remark_13" name="l_remark_13"></textarea>
                </div>
            </div>
            {{--13标右洞--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标右洞人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标右洞人数，只需填入数字。" id="r_people_13" name="r_people_13" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标右洞施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标右洞施工时长，只需填入数字。" id="r_construction_duration_13" name="r_construction_duration_13" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标右洞备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入13标右洞备注" id="r_remark_13" name="r_remark_13"></textarea>
                </div>
            </div>
            {{--13标钢筋加工厂--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标钢筋加工厂人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标钢筋加工厂人数，只需填入数字。" id="reinforcement_yard_13" name="reinforcement_yard_13" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标钢筋加工厂施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入13标钢筋加工厂施工时长，只需填入数字。" id="reinforcement_yard_construction_duration_13" name="reinforcement_yard_construction_duration_13" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标钢筋加工厂备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入13标钢筋加工厂备注" id="reinforcement_yard_remark_13" name="reinforcement_yard_remark_13"></textarea>
                </div>
            </div>
            {{--13标路基施工情况--}}
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>13标路基施工情况：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入挖方段路基设备" id="roadbed_construction_13" name="roadbed_construction_13"></textarea>
                </div>
            </div>

            {{--14标--}}
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><h3 class="c-333">LJ-14合同段：</h3></label>
            </div>
            {{--14标左洞--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标左洞人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标左洞人数，只需填入数字。" id="l_people_14" name="l_people_14" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标左洞施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标左洞施工时长，只需填入数字。" id="l_construction_duration_14" name="l_construction_duration_14" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标左洞备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入14标左洞备注" id="l_remark_14" name="l_remark_14"></textarea>
                </div>
            </div>
             {{--14标右洞--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标右洞人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标右洞人数，只需填入数字。" id="r_people_14" name="r_people_14" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标右洞施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标右洞施工时长，只需填入数字。" id="r_construction_duration_14" name="r_construction_duration_14" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标右洞备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入14标右洞备注" id="r_remark_14" name="r_remark_14"></textarea>
                </div>
            </div>
             {{--14标钢筋加工厂--}}
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标钢筋加工厂人数(人)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标钢筋加工厂人数，只需填入数字。" id="reinforcement_yard_14" name="reinforcement_yard_14" required>
                </div>
            </div>
            <div class="row cl show-all">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标钢筋加工厂施工时长(小时)：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="text" class="input-text" value="" placeholder="请输入14标钢筋加工厂施工时长，只需填入数字。" id="reinforcement_yard_construction_duration_14" name="reinforcement_yard_construction_duration_14" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标钢筋加工厂备注：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入14标钢筋加工厂备注" id="reinforcement_yard_remark_14" name="reinforcement_yard_remark_14"></textarea>
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>14标路基施工情况：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <textarea class="textarea" placeholder="输入挖方段路基设备" id="roadbed_construction_14" name="roadbed_construction_14"></textarea>
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"></label>
                <div class="formControls col-xs-8 col-sm-9">
                    <button class="btn btn-primary radius sub" type="submit">提 交</button>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/plupload-2.1.9/js/plupload.full.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/upload.js"></script>
    <script type="text/javascript">
        list.init();

        $('.sub').on('click',function(e){
            e.preventDefault();
            layer.confirm('确定提交吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                var data=$("#form_container").serialize();

//                   layer.alert(data);
                var url=$("#form_container").attr('data-url');
                $.post(url,data,function(data){
                    if(data.status==1){
                        layer.alert(data.info);
                    }
                    if(data.status==0){
                        layer.msg(data.info, {
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

    </script>
@stop