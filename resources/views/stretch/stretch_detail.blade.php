@extends('admin.layouts.iframe')

@section('container')

    <div class="mt-10 dataTables_wrapper">
        <table class="table table-border table-bordered">
            <thead class="text-c">
            <tr class="text-c">
                <th colspan="3" class="f-14 c-black">({{$info_data->device->name}})张拉详情数据</th>
                <th class="f-14 c-black">张拉日期：{{$info_data->time ? date('Y-m-d',$info_data->time) : ''}}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-c">
                <td class="f-14 c-black">工程名称</td>
                <td class="f-14 c-black">{{$info_data->engineering_name}}</td>
                <td class="f-14 c-black">梁号</td>
                <td class="f-14 c-black">{{$info_data->girder_number}}</td>
            </tr>
            <tr class="text-c">
                <td class="f-14 c-black">监理单位</td>
                <td class="f-14 c-black">{{$info_data->supervisor_unit}}</td>
                <td class="f-14 c-black">张拉单位</td>
                <td class="f-14 c-black">{{$info_data->stretch_unit}}</td>
            </tr>
            <tr class="text-c">
                <td class="f-14 c-black">砼设计强度(MPa)</td>
                <td class="f-14 c-black">{{$info_data->concrete_design_intensity}}</td>
                <td class="f-14 c-black">砼实际强度(MPa)</td>
                <td class="f-14 c-black">{{$info_data->concrete_reality_intensity}}</td>
            </tr>
            <tr class="text-c">
                <td class="f-14 c-black">预支梁厂</td>
                <td class="f-14 c-black">{{$info_data->precasting_yard}}</td>
                <td class="f-14 c-black">构件类型</td>
                <td class="f-14 c-black">{{$info_data->component_type}}</td>
            </tr>
            <tr class="text-c">
                <td class="f-14 c-black">张拉顺序</td>
                <td class="f-14 c-black">{{$info_data->stretch_order}}</td>
                <td class="f-14 c-black">张拉工艺</td>
                <td class="f-14 c-black">{{$info_data->stretch_craft}}</td>

            </tr>
            </tbody>
        </table>
        <table class="table table-border table-bordered">
            <thead class="text-c">
            <tr >
                <th>孔道名称</th>
                <th>钢绞线根数</th>
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
            @if($detail_data)
               @foreach($detail_data as $v)
            <tr class="text-c">
                <td rowspan="4" class="c-black f-14">{{$v->pore_canal_name}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->steel_strand_number}}</td>
                <td rowspan="2" class="c-black f-14" >张拉仪1</td>
                <td class="c-black f-14" >张拉力(KN)</td>
                <td class="c-black f-14">{{$v->init_stroke_force1}}</td>
                <td class="c-black f-14">{{$v->first_stroke_force1}}</td>
                <td class="c-black f-14">{{$v->second_stroke_force1}}</td>
                <td class="c-black f-14">{{$v->third_stroke_force1}}</td>
                <td class="c-black f-14">{{$v->fourth_stroke_force1}}</td>
                <td class="c-black f-14" rowspan="4">{{$v->hold_time}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->design_stretch_force}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->rebound1}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->rebound2}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->design_elongation}}</td>
                <td rowspan="4" class="c-blue">{{$v->reality_elongation}}</td>
                <td rowspan="4" class="c-black f-14">{{$v->elongation_deviation}}</td>
                <td rowspan="4" class="c-black f-14">
                    @if($v->is_warn==1)
                        <span class="c-red">不合格</span>
                    @else
                        <span>合格</span>
                    @endif
                </td>
            </tr>
            <tr class="text-c">
                <td class="c-black f-14">伸长量(mm)</td>
                <td class="c-black f-14">{{$v->init_stroke_length1}}</td>
                <td class="c-black f-14">{{$v->first_stroke_length1}}</td>
                <td class="c-black f-14">{{$v->second_stroke_length1}}</td>
                <td class="c-black f-14">{{$v->third_stroke_length1}}</td>
                <td class="c-black f-14">{{$v->fourth_stroke_length1}}</td>
            </tr>
            <tr class="text-c">
                <td rowspan="2" class="c-black f-14" >张拉仪2</td>
                <td class="c-black f-14">张拉力(KN)</td>
                <td class="c-black f-14">{{$v->init_stroke_force2}}</td>
                <td class="c-black f-14">{{$v->first_stroke_force2}}</td>
                <td class="c-black f-14">{{$v->second_stroke_force2}}</td>
                <td class="c-black f-14">{{$v->third_stroke_force2}}</td>
                <td class="c-black f-14">{{$v->fourth_stroke_force2}}</td>
            </tr>
            <tr class="text-c">
                <td class="c-black f-14" >伸长量(mm)</td>
                <td class="c-black f-14">{{$v->init_stroke_length2}}</td>
                <td class="c-black f-14">{{$v->first_stroke_length2}}</td>
                <td class="c-black f-14">{{$v->second_stroke_length2}}</td>
                <td class="c-black f-14">{{$v->third_stroke_length2}}</td>
                <td class="c-black f-14">{{$v->fourth_stroke_length2}}</td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
@stop
@section('layer')
@stop
@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();
    </script>
@stop