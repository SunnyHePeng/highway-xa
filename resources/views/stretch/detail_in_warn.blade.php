@extends('admin.layouts.iframe')

@section('container')
    @if(Auth::user()->role==4 || Auth::user()->role==5)
        @if(Auth::user()->role==4 && $detail->is_sup_deal==0)
    <input class="btn btn-success radius mb-10 open-deal" data-title="报警处理" data-url="{{url('stretch/warn_deal'.'/'.$detail->id)}}" type="button" value="报警处理">
        @endif
        @if(Auth::user()->role==5 && $detail->is_sec_deal == 0)
            <input class="btn btn-success radius mb-10 open-deal" data-title="报警处理" data-url="{{url('stretch/warn_deal'.'/'.$detail->id)}}" type="button" value="报警处理">
        @endif
    @endif
    <div class="row cl">
        <div class="wl_info col-xs-12 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-header">张拉详细数据</div>
                <div class="panel-body" class="padding: 0;">
                    <table class="table table-border table-bordered table-bg">
                        <tbody>
                        <tr class="text-c">
                            <td class="f-14 c-black">工程名称</td>
                            <td class="f-14 c-black">{{$detail->info->engineering_name}}</td>
                            <td class="f-14 c-black">梁号</td>
                            <td class="f-14 c-black">{{$detail->info->girder_number}}</td>
                            <td class="f-14 c-black">孔道名称</td>
                            <td class="f-14 c-black">{{$detail->pore_canal_name}}</td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">监理单位</td>
                            <td class="f-14 c-black">{{$detail->info->supervisor_unit}}</td>
                            <td class="f-14 c-black">张拉单位</td>
                            <td class="f-14 c-black">{{$detail->info->stretch_unit}}</td>
                            <td class="f-14 c-black">预制梁场</td>
                            <td class="f-14 c-black">{{$detail->info->precasting_yard}}</td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">砼设计强度(MPa)</td>
                            <td class="f-14 c-black">{{$detail->info->concrete_design_intensity}}</td>
                            <td class="f-14 c-black">砼实际强度(MPa)</td>
                            <td class="f-14 c-black">{{$detail->info->concrete_reality_intensity}}</td>
                            <td class="f-14 c-black">钢绞线根数</td>
                            <td class="f-14 c-black">{{$detail->steel_strand_number}}</td>
                        </tr>
                        <tr class="text-c">
                            <td class="f-14 c-black">张拉顺序</td>
                            <td class="f-14 c-black">{{$detail->info->stretch_order}}</td>
                            <td class="f-14 c-black">张拉工艺</td>
                            <td class="f-14 c-black">{{$detail->info->stretch_craft}}</td>
                            <td class="f-14 c-black">构件类型</td>
                            <td class="f-14 c-black">{{$detail->info->component_type}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-border table-bordered">
                        <thead class="text-c">
                        <tr >
                            <th>张拉断面</th>
                            <th>记录项目</th>
                            <th>初始行程10%</th>
                            <th>第一行程20%</th>
                            <th>第二行程50%</th>
                            <th>第三行程50%</th>
                            <th>第四行程100%</th>
                            <th>持荷时间(s)</th>
                            <th>设计张力(KN)</th>
                            <th>回缩值1</th>
                            <th>回缩值2</th>
                            <th>设计伸长量(mm)</th>
                            <th class="c-blue">实际伸长量(mm)</th>
                            <th>延伸量误差(%)</th>
                            <th>结论</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($detail)

                                <tr class="text-c">
                                    <td rowspan="2" class="c-black f-14" >张拉仪1</td>
                                    <td class="c-black f-14" >张拉力(KN)</td>
                                    <td class="c-black f-14">{{$detail->init_stroke_force1}}</td>
                                    <td class="c-black f-14">{{$detail->first_stroke_force1}}</td>
                                    <td class="c-black f-14">{{$detail->second_stroke_force1}}</td>
                                    <td class="c-black f-14">{{$detail->third_stroke_force1}}</td>
                                    <td class="c-black f-14">{{$detail->fourth_stroke_force1}}</td>
                                    <td class="c-black f-14" rowspan="4">{{$detail->hold_time}}</td>
                                    <td rowspan="4" class="c-black f-14">{{$detail->design_stretch_force}}</td>
                                    <td rowspan="4" class="c-black f-14">{{$detail->rebound1}}</td>
                                    <td rowspan="4" class="c-black f-14">{{$detail->rebound2}}</td>
                                    <td rowspan="4" class="c-black f-14">{{$detail->design_elongation}}</td>
                                    <td rowspan="4" class="c-blue">{{$detail->reality_elongation}}</td>
                                    <td rowspan="4" class="c-black f-14">{{$detail->elongation_deviation}}</td>
                                    <td rowspan="4" class="c-black f-14">
                                        @if($detail->is_warn==1)
                                            <span class="c-red">不合格</span>
                                        @else
                                            <span>合格</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="text-c">
                                    <td class="c-black f-14">伸长量(mm)</td>
                                    <td class="c-black f-14">{{$detail->init_stroke_length1}}</td>
                                    <td class="c-black f-14">{{$detail->first_stroke_length1}}</td>
                                    <td class="c-black f-14">{{$detail->second_stroke_length1}}</td>
                                    <td class="c-black f-14">{{$detail->third_stroke_length1}}</td>
                                    <td class="c-black f-14">{{$detail->fourth_stroke_length1}}</td>
                                </tr>
                                <tr class="text-c">
                                    <td rowspan="2" class="c-black f-14" >张拉仪2</td>
                                    <td class="c-black f-14">张拉力(KN)</td>
                                    <td class="c-black f-14">{{$detail->init_stroke_force2}}</td>
                                    <td class="c-black f-14">{{$detail->first_stroke_force2}}</td>
                                    <td class="c-black f-14">{{$detail->second_stroke_force2}}</td>
                                    <td class="c-black f-14">{{$detail->third_stroke_force2}}</td>
                                    <td class="c-black f-14">{{$detail->fourth_stroke_force2}}</td>
                                </tr>
                                <tr class="text-c">
                                    <td class="c-black f-14" >伸长量(mm)</td>
                                    <td class="c-black f-14">{{$detail->init_stroke_length2}}</td>
                                    <td class="c-black f-14">{{$detail->first_stroke_length2}}</td>
                                    <td class="c-black f-14">{{$detail->second_stroke_length2}}</td>
                                    <td class="c-black f-14">{{$detail->third_stroke_length2}}</td>
                                    <td class="c-black f-14">{{$detail->fourth_stroke_length2}}</td>
                                </tr>
                        @endif
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
                    <p>(合同段)处理时间：@if($warn_deal_data){{date('Y-m-d H:i:s',$warn_deal_data->section_deal_time)}}@endif</p>
                    <p>(监理)处理人：@if($warn_deal_data){{$warn_deal_data->supervision_user_name}}@endif</p>
                    <p>(监理)处理意见：@if($warn_deal_data){{$warn_deal_data->supervision_deal_info}}@endif</p>
                    @if($warn_deal_data)
                    @if($warn_deal_data->supervision_img)
                        <div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$warn_deal_data->supervision_img}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$warn_deal_data->supervision_img}}"></a></div>
                    @endif
                    @endif
                    <p>(监理)处理时间：@if($warn_deal_data){{date('Y-m-d H:i:s',$warn_deal_data->supervision_deal_time)}}@endif</p>
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