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
    @if($data)
    <form class="form form-horizontal " id="form_container" data-url="{{url('stat/tunnel_house_init_edit')}}">
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">1#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_1}}" placeholder="请输入1#测点初始高程" id="station_init_1" name="station_init_1">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">2#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_2}}" placeholder="请输入2#测点初始高程" id="station_init_2" name="station_init_2">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">3#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_3}}" placeholder="请输入3#测点初始高程" id="station_init_3" name="station_init_3">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">4#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_4}}" placeholder="请输入4#测点初始高程" id="station_init_4" name="station_init_4">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">5#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_5}}" placeholder="请输入5#测点初始高程" id="station_init_5" name="station_init_5">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">6#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_6}}" placeholder="请输入6#测点初始高程" id="station_init_6" name="station_init_6">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">7#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_7}}" placeholder="请输入7#测点初始高程" id="station_init_7" name="station_init_7">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">8#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_8}}" placeholder="请输入8#测点初始高程" id="station_init_8" name="station_init_8">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">9#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_9}}" placeholder="请输入9#测点初始高程" id="station_init_9" name="station_init_9">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">10#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_10}}" placeholder="请输入10#测点初始高程" id="station_init_10" name="station_init_10">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">11#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_11}}" placeholder="请输入11#测点初始高程" id="station_init_11" name="station_init_11">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">12#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_12}}" placeholder="请输入12#测点初始高程" id="station_init_12" name="station_init_12">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">13#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_13}}" placeholder="请输入13#测点初始高程" id="station_init_13" name="station_init_13">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">14#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_14}}" placeholder="请输入14#测点初始高程" id="station_init_14" name="station_init_14">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">15#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_15}}" placeholder="请输入15#测点初始高程" id="station_init_15" name="station_init_15">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">16#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_16}}" placeholder="请输入16#测点初始高程" id="station_init_16" name="station_init_16">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">17#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_17}}" placeholder="请输入17#测点初始高程" id="station_init_17" name="station_init_17">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">18#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_18}}" placeholder="请输入18#测点初始高程" id="station_init_18" name="station_init_18">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">19#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_19}}" placeholder="请输入19#测点初始高程" id="station_init_19" name="station_init_19">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">20#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_20}}" placeholder="请输入20#测点初始高程" id="station_init_20" name="station_init_20">
            </div>
        </div>
        <div class="row cl show-all">
            <label class="form-label col-xs-3 col-sm-3">21#测点初始高程：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="{{$data->station_init_21}}" placeholder="请输入21#测点初始高程" id="station_init_21" name="station_init_21">
            </div>
        </div>

        <input type="hidden" name="supervision_id" value="7">
        <input type="hidden" name="section_id" value="19">
        <input type="hidden" name="id" value="{{$data->id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <br><br>
        <input class="btn btn-primary radius login-button sub" readonly="readonly" value="提交">
    </form>
    @else
        <span >暂时还未添加数据</span>
    @endif
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


        });

    </script>
@stop