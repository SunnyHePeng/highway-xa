@extends('admin.layouts.iframe')

@section('container')
    <article class="page-container">
        <form class="form form-horizontal" id="form_container" data-url="{{url('remove/remove_day_add')}}">

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>用地今日交付(亩)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入用地今日交付数据" id="occupation_day" name="occupation_day">
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>房屋今日拆迁(户)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入房屋今日拆迁数" id="house_day" name="house_day" >
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>铁塔今日迁改(座)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入铁塔今日迁改数" id="pylon_day" name="pylon_day">
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>双杆今日迁改(处)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入双杆今日迁改数" id="parallels_day" name="parallels_day">
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>地埋光缆今日迁改(米)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入地埋光缆今日迁改数" id="optical_cable_day" name="optical_cable_day" >
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>输水管道今日迁改(米)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入输水管道今日迁改数" id="water_pipe_day" name="water_pipe_day">
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>天然气管道今日迁改：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入天然气管道今日迁改" id="natural_gas_pipeline_day" name="natural_gas_pipeline_day">
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>特殊拆除物今日拆除(处)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入特殊拆除物今日拆除数" id="special_remove_day" name="special_remove_day">
                </div>
            </div>
            {{--备注--}}
            <div class="row cl dbxc_remark_r remark">
                <label class="form-label col-xs-2 col-sm-2">备注：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <textarea class="textarea" placeholder="如无备注信息，此项可不填" id="remark" name="remark"></textarea>
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="section_id" value="{{$section_id}}">
            <input type="hidden" name="supervision_id" value="{{$supervision_id}}">

            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"></label>
                <div class="formControls col-xs-3 col-sm-3">
                    <button class="btn btn-primary radius sub" type="submit">确 定</button>
                </div>
            </div>

        </form>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/plupload-2.1.9/js/plupload.full.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/upload.js"></script>
    <script type="text/javascript">
         var status=1;
        $('.sub').on('click',function(){
            if(status==1){
                status=2;
                $("#form_container").submit(function(e){
                    e.preventDefault();
                });
                var data=$("#form_container").serialize();

                var url=$("#form_container").attr('data-url');
                $.post(url,data,function(data){
                    if(data.status==1){
                        layer.alert(data.mess);
                    }
                    if(data.status==0){
                        layer.msg(data.mess, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            //do something
                            parent.parent.location.reload();
                        });
                    }
                });
            }else{
               layer.alert("正在提交中....");
            }

        });

    </script>
@stop