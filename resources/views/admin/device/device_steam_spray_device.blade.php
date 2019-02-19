@extends('admin.layouts.tree')

@section('container')
    @if($user_act)
        <div class="cl pd-5 bg-1 bk-gray mt-20">
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="project" data-title="添加设备" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加设备</a>
	</span>
        </div>
    @endif
    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="50">序号</th>
                <th width="100">项目名称</th>
                <th width="100">监理名称</th>
                <th width="100">合同段名称</th>
                <th width="100">梁场名称</th>
                <th width="100">设备名称</th>
                <th width="100">设备型号</th>
                <th width="100">设备编号</th>
                <th width="100">生产厂家</th>
                <th width="100">生产日期</th>
                <th width="100">摄像头1</th>
                <th width="100">摄像头2</th>
                <th width="100">增压泵额定功率(KW)</th>
                <th width="100">增压泵扬程(M)</th>
                <th width="100">最大养生预制梁数量(个)</th>
                <th width="100">最大养生时间(D)</th>
                <th width="100">负责人</th>
                <th width="100">联系方式</th>
                @if($user_is_act)
                    <th width="80">操作</th>
                @endif
            </tr>
            </thead>
            <tbody>
              @if($device_data)
                 @foreach($device_data as$k=> $v)
                    <tr class="text-c">
                       <td>{{$k+1}}</td>
                       <td>{{$v['project']['name']}}</td>
                       <td>{{$v['sup']['name']}}</td>
                       <td>{{$v['section']['name']}}</td>
                       <td>{{$v['beam_site']['name']}}</td>
                       <td>{{$v['name']}}</td>
                       <td>{{$v['model']}}</td>
                       <td>{{$v['dcode']}}</td>
                       <td>{{$v['factory_name']}}</td>
                       <td>{{$v['factory_date']}}</td>
                       <td>{{$v['camera1']}}</td>
                       <td>{{$v['camera2']}}</td>
                       <td>{{$v['parame1']}}</td>
                       <td>{{$v['parame2']}}</td>
                       <td>{{$v['parame3']}}</td>
                       <td>{{$v['parame4']}}</td>
                       <td>{{$v['fzr']}}</td>
                       <td>{{$v['phone']}}</td>
                        @if($user_is_act)
                            <td class="f-14 td-manage">
                                <a style="text-decoration:none" class="mt-5 steam_spray_device_edit btn btn-secondary radius size-MINI" data-for="section" data-title="编辑设备" data-url="{{url('manage/steam_spray_device_edit?id='.$v['id'])}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
                                <a style="text-decoration:none" class="mt-5 ml-5 device-del btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$v['id']}}" data-url="{{url('manage/steam_spray_device_del/'.$v['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                            </td>
                        @endif
                    </tr>
                 @endforeach
              @endif
            </tbody>
        </table>

    </div>
@stop

@section('layer')
    @include('admin.device.device_steam_spray_add')
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript">
        list.init();
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
                }

            });
        });
        $(".steam_spray_device_edit").on('click',function(){
//    alert(0);
            var url=$(this).attr("data-url");
            var title=$(this).attr("data-title");

            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                shade: 0.3,
                area: ['30%', '60%'],
                content: url
            });


        });
        $(".device-del").on('click',function(){

            var url=$(this).attr('data-url');
            layer.confirm('确定要删除吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.get(url,function(data){
                    if(data.status==1){
                        layer.alert(data.info);
                        var id='data-id='+data.id;

                        $("tr ["+id+"]").parent().parent().remove();
                    }
                    if(data.status==0){
                        layer.alert(data.info);
                    }

                });

            }, function(){

            });
        });
    </script>
@stop