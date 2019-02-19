<style type="text/css" media="print">
    .noprint{display : none}
    .clearfix:after {
        display: block;
        visibility: hidden;
        clear: both;
        height: 0;
        content: ".";
    }
    .clearfix {
        zoom: 1;
    }
</style>
<div class="cl pd-5 text-r noprint">
    <span class="ml-10 export-file export-dy open-print" data-name="征地拆迁日工作台账" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
</div>
<!--startprint-->
<h3 class="text-c" style="font-weight: 900;padding: 0;">西安外环高速公路南段项目征地拆迁日工作台账</h3>
<h4 class="text-r" style="padding: 0;">日期：{{date('Y-m-d',time())}}</h4>
<div class=" col-xs-pull-12 col-sm-pull-12" style="padding-left: 0;">
    <div>
        <div >
            <table id="table_list" class="table table-border table-bordered">
                <thead>
                <tr class="text-c">
                    <th width="50" rowspan="2" class="hidden-xs hidden-sm">所属区县</th>
                    <th width="50" rowspan="2" class="hidden-xs hidden-sm" >所属乡镇</th>
                    <th width="50" rowspan="2">施工标段</th>
                    <th width="100" rowspan="2" class="hidden-xs hidden-sm">里程桩号</th>
                    <th width="80" class="hidden-xs hidden-sm" rowspan="2">长度(KM)</th>
                    <th width="200" colspan="4">用地</th>
                    <th width="200" colspan="4">拆迁房屋(户)</th>
                    <th width="600" colspan="12">电力通讯</th>
                    <th width="400" colspan="8">地埋管道</th>
                    <th width="200" colspan="4">特殊拆迁物(处)</th>
                    <th rowspan="2" width="100" class="hidden-xs hidden-sm pl-0">备注</th>
                </tr>
                <tr class="text-c">
                  <th width="50">总占地(亩)</th>
                  <th width="50">交付</th>
                  <th width="50">累计交付</th>
                  <th width="50">剩余</th>
                  <th width="50">总涉及</th>
                  <th width="50">拆迁</th>
                  <th width="50">累计拆除</th>
                  <th width="50">剩余</th>
                  <th width="50">铁塔(座)</th>
                  <th width="50">迁改</th>
                  <th width="50">累计迁改</th>
                  <th width="50">剩余</th>
                  <th width="50">双杆(处)</th>
                  <th width="50">迁改</th>
                  <th width="50">累计迁改</th>
                  <th width="50">剩余</th>
                  <th width="50">地埋光缆(米)</th>
                  <th width="50">迁改</th>
                  <th width="50">累计迁改</th>
                  <th width="50">剩余</th>
                  <th width="50">输水管道(米)</th>
                  <th width="50">迁改</th>
                  <th width="50">累计迁改</th>
                  <th width="50">剩余</th>
                  <th width="50">天然气管道</th>
                  <th width="50">迁改</th>
                  <th width="50">累计迁改</th>
                  <th width="50">剩余</th>
                  <th width="50">总数量(处)</th>
                  <th width="50">拆除</th>
                  <th width="50">累计拆除</th>
                  <th width="50">剩余</th>
                </tr>
                </thead>
                <tbody>
                {{--长安区--}}
                  @if($gaoxin_data)
                    @foreach($gaoxin_data as $k=>$v)
                        <tr class="text-c">
                           @if($k==0)
                              <td rowspan="4" class="hidden-xs hidden-sm">{{$v['district_name']}}</td>
                           @endif
                              <td class="hidden-xs hidden-sm">{{$v['town_name']}}</td>
                              <td>{{$v['section']['name']}}</td>
                              <td class="hidden-xs hidden-sm">{{$v['mileage_stake']}}</td>
                              <td class="hidden-xs hidden-sm">{{$v['length']}}</td>
                            {{--用地数据--}}
                                 {{--总量--}}
                              <td >{{$v['occupation_total']}}</td>
                                  {{--当日数据--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['occupation_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                                  {{--累计数据--}}
                              <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['occupation_sum']}}
                                @else
                                    0
                                @endif
                              </td>
                                   {{--剩余--}}
                              <td>
                                 @if(array_key_exists('finish',$v))
                                     {{$v['occupation_total']-$v['finish']['occupation_sum']}}
                                 @else
                                     {{$v['occupation_total']}}
                                 @endif
                              </td>
                            {{--拆迁房屋--}}
                               {{--总量--}}
                               <td>{{$v['house_total']}}</td>
                               {{--当日--}}
                               <td>
                                   @if(array_key_exists('now',$v))
                                       {{$v['now']['house_day']}}
                                   @else
                                      0
                                   @endif
                               </td>
                                {{--累计--}}
                               <td>
                                 @if(array_key_exists('finish',$v))
                                     {{$v['finish']['house_sum']}}
                                 @else
                                     0
                                 @endif
                               </td>
                               {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['house_total']-$v['finish']['house_sum']}}
                                  @else
                                      {{$v['house_total']}}
                                  @endif
                              </td>
                            {{--电力通讯--}}
                                {{--铁塔--}}
                                 {{--总量--}}
                              <td>{{$v['pylon_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['pylon_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['pylon_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                             {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['pylon_total']-$v['finish']['pylon_sum']}}
                                  @else
                                      {{$v['pylon_total']}}
                                  @endif
                              </td>
                            {{--双杆--}}
                               {{--总量--}}
                              <td>{{$v['parallels_total']}}</td>
                               {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['parallels_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                               {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['parallels_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['parallels_total']-$v['finish']['parallels_sum']}}
                                  @else
                                      {{$v['parallels_total']}}
                                  @endif
                              </td>
                            {{--地埋光缆--}}
                               {{--总量--}}
                               <td>{{$v['optical_cable_total']}}</td>
                               {{--当日--}}
                               <td>
                                   @if(array_key_exists('now',$v))
                                       {{$v['now']['optical_cable_day']}}
                                   @else
                                       0
                                   @endif
                               </td>
                               {{--累计--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['finish']['optical_cable_sum']}}
                                   @else
                                       0
                                   @endif
                               </td>
                               {{--剩余--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['optical_cable_total']-$v['finish']['optical_cable_sum']}}
                                   @else
                                       {{$v['optical_cable_total']}}
                                   @endif
                               </td>
                            {{--地埋管道--}}
                                {{--输水管道--}}
                                  {{--总量--}}
                               <td>{{$v['water_pipe_total']}}</td>
                                  {{--当日--}}
                               <td>
                                   @if(array_key_exists('now',$v))
                                      {{$v['now']['water_pipe_day']}}
                                   @else
                                       0
                                   @endif
                               </td>
                                  {{--累计--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['finish']['water_pipe_sum']}}
                                   @else
                                       0
                                   @endif
                               </td>
                                  {{--剩余--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['water_pipe_total']-$v['finish']['water_pipe_sum']}}
                                   @else
                                       {{$v['water_pipe_total']}}
                                   @endif
                               </td>
                            {{--天然气管道--}}
                               {{--总量--}}
                               <td>{{$v['natural_gas_pipeline_total']}}</td>
                               {{--当日--}}
                               <td>
                                  @if(array_key_exists('now',$v))
                                     {{$v['now']['natural_gas_pipeline_day']}}
                                  @else
                                      0
                                  @endif
                               </td>
                                {{--累计--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                      {{$v['finish']['natural_gas_pipeline_sum']}}
                                   @else
                                       0
                                   @endif
                               </td>
                                {{--剩余--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['natural_gas_pipeline_total']-$v['finish']['natural_gas_pipeline_sum']}}
                                   @else
                                       {{$v['natural_gas_pipeline_total']}}
                                   @endif
                               </td>
                               {{--特殊拆除物--}}
                                   {{--总量--}}
                               <td>{{$v['special_remove_total']}}</td>
                                   {{--当日--}}
                               <td>
                                   @if(array_key_exists('now',$v))
                                       {{$v['now']['special_remove_day']}}
                                   @else
                                       0
                                   @endif
                               </td>
                                   {{--累计--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['finish']['special_remove_sum']}}
                                   @else
                                       0
                                   @endif
                               </td>
                                  {{--剩余--}}
                               <td>
                                   @if(array_key_exists('finish',$v))
                                       {{$v['special_remove_total']-$v['finish']['special_remove_sum']}}
                                   @else
                                       {{$v['special_remove_total']}}
                                   @endif
                               </td>
                               {{--备注--}}
                               <td class="hidden-xs hidden-sm">
                                   @if(array_key_exists('now',$v))
                                       {{$v['now']['remark']}}
                                   @endif
                               </td>
                        </tr>
                    @endforeach
                  @endif
                {{--占空行--}}
                <tr class="hidden-xs hidden-sm">
                    <td colspan="3" height="20"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="hidden-xs hidden-sm"></td>
                </tr>
                {{--长安区--}}
                  @if($changan_data)
                      @foreach($changan_data as $k=>$v)
                          <tr class="text-c">
                              @if($k==0)
                                  <td rowspan="6" class="hidden-xs hidden-sm">{{$v['district_name']}}</td>
                              @endif
                              <td class="hidden-xs hidden-sm">{{$v['town_name']}}</td>
                              <td >{{$v['section']['name']}}</td>
                              <td class="hidden-xs hidden-sm">{{$v['mileage_stake']}}</td>
                              <td class="hidden-xs hidden-sm">{{$v['length']}}</td>
                              {{--用地数据--}}
                              {{--总量--}}
                              <td>{{$v['occupation_total']}}</td>
                              {{--当日数据--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['occupation_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计数据--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['occupation_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['occupation_total']-$v['finish']['occupation_sum']}}
                                  @else
                                      {{$v['occupation_total']}}
                                  @endif
                              </td>
                              {{--拆迁房屋--}}
                              {{--总量--}}
                              <td>{{$v['house_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['house_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['house_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['house_total']-$v['finish']['house_sum']}}
                                  @else
                                      {{$v['house_total']}}
                                  @endif
                              </td>
                              {{--电力通讯--}}
                              {{--铁塔--}}
                              {{--总量--}}
                              <td>{{$v['pylon_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['pylon_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['pylon_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['pylon_total']-$v['finish']['pylon_sum']}}
                                  @else
                                      {{$v['pylon_total']}}
                                  @endif
                              </td>
                              {{--双杆--}}
                              {{--总量--}}
                              <td>{{$v['parallels_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['parallels_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['parallels_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['parallels_total']-$v['finish']['parallels_sum']}}
                                  @else
                                      {{$v['parallels_total']}}
                                  @endif
                              </td>
                              {{--地埋光缆--}}
                              {{--总量--}}
                              <td>{{$v['optical_cable_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['optical_cable_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['optical_cable_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['optical_cable_total']-$v['finish']['optical_cable_sum']}}
                                  @else
                                      {{$v['optical_cable_total']}}
                                  @endif
                              </td>
                              {{--地埋管道--}}
                              {{--输水管道--}}
                              {{--总量--}}
                              <td>{{$v['water_pipe_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['water_pipe_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['water_pipe_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['water_pipe_total']-$v['finish']['water_pipe_sum']}}
                                  @else
                                      {{$v['water_pipe_total']}}
                                  @endif
                              </td>
                              {{--天然气管道--}}
                              {{--总量--}}
                              <td>{{$v['natural_gas_pipeline_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['natural_gas_pipeline_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['natural_gas_pipeline_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['natural_gas_pipeline_total']-$v['finish']['natural_gas_pipeline_sum']}}
                                  @else
                                      {{$v['natural_gas_pipeline_total']}}
                                  @endif
                              </td>
                              {{--特殊拆除物--}}
                              {{--总量--}}
                              <td>{{$v['special_remove_total']}}</td>
                              {{--当日--}}
                              <td>
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['special_remove_day']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--累计--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['finish']['special_remove_sum']}}
                                  @else
                                      0
                                  @endif
                              </td>
                              {{--剩余--}}
                              <td>
                                  @if(array_key_exists('finish',$v))
                                      {{$v['special_remove_total']-$v['finish']['special_remove_sum']}}
                                  @else
                                      {{$v['special_remove_total']}}
                                  @endif
                              </td>
                              {{--备注--}}
                              <td class="hidden-xs hidden-sm">
                                  @if(array_key_exists('now',$v))
                                      {{$v['now']['remark']}}
                                  @endif
                              </td>
                          </tr>
                      @endforeach
                  @endif
                  {{--占空行--}}
                <tr class="hidden-xs hidden-sm">
                    <td colspan="3" height="20"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="hidden-xs hidden-sm"></td>
                </tr>
                   {{--蓝田县--}}
                @if($lantian_data)
                    @foreach($lantian_data as $k=>$v)
                        <tr class="text-c">
                            @if($k==0)
                                <td rowspan="7" class="hidden-xs hidden-sm">{{$v['district_name']}}</td>
                            @endif
                            <td class="hidden-xs hidden-sm">{{$v['town_name']}}</td>
                            <td>{{$v['section']['name']}}</td>
                            <td class="hidden-xs hidden-sm">{{$v['mileage_stake']}}</td>
                            <td class="hidden-xs hidden-sm">{{$v['length']}}</td>
                            {{--用地数据--}}
                            {{--总量--}}
                            <td>{{$v['occupation_total']}}</td>
                            {{--当日数据--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['occupation_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计数据--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['occupation_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['occupation_total']-$v['finish']['occupation_sum']}}
                                @else
                                    {{$v['occupation_total']}}
                                @endif
                            </td>
                            {{--拆迁房屋--}}
                            {{--总量--}}
                            <td>{{$v['house_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['house_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['house_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['house_total']-$v['finish']['house_sum']}}
                                @else
                                    {{$v['house_total']}}
                                @endif
                            </td>
                            {{--电力通讯--}}
                            {{--铁塔--}}
                            {{--总量--}}
                            <td>{{$v['pylon_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['pylon_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['pylon_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['pylon_total']-$v['finish']['pylon_sum']}}
                                @else
                                    {{$v['pylon_total']}}
                                @endif
                            </td>
                            {{--双杆--}}
                            {{--总量--}}
                            <td>{{$v['parallels_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['parallels_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['parallels_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['parallels_total']-$v['finish']['parallels_sum']}}
                                @else
                                    {{$v['parallels_total']}}
                                @endif
                            </td>
                            {{--地埋光缆--}}
                            {{--总量--}}
                            <td>{{$v['optical_cable_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['optical_cable_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['optical_cable_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['optical_cable_total']-$v['finish']['optical_cable_sum']}}
                                @else
                                    {{$v['optical_cable_total']}}
                                @endif
                            </td>
                            {{--地埋管道--}}
                            {{--输水管道--}}
                            {{--总量--}}
                            <td>{{$v['water_pipe_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['water_pipe_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['water_pipe_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['water_pipe_total']-$v['finish']['water_pipe_sum']}}
                                @else
                                    {{$v['water_pipe_total']}}
                                @endif
                            </td>
                            {{--天然气管道--}}
                            {{--总量--}}
                            <td>{{$v['natural_gas_pipeline_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['natural_gas_pipeline_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['natural_gas_pipeline_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['natural_gas_pipeline_total']-$v['finish']['natural_gas_pipeline_sum']}}
                                @else
                                    {{$v['natural_gas_pipeline_total']}}
                                @endif
                            </td>
                            {{--特殊拆除物--}}
                            {{--总量--}}
                            <td>{{$v['special_remove_total']}}</td>
                            {{--当日--}}
                            <td>
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['special_remove_day']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--累计--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['finish']['special_remove_sum']}}
                                @else
                                    0
                                @endif
                            </td>
                            {{--剩余--}}
                            <td>
                                @if(array_key_exists('finish',$v))
                                    {{$v['special_remove_total']-$v['finish']['special_remove_sum']}}
                                @else
                                    {{$v['special_remove_total']}}
                                @endif
                            </td>
                            {{--备注--}}
                            <td class="hidden-xs hidden-sm">
                                @if(array_key_exists('now',$v))
                                    {{$v['now']['remark']}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                {{--合计--}}
                <tr class="text-c hidden-xs hidden-sm">
                    <td colspan="5">合计</td>
                     {{--用地总占地合计--}}
                    <td>{{$amount['occupation_total_amount']}}</td>
                     {{--用地当日交付合计--}}
                    <td>{{$amount['occupation_day_amount']}}</td>
                    {{--用地累计交付合计--}}
                    <td>{{$amount['occupation_finish_amount']}}</td>
                    {{--用地剩余交付合计--}}
                    <td>{{$amount['occupation_total_amount']-$amount['occupation_finish_amount']}}</td>
                    {{--拆迁房屋总量合计--}}
                    <td>{{$amount['house_total_amount']}}</td>
                    {{--拆迁房屋当日数据合计--}}
                    <td>{{$amount['house_day_amount']}}</td>
                    {{--拆迁房屋累计合计--}}
                    <td>{{$amount['house_finish_amount']}}</td>
                    {{--拆迁房屋剩余合计--}}
                    <td>{{$amount['house_total_amount']-$amount['house_finish_amount']}}</td>
                    {{--铁塔迁改总量合计--}}
                    <td>{{$amount['pylon_total_amount']}}</td>
                    {{--铁塔迁改今日数据合计--}}
                    <td>{{$amount['pylon_day_amount']}}</td>
                    {{--铁塔迁改累计数据合计--}}
                    <td>{{$amount['pylon_finish_amount']}}</td>
                    {{--铁塔迁改剩余数据合计--}}
                    <td>{{$amount['pylon_total_amount']-$amount['pylon_finish_amount']}}</td>
                    {{--双杆迁改总量合计--}}
                    <td>{{$amount['parallels_total_amount']}}</td>
                    {{--双杆迁改今日数据合计--}}
                    <td>{{$amount['parallels_day_amount']}}</td>
                    {{--双杆迁改累计数据合计--}}
                    <td>{{$amount['parallels_finish_amount']}}</td>
                    {{--双杆迁改剩余数据合计--}}
                    <td>{{$amount['parallels_total_amount']-$amount['parallels_finish_amount']}}</td>
                    {{--地埋电缆总量合计--}}
                    <td>{{$amount['optical_cable_total_amount']}}</td>
                    {{--地埋电缆当日数据合计--}}
                    <td>{{$amount['optical_cable_day_amount']}}</td>
                     {{--地埋电缆累计数据合计--}}
                    <td>{{$amount['optical_cable_finish_amount']}}</td>
                    {{--地埋电缆剩余数据合计--}}
                    <td>{{$amount['optical_cable_total_amount']-$amount['optical_cable_finish_amount']}}</td>
                    {{--输水管道总量合计--}}
                    <td>{{$amount['water_pipe_total_amount']}}</td>
                    {{--输水管道当日数据合计--}}
                    <td>{{$amount['water_pipe_day_amount']}}</td>
                    {{--输水管道累计数据合计--}}
                    <td>{{$amount['water_pipe_finish_amount']}}</td>
                    {{--输水管道剩余数据合计--}}
                    <td>{{$amount['water_pipe_total_amount']-$amount['water_pipe_finish_amount']}}</td>
                    {{--天然气管道总量合计--}}
                    <td>{{$amount['natural_gas_pipeline_total_amount']}}</td>
                     {{--天然气管道当日数据合计--}}
                    <td>{{$amount['natural_gas_pipeline_day_amount']}}</td>
                    {{--天然气管道累计数据合计--}}
                    <td>{{$amount['natural_gas_pipeline_finish_amount']}}</td>
                    {{--天然气管道剩余数据合计--}}
                    <td>{{$amount['natural_gas_pipeline_total_amount']-$amount['natural_gas_pipeline_finish_amount']}}</td>
                    {{--特殊拆除物总量合计--}}
                    <td>{{$amount['special_remove_total_amount']}}</td>
                    {{--特殊拆除物当日数据合计--}}
                    <td>{{$amount['special_remove_day_amount']}}</td>
                    {{--特殊拆除物累计数据合计--}}
                    <td>{{$amount['special_remove_finish_amount']}}</td>
                    {{--特殊拆除物剩余数据合计--}}
                    <td>{{$amount['special_remove_total_amount']-$amount['special_remove_finish_amount']}}</td>
                    <td class="hidden-xs hidden-sm"></td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>
<!--endprint-->