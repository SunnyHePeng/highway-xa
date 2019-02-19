@extends('admin.layouts.iframe')

@section('container')
    <style>
        #passstr_div {display: none;}
        #passStrength{height:8px;width:320px;border:1px solid #ccc;padding:1px 0;margin-bottom: -20px;}
        .strengthLv0{background:red;height:8px;width:10%;}
        .strengthLv1{background:red;height:8px;width:40%;}
        .strengthLv2{background:orange;height:8px;width:70%;}
        .strengthLv3{background:green;height:8px;width:100%;}
        .sub{
            width:60px;
            height: 30px;
            margin-left: 20%;
        }
    </style>
    <link rel="stylesheet" href="/lib/select2/css/select2.min.css">
    <form class="form form-horizontal" id="form_container" data-url="{{url('manage/beam_yard_device_edit'.'/'.$device_data->id)}}">
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目公司：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="project_id" id="project_id" placeholder="请选择项目公司" data-url="{{url('manage/get_sup_by_pro')}}">
						<option value="0">请选择项目公司</option>
                        @if($project_data)
                            @foreach($project_data as $v)
                                <option value="{{$v['id']}}" @if($v['id']==$device_data['project_id']) selected="selected"@endif>{{$v['name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="supervision_id" id="supervision_id" placeholder="请选择监理" data-url="{{url('manage/get_sec_by_sup')}}">
						<option value="">请选择</option>
                        @if($sup_data)
                            @foreach($sup_data as $v)
                                <option value="{{$v['id']}}" @if($v['id']==$device_data['supervision_id']) selected="selected"@endif>{{$v['name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>合同段：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="section_id" id="section_id" placeholder="请选择合同段" data-url="{{url('manage/get_beam_site_by_sec')}}">
						<option value="">请选择</option>
                        @if($sec_data)
                            @foreach($sec_data as $v)
                                <option value="{{$v['id']}}" @if($v['id']==$device_data['section_id']) selected="selected"@endif>{{$v['name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>梁场：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="beam_site_id" id="beam_site_id" placeholder="请选择梁场">
						<option value="">请选择</option>
                        @if($beam_site_data)
                            @foreach($beam_site_data as $v)
                                <option value="{{$v['id']}}" @if($v['id']==$device_data['beam_site_id']) selected="selected"@endif >{{$v['name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备名称：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['name']}}" placeholder="请输入设备名称" id="name" name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备编码：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text" value="{{$device_data['dcode']}}" placeholder="请输入设备编码" id="dcode" name="dcode">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备型号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['model']}}" placeholder="请输入设备型号" id="model" name="model">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>摄像头1编号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['camera1']}}" placeholder="请输入摄像头1编号" id="camera1" name="camera1">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>摄像头2编号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['camera2']}}" placeholder="请输入摄像头2编号" id="camera2" name="camera2">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>生产厂家：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['factory_name']}}" placeholder="请输入生产厂家" id="factory_name" name="factory_name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>出厂日期：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['factory_date']}}" placeholder="请输入出厂日期" id="factory_date" name="factory_date">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>额定功率(KW)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['parame1']}}" placeholder="请输入额定功率(kw)" id="parame1" name="parame1">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>负责人(D)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['fzr']}}" placeholder="请输入负责人" id="fzr" name="fzr">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="{{$device_data['phone']}}" placeholder="请输入负责人联系方式" id="phone" name="phone">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$device_data->id}}">
        <input type="hidden" name="cat_id" value="{{$device_data->cat_id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <br><br>
        <input class="btn btn-primary radius login-button sub"  value="提交">
    </form>
@stop

@section('script')
    <script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="/static/common/js/H-ui.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/static/admin/js/H-ui.admin.page.js"></script>
    <script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/plupload/2.3.1/js/plupload.full.min.js"></script>
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript">
        list.init();
        $(".sub").on('click',function(event){
            layer.confirm('确定数据填写无误并提交吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                event.preventDefault();
                var data=$("#form_container").serialize();
                var url=$("#form_container").attr('data-url');

                $.post(url,data,function(data){
                    if(data.status==0){
                        layer.alert(data.info);
                    }
                    if(data.status==1){
                        layer.msg(data.info, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            parent.parent.location.reload();
                        });
                    }
                })
            }, function(){

            });



        });
        $("#project_id").on('change',function(){
            var project_id=$(this).val();
            var url=$(this).attr('data-url');

            $.get(url+'?pro_id='+project_id,function(data){
                if(data.status==1){
                    layer.alert(data.mess);
                }
                if(data.status==0){
                    var htmlstr='<option>请选择监理</option>';
                    console.dir(data.mess);
                    for(var i=0;i<data.mess.length;i++){
                        htmlstr+="<option value=\""+data.mess[i].id+"\">"+data.mess[i].name+"</option>";
                    }
                    $("#supervision_id").empty().append(htmlstr);
                }
            });

        });
        $("#supervision_id").on('change',function(){
            var supervision_id=$(this).val();
            var url=$(this).attr("data-url");

            $.get(url+'?sup_id='+supervision_id,function(data){
                if(data.status==0){
                    var htmlstr='<option>请选择合同段</option>';
                    htmlstr+="<option value=\""+data.mess.id+"\">"+data.mess.name+"</option>";
                    $("#section_id").empty().append(htmlstr);
                }
            });
        });
        $("#section_id").on('change',function(){
//            alert(0);
            var section_id=$(this).val();
            var url=$(this).attr("data-url");

            $.get(url+'?sec_id='+section_id,function(data){
                if(data.status==0){
                    var htmlstr='<option>请选择梁场</option>';
                    for(var i=0;i<data.mess.length;i++){
                        htmlstr+="<option value=\""+data.mess[i].id+"\">"+data.mess[i].name+"</option>";
                    }
                    $("#beam_site_id").empty().append(htmlstr);
                }
                if(data.status==1){
                    layer.alert(data.mess);
                    var htmlstr='<option>请选择梁场</option>';
                    $("#beam_site_id").empty().append(htmlstr);
                }

            });
        });

    </script>

@stop