@extends('admin.layouts.iframe')

@section('container')
    <article class="page-container">
        <form class="form form-horizontal" id="form_container" data-url="{{url('remove/remove_total_add')}}">

            <div class="row cl show-all text-c">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>监理：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select" id="supervision_id" name="supervision_id" placeholder="请选择" data-url="{{url('remove/get_section_by_supervision')}}">
                    <option value="0">请选择</option>
                    @if($supervision_data)
                      @foreach($supervision_data as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                      @endforeach
                    @endif
                </select>
            </span>
                </div>
            </div>
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>合同段：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select" id="section_id" name="section_id" placeholder="请选择">
                    <option value="0">请选择</option>

                </select>
            </span>
                </div>
            </div>
            <div class="row cl show-all text-c">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>所属区县：</label>
                <div class="formControls col-xs-3 ">
            <span class="select-box">
                <select class="select" id="district_name" name="district_name" placeholder="请选择">
                    <option value="0">请选择</option>
                    <option value="高新区">高新区</option>
                    <option value="长安区">长安区</option>
                    <option value="蓝田县">蓝田县</option>
                </select>
            </span>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>所属乡镇：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入所属乡镇" id="town_name" name="town_name" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>里程桩号：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入里程桩号" id="mileage_stake" name="mileage_stake" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>长度(KM)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入长度" id="length" name="length" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>用地总占地(亩)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入用地总占地" id="occupation_total" name="occupation_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>拆迁房屋总数(户)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入拆迁房屋总数" id="house_total" name="house_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>铁塔总数(座)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入铁塔总数" id="pylon_total" name="pylon_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>双杆总数(处)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入双杆总数" id="parallels_total" name="parallels_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>地埋光缆总量(米)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入地埋光缆总量" id="optical_cable_total" name="optical_cable_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>输水管道总量(米)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入输水管道总量" id="water_pipe_total" name="water_pipe_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>天然气管道总量：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入天然气管道总量" id="natural_gas_pipeline_total" name="natural_gas_pipeline_total" required>
                </div>
            </div>

            <div class="row cl show-all">
                <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>特殊拆除物总量(处)：</label>
                <div class="formControls col-xs-5 col-sm-5">
                    <input type="text" class="input-text" value="" placeholder="请输入特殊拆除物总量" id="special_remove_total" name="special_remove_total" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"></label>
                <div class="formControls col-xs-3 col-sm-3">
                    <button class="btn btn-primary radius sub" type="submit">确 定</button>
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
        $('#supervision_id').on('change',function(){
              var id=$(this).val();
              var url=$(this).attr("data-url");
              url=url+'?supervision_id='+id;
              $.get(url,function(data){
                  if(data.status==1){
                      layer.alert(data.info);
                  }
                  if(data.status==0){
                      var htmlstr='<option value="0">'+'请选择合同段'+'</option>';

                      for(var i=0;i<data.data.length;i++){
                          htmlstr=htmlstr+'<option value='+data.data[i].id+'>'+data.data[i].name+'</option>';
                      }
                      $("#section_id").empty().append(htmlstr);
                  }
              });
        });

        $('.sub').on('click',function(){
            $("#form_container").submit(function(e){
                e.preventDefault();
            });
            var data=$("#form_container").serialize();

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
        });

    </script>
@stop