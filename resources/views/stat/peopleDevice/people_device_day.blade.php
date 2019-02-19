@extends('admin.layouts.default')

@section('container')
    <div class="col-xs-12 col-sm-12 pd-15 bg-1 bk-gray mt-10">
       @if($day_data)
            <span class="l">
    <a class="btn btn-default radius" data-title="录入日统计数据" data-url="{{url('stat/people_device_day_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>录入当日统计数据</a>
      </span>
       @else
      <span class="l">
    <a class="btn open-add btn-primary radius" data-title="录入日统计数据" data-url="{{url('stat/people_device_day_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>录入当日统计数据</a>
      </span>
       @endif
           @if($day_data)
               <span class="l">
    <a class="btn open-add btn-success radius ml-50" data-title="修改日统计数据" data-url="{{url('stat/people_device_edit')}}" href="javascript:;"><i class="Hui-iconfont">&#xe6df;</i>修改当日统计数据</a>
               </span>
           @else
               <span class="l">
    <a class="btn btn-default radius ml-50" data-title="修改日统计数据" data-url="{{url('stat/people_device_edit')}}" href="javascript:;"><i class="Hui-iconfont">&#xe6df;</i>修改当日统计数据</a>
              </span>
           @endif
        <span class=" export-file export-dy open-print r" data-name="日投入人员，设备统计表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
    </div>
    <!--startprint-->
    <div class=" col-xs-12 col-sm-12" class="padding-left: 0; ">
        <div class="">
            <h1 class="text-c ">白鹿原隧道当日施工情况统计表</h1>
            @if($day_data)
                @if($day_data->updated_at)
                    <h5 class="text-l pd-20">日期：@if($day_data){{date('Y-m-d H:i:s',$day_data->updated_at)}}@endif</h5>
                @else
                    <h5 class="text-l pd-20">日期：@if($day_data){{date('Y-m-d H:i:s',$day_data->created_at)}}@endif</h5>
                @endif
            @endif
            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead >
                    <tr class="text-c" height="80">
                        <th width="200" class="f-28" rowspan="2">位置</th>
                        <th class="f-28" colspan="3">LJ-13</th>
                        <th class="f-28" colspan="3">LJ-14</th>
                    </tr>
                    <tr class="text-c">
                       <th class="f-24" width="100">人数</th>
                       <th class="f-24" width="200">施工时长(小时)</th>
                       <th class="f-24" width="300">备注</th>
                        <th class="f-24" width="100">人数</th>
                        <th class="f-24" width="200">施工时长(小时)</th>
                        <th class="f-24" width="300">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--左洞施工情况--}}
                      <tr class="text-c" height="80">
                        <td class="f-20">左洞施工情况</td>
                          {{--LJ-13--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_people_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_construction_duration_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_remark_13}}
                            @endif
                        </td>
                          {{--LJ-14--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_people_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_construction_duration_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->l_remark_14}}
                            @endif
                        </td>
                      </tr>
                    {{--右洞施工情况--}}
                    <tr class="text-c" height="80">
                        <td class="f-20">右洞施工情况</td>
                        {{--LJ-13--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_people_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_construction_duration_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_remark_13}}
                            @endif
                        </td>
                        {{--LJ-14--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_people_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_construction_duration_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->r_remark_14}}
                            @endif
                        </td>
                    </tr>
                    {{--钢筋加工厂--}}
                    <tr class="text-c" height="80">
                        <td class="f-20">钢筋加工厂</td>
                        {{--LJ-13--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_construction_duration_13}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_remark_13}}
                            @endif
                        </td>
                        {{--LJ-14--}}
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_construction_duration_14}}
                            @endif
                        </td>
                        <td class="f-20">
                            @if($day_data)
                                {{$day_data->reinforcement_yard_remark_14}}
                            @endif
                        </td>
                    </tr>
                    {{--路基施工情况--}}
                    <tr class="text-c" height="80">
                        <td class="f-20">路基施工情况</td>
                        {{--LJ-13--}}
                        <td class="f-20" colspan="3">
                            @if($day_data)
                                {{$day_data->roadbed_construction_13}}
                            @endif
                        </td>

                        {{--LJ-14--}}
                        <td class="f-20" colspan="3">
                            @if($day_data)
                                {{$day_data->roadbed_construction_14}}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!--endprint-->
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
//        list.init();
$(function(){
    $('.open-print').on('click',function(){
        bdhtml = window.document.body.innerHTML;
        sprnstr = "<!--startprint-->";
        eprnstr = "<!--endprint-->";
        prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
        prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
        window.document.body.innerHTML = prnhtml;
        window.print();
        location.reload();
    });
    $(".open-add").on('click',function(){
         var title=$(this).attr("data-title");
         var url=$(this).attr("data-url");

        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            area: ['60%', '90%'],
            content: url,
        });
    });
});
    </script>
@stop
