@extends('admin.layouts.default')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/lib/select2/css/select2.min.css">
    <div>
        <form id="search_form" data="pro_id,sup_id,sec_id,dev_id,type,start_date,end_date" method="get" data-url="{{url('stretch/warn_info')}}">
                @if($role==1 || $role==2 || $role==3)
                <div class="row cl">
                    <span style="float:left;padding: 6px 10px;">选择项目</span>
                    <span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="pro_id[]" id="pro_id" multiple class="select select2">
		  			<option value="0">全部</option>
                    @if($project_data)
                        @foreach($project_data as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                        @endforeach
                    @endif
	            </select>
	        </span>
                </div>
                @endif

                @if($role != 5)
                <div class="row cl">
                    <span style="float:left;padding: 6px 10px;">选择监理</span>
                    <span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="sup_id[]" id="sup_id" multiple class="select select2">
		  			<option value="0">全部</option>
                    @if($supervision_data)
                      @foreach($supervision_data as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                      @endforeach
                    @endif
	            </select>
	        </span>
                </div>
                @endif

                <div class="row cl">
                    <span style="float:left;padding: 6px 10px;">选择标段</span>
                    <span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="sec_id[]" id="sec_id" multiple class="select select2">
		  			<option value="0" >全部</option>
                     @if($section_data)
                         @foreach($section_data as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                         @endforeach
                     @endif
	            </select>
	        </span>
                </div>

            <div class="row cl">
                <span style="padding: 6px 10px;">处理状态</span>
                <span class="select-box" style="width:auto; padding: 3px 5px;">
		  		<select name="type" id="type" class="select select2">
                   @if($type)
                      @foreach($type as $k=>$v)
                            <option value="{{$k}}" @if($k==$search['type']) selected="selected" @endif>{{$v}}</option>
                      @endforeach
                   @endif
	            </select>
	        </span>
                <span style="padding: 6px 10px;">张拉时间：</span>
                <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
                -
                <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            </div>
            <div class="row cl">
                <span style="float:left; padding: 6px 10px;">&emsp;&emsp;&emsp;&emsp;</span>
                <span class="col-sm-10" style="padding: 3px 5px;">
		        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    		</span>
            </div>
        </form>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="40" class="hidden-xs hidden-sm">序号</th>
                <th width="100" class="hidden-xs hidden-sm">监理名称</th>
                <th width="100">标段名称</th>
                <th width="80">所属梁场</th>
                <th width="100">设备名称</th>
                <th width="100">梁号</th>
                <th width="100">孔道名称</th>
                <th width="100">报警信息</th>
                <th width="150" class="hidden-xs">该孔张拉时间</th>
                <th width="100" class="hidden-xs">标段处理情况</th>
                <th width="100" class="hidden-xs">监理处理情况</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
               @if($data)
                 @foreach($data as $v)
                     <tr  class="text-c">
                         <td>{{$from++}}</td>
                         <td>{{$v->supervision_name}}</td>
                         <td>{{$v->section_name}}</td>
                         <td>{{$v->beam_site_name}}</td>
                         <td>{{$v->device_name}}</td>
                         <td>{{$v->girder_number}}</td>
                         <td>{{$v->pore_canal_name}}</td>
                         <td>{{$v->warn_info}}</td>
                         <td>{{date('Y-m-d H:i:s',$v->stretch_time)}}</td>
                         <td>
                             @if($v->is_sec_deal)
                                 <input class="btn btn-success radius size-MINI" type="button" value="已处理">
                             @else
                                 <input class="btn radius btn-danger size-MINI" type="button" value="未处理">
                             @endif
                         </td>
                         <td>
                             @if($v->is_sup_deal)
                                 <input class="btn btn-success radius size-MINI" type="button" value="已处理">
                             @else
                                 <input class="btn radius btn-danger size-MINI" type="button" value="未处理">
                             @endif
                         </td>
                         <td>
                             <input class="btn btn-success radius size-MINI mr-10 open-data" data-title="详细数据" data-url="{{url('stretch/detail_in_warn'.'/'.$v->detail_id)}}" type="button" value="详细数据">
                             <input class="btn btn-primary radius size-MINI open-data" type="button" data-title="处理报告" data-url="{{url('stretch/warn_deal_report'.'/'.$v->detail_id)}}" value="处理报告">
                         </td>
                     </tr>
                 @endforeach
               @endif
            </tbody>
        </table>
        @if($last_page>1)
            @include('admin.layouts.page')
        @endif
    </div>
    <input id="select_pro" value="{{$search['pro_id']}}" type="hidden">
    <input id="select_sup" value="{{$search['sup_id']}}" type="hidden">
    <input id="select_sec" value="{{$search['sec_id']}}" type="hidden">
@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.openIframe();

        $('#pro_id').select2().val(eval('('+$('#select_pro').val()+')')).trigger("change");
        $('#sup_id').select2().val(eval('('+$('#select_sup').val()+')')).trigger("change");
        $('#sec_id').select2().val(eval('('+$('#select_sec').val()+')')).trigger("change");
        $('#dev_id').select2().val(eval('('+$('#select_dev').val()+')')).trigger("change");

        var url = "{{url('stretch/get_select_val')}}";
        //项目改变 获取对应的监理 标段 设备
        $('#pro_id').on('change', function () {
            var pro_id = $(this).val();
            var data = {
                'pro_id': pro_id
            };
            setOption(url+'/pro', data, 'pro_id');
            if(pro_id.length > 1 && pro_id[0]==0){
                //去掉全部选项
                pro_id.splice(0,1);
                $('#pro_id').select2().val(eval('('+pro_id+')')).trigger("change");
            }
        });
        //监理改变 获取对应的 标段 设备
        $('#sup_id').on('change', function () {
            var sup_id = $(this).val();
            var data = {
                'sup_id': sup_id
            };
            setOption(url+'/sup', data, 'sup_id');
            if(sup_id.length > 1 && sup_id[0]==0){
                //去掉全部选项
                sup_id.splice(0,1);
                $('#sup_id').select2().val(eval('('+sup_id+')')).trigger("change");
            }
        });
        //标段改变 获取对应的  设备
        $('#sec_id').on('change', function () {
            var sec_id = $(this).val();
            var data = {
                'sec_id': sec_id
            };
            setOption(url+'/sec', data, 'sec_id');
            if(sec_id.length > 1 && sec_id[0]==0){
                //去掉全部选项
                sec_id.splice(0,1);
                $('#sec_id').select2().val(eval('('+sec_id+')')).trigger("change");
            }
        });
        $('#dev_id').on('change', function () {
            var dev_id = $(this).val();
            if(dev_id.length > 1 && dev_id[0]==0){
                //去掉全部选项
                dev_id.splice(0,1);
                $('#dev_id').select2().val(eval('('+dev_id+')')).trigger("change");
            }
        });
        function setOption(url, data, id){
            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                dataType: 'json',
                success:function(msg){
                    var str = '';
                    if(msg.status){
                        data = msg.data;
                        for(var i in data){
                            str = '<option value="0">全部</option>';
                            if(data[i]){
                                for(var j in data[i]){
                                    if(i == 'dev_id'){
                                        str += '<option value="'+data[i][j]['id']+'">'+data[i][j]['model']+data[i][j]['category']['name']+'</option>';
                                    }else{
                                        str += '<option value="'+data[i][j]['id']+'">'+data[i][j]['name']+'</option>';
                                    }

                                }
                            }
                            $('#'+i).html('').append(str);
                            $('#'+i).select2().val(0).trigger("change");
                        }
                    }
                },
                error: function(){
                    common.alert('获取信息出错...');
                }
            });
        }
      $(".open-data").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
          layer.open({
              type: 2,
              title: title,
              shadeClose: true,
              area: ['90%', '90%'],
              content: url,
          });
      });
    </script>
@stop