@extends('admin.layouts.iframe')

@section('container')
    @if(Auth::user()->role==4 || Auth::user()->role==5)
        @if(Auth::user()->role==4 && $detail->is_sup_deal==0)
            <input class="btn btn-success radius mb-10 open-deal" data-title="报警处理" data-url="{{url('mudjack/warn_deal'.'/'.$detail->id)}}" type="button" value="报警处理">
        @endif
        @if(Auth::user()->role==5 && $detail->is_sec_deal == 0)
            <input class="btn btn-success radius mb-10 open-deal" data-title="报警处理" data-url="{{url('mudjack/warn_deal'.'/'.$detail->id)}}" type="button" value="报警处理">
        @endif
    @endif
    <div class="row cl">
        <div class="wl_info col-xs-12 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-header">压浆详细数据</div>
                <div class="panel-body" class="padding: 0;">
                    <table class="table table-border table-bordered table-bg">
                        <tbody>
                        <tr class="text-c">
                            <td class="f-14 c-black">梁号</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->girder_number}}
                                @endif
                            </td>
                            <td class="f-14 c-black">梁板类型</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->girdertype}}
                                @endif
                            </td>
                            <td class="f-14 c-black">水泥名称</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->concretename}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">压浆剂</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->mudjackagent}}
                                @endif
                            </td>
                            <td class="f-14 c-black">配合比</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->groutingagent}}
                                @endif
                            </td>
                            <td class="f-14 c-black">流动度</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->mobility}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">搅拌时间(min)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->stirtime}}
                                @endif
                            </td>
                            <td class="f-14 c-black">环境温度(℃)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                   {{$detail->info->environment_temperature}}
                                @endif
                            </td>
                            <td class="f-14 c-black">浆液温度(℃)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->seriflux_temperature}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">操作人员</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->operating_personnel}}
                                @endif
                            </td>
                            <td class="f-14 c-black">压浆方向</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->mudjackdirect}}
                                @endif
                            </td>
                            <td class="f-14 c-black">步骤次数</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->stepnum}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">步骤参数</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->stepparam}}
                                @endif
                            </td>
                            <td class="f-14 c-black">张拉日期</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->stretchdate}}
                                @endif
                            </td>
                            <td class="f-14 c-black">压浆模式</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->info->mudjackmode}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">孔道名称</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->pore_canal_name}}
                                @endif
                            </td>
                            <td class="f-14 c-black">孔道长度(米)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->pore_canal_length}}
                                @endif
                            </td>
                            <td class="f-14 c-black">水胶比</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->wcratio}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">进浆压力(MPa)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->enter_pressure}}
                                @endif
                            </td>
                            <td class="f-14 c-black">返浆压力(MPa)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->out_pressure}}
                                @endif
                            </td>
                            <td class="f-14 c-black">稳压值(MPa)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->stabilize_pressure}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">保压时间(s)</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{$detail->duration_time}}
                                @endif
                            </td>
                            <td class="f-14 c-black">压浆开始时间</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{date('Y-m-d H:i:s',$detail->start_time)}}
                                @endif
                            </td>
                            <td class="f-14 c-black">压浆结束时间</td>
                            <td class="f-14 c-black">
                                @if($detail)
                                    {{date('Y-m-d H:i:s',$detail->end_time)}}
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary mt-20">
                <div class="panel-header">处理意见</div>
                <div class="panel-body" class="padding: 0;">
                    <p>(合同段)处理人：@if($warn_deal_data){{$warn_deal_data->section_user_name}}@endif</p>
                    <p>(合同段)处理意见：@if($warn_deal_data){{$warn_deal_data->section_deal_info}}@endif</p>
                    @if($warn_deal_data)
                        @if($warn_deal_data->section_img)
                            <div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$warn_deal_data->section_img}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$warn_deal_data->section_img}}"></a></div>
                        @endif
                    @endif
                    <p>(合同段)处理时间：@if(isset($warn_deal_data->section_deal_time)){{date('Y-m-d H:i:s',$warn_deal_data->section_deal_time)}}@endif</p>
                    <p>(监理)处理人：@if($warn_deal_data){{$warn_deal_data->supervision_user_name}}@endif</p>
                    <p>(监理)处理意见：@if($warn_deal_data){{$warn_deal_data->supervision_deal_info}}@endif</p>
                    @if($warn_deal_data)
                        @if($warn_deal_data->supervision_img)
                            <div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$warn_deal_data->supervision_img}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$warn_deal_data->supervision_img}}"></a></div>
                        @endif
                    @endif
                    <p>(监理)处理时间：@if(isset($warn_deal_data->supervision_deal_time)){{date('Y-m-d H:i:s',$warn_deal_data->supervision_deal_time)}}@endif</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/lab_info.js"></script>
    <script type="text/javascript">
        list.init();
        $(".open-deal").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                area: ['80%', '80%'],
                content: url,
            });
        });
    </script>
@stop