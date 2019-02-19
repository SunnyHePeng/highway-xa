@extends('admin.layouts.iframe')

@section('container')
    <div class="row cl">
        <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
            <table class="table table-border table-bordered table-bg">
                <thead>
                <tr>
                    <th class="text-r c-black f-14" width="100">梁号</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->girder_number}}@endif</td>
                    <th class="text-r c-black f-14" width="100">梁板类型</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->girdertype}}@endif</td>
                    <th class="text-r c-black f-14" width="100">压浆方向</th>
                    <td colspan="4" class="c-black f-14">@if($info_data){{$info_data->mudjackdirect}}@endif</td>
                </tr>
                <tr>
                    <th class="text-r c-black f-14" width="100">水泥名称</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->concretename}}@endif</td>
                    <th class="text-r c-black f-14" width="100">压浆剂</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->mudjackagent}}@endif</td>
                    <th class="text-r c-black f-14" width="100">配合比(水泥:压浆剂:水)</th>
                    <td colspan="4" class="c-black f-14">@if($info_data){{$info_data->groutingagent}}@endif</td>
                </tr>
                <tr>
                    <th class="text-r c-black f-14" width="100">压浆开始时间</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{date('Y-m-d H:i:s',$info_data->time)}}@endif</td>
                    <th class="text-r c-black f-14" width="100">环境温度</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->environment_temperature}}@endif</td>
                    <th class="text-r c-black f-14" width="100">浆液温度</th>
                    <td colspan="4" class="c-black f-14">@if($info_data){{$info_data->seriflux_temperature}}@endif</td>
                </tr>
                <tr>
                    <th class="text-r c-black f-14" width="100">搅拌时间(分钟)</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->stirtime}}@endif</td>
                    <th class="text-r c-black f-14" width="100">流动度</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->mobility}}@endif</td>
                    <th class="text-r c-black f-14" width="100">压浆模式</th>
                    <td colspan="4" class="c-black f-14">@if($info_data){{$info_data->mudjackmode}}@endif</td>
                </tr>
                <tr>
                    <th class="text-r c-black f-14" width="100">操作人员</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->operating_personnel}}@endif</td>
                    <th class="text-r c-black f-14" width="100">张拉日期</th>
                    <td class="c-black f-14" colspan="3">@if($info_data){{$info_data->stretchdate}}@endif</td>
                    <th class="text-r c-black f-14" width="100">步骤参数</th>
                    <td colspan="4" class="c-black f-14">@if($info_data){{$info_data->stepparam}}@endif</td>
                </tr>
                <tr class="text-c">
                    <th>次序</th>
                    <th>孔道名称</th>
                    <th>孔道长度(米)</th>
                    <th >压浆开始时间</th>
                    <th>压浆结束时间</th>
                    <th>保压时间(s)</th>
                    <th>进浆压力(Mpa)</th>
                    <th>返浆压力(Mpa)</th>
                    <th>稳压压力(Mpa)</th>
                    <th>水胶比</th>
                    <th>是否报警</th>
                    <th>结论</th>
                </tr>
                </thead>
                <tbody>
                  @if($detail_data)
                    @foreach($detail_data as $k=>$v)
                        <tr class="text-c">
                            <td>{{$k+1}}</td>
                            <td>{{$v->pore_canal_name}}</td>
                            <td>{{$v->pore_canal_length}}</td>
                            <td>{{date('Y-m-d H:i:s',$v['start_time'])}}</td>
                            <td>{{date('Y-m-d H:i:s',$v['end_time'])}}</td>
                            <td>{{$v->duration_time}}</td>
                            <td>{{$v->enter_pressure}}</td>
                            <td>{{$v->out_pressure}}</td>
                            <td>{{$v->stabilize_pressure}}</td>
                            <td>{{$v->wcratio}}</td>
                            <td>
                                @if($v->is_warn)
                                    <span class="c-red">是</span>
                                @else
                                    <span>否</span>
                                @endif
                            </td>
                            <td>
                                @if($v->is_warn)
                                    <span class="c-red">{{$v->warn_info}}</span>
                                @else

                                @endif
                            </td>
                        </tr>
                    @endforeach
                  @endif
                </tbody>
            </table>
        </div>
    </div>

@stop


@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/lab_info.js"></script>
@stop