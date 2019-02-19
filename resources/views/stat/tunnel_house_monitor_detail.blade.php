@extends('admin.layouts.iframe')

@section('container')
    <div class="col-offset-2 col-xs-8 col-sm-8">

        <div class="col-xs-6 col-sm-6 text-l">
            @if(Auth::user()->role==4 && $this_data)
               @if($this_data->is_check == 0)
                    <input class="btn btn-primary radius edit-data mr-30" data-title="数据修改" data-url="{{url('stat/tunnel_house_monitor_edit'.'/'.$this_data->id)}}" type="button" value="修改">

                    <input class="btn btn-success radius check " data-url="{{url('stat/tunnel_house_monitor_check'.'/'.$this_data->id)}}" type="button" value="审核">
               @endif
            @endif
        </div>
        <div class="col-xs-6 col-sm-6 text-r noprint">
            <span class="ml-10 export-file export-excel" onclick="layer.msg('正在导出数据,请稍后')" data-name="白鹿原隧道尽快洞顶居民区房屋沉降观测" data-obj="print_div" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
            <span class="ml-10 export-file export-dy open-print" data-name="报警信息统计历史数据" data-obj="print_div" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
    </div>

    <div class="col-sm-offset-2 col-xs-12 col-sm-8 mt-20 " id="print_div" class="padding-left: 0; ">
        <div class="panel panel-info">
            <h3 class="text-c">白鹿原隧道进口洞顶居民区房屋沉降观测表</h3>
            <div class=" col-xs-12 col-sm-12 mt-10">
                <div class="col-xs-4 col-sm-4 text-l" style="font-weight: 900;">合同段：LJ-13</div>
                <div class="col-xs-4 col-sm-4 text-c" style="font-weight: 900;">@if($count)第{{$count}}次@endif</div>
                <div class="col-xs-4 col-sm-4 text-r" style="font-weight: 900;">观测时间：@if(isset($this_data->created_at)){{date('Y-m-d H:i:s',$this_data->created_at)}}@endif</div>
            </div>
            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="40">测点</th>
                        <th width="100">初始高程</th>
                        <th width="100">上次实测高程</th>
                        <th width="100">本次实测高程</th>
                        <th width="100" >单次沉降量(mm)</th>
                        <th width="100">累计沉降量(mm)</th>
                        <th width="100">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($init_data)
                      @if($this_data)
                      <tr class="text-c">
                         <td>1#</td>
                          <td>
                              @if($init_data)
                              {{$init_data->station_init_1}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                              {{$last_data->station1}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                              {{$this_data->station1}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                             {{sprintf('%.1f',($this_data->station1-$last_data->station1)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station1-$init_data->station_init_1)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station1_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>2#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_2}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station2}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station2}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station2-$last_data->station2)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station2-$init_data->station_init_2)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station2_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>3#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_3}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station3}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station3}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station3-$last_data->station3)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station3-$init_data->station_init_3)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station3_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>4#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_4}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station4}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station4}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station4-$last_data->station4)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station4-$init_data->station_init_4)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station4_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>5#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_5}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station5}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station5}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station5-$last_data->station5)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station5-$init_data->station_init_5)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station5_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>6#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_6}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station6}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station6}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station6-$last_data->station6)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station6-$init_data->station_init_6)*1000,1)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station6_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>7#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_7}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station7}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station7}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station7-$last_data->station7)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station7-$init_data->station_init_7)*1000,1)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station7_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>8#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_8}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station8}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station8}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station8-$last_data->station8)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station8-$init_data->station_init_8)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station8_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>9#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_9}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station9}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station9}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station9-$last_data->station9)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station9-$init_data->station_init_9)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station9_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>10#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_10}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station10}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station10}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station10-$last_data->station10)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station10-$init_data->station_init_10)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station10_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>11#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_11}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station11}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station11}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station11-$last_data->station11)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station11-$init_data->station_init_11)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station11_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>12#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_12}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station12}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station12}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station12-$last_data->station12)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station12-$init_data->station_init_12)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station12_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>13#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_13}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station13}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station13}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station13-$last_data->station13)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station13-$init_data->station_init_13)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station13_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>14#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_14}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station14}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station14}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station14-$last_data->station14)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station14-$init_data->station_init_14)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station14_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>15#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_15}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station15}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station15}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station15-$last_data->station15)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station15-$init_data->station_init_15)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station15_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>16#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_16}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station16}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station16}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station16-$last_data->station16)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station16-$init_data->station_init_16)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station16_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>17#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_17}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station17}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station17}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station17-$last_data->station17)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station17-$init_data->station_init_17)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station17_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>18#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_18}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station18}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station18}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station18-$last_data->station18)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station18-$init_data->station_init_18)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station18_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>19#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_19}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station19}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station19}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station19-$last_data->station19)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station19-$init_data->station_init_19)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station19_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>20#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_20}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station20}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station20}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station20-$last_data->station20)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station20-$init_data->station_init_20)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station20_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>21#</td>
                          <td>
                              @if($init_data)
                                  {{$init_data->station_init_21}}
                              @endif
                          </td>
                          <td>
                              @if($last_data)
                                  {{$last_data->station21}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station21}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $last_data)
                                  {{sprintf('%.1f',($this_data->station21-$last_data->station21)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data && $init_data)
                                  {{sprintf('%.1f',($this_data->station21-$init_data->station_init_21)*1000)}}
                              @endif
                          </td>
                          <td>
                              @if($this_data)
                                  {{$this_data->station21_remark}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-l">
                         <td colspan="7">观测结论：{{$this_data->conclusion}}</td>
                      </tr>
                      @endif
                   @endif
                    </tbody>
                </table>
            </div>
            <div class=" col-xs-12 col-sm-12">
             <div class="col-xs-6 col-sm-6">驻地办审核人：@if(isset($this_data->check_user)){{$this_data->check_user->name}}@endif</div>
             <div class="col-xs-6 col-sm-6 text-r">填表人：@if(isset($this_data->write_user)){{$this_data->write_user->name}}@endif</div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/export/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/export/jquery.jqprint-0.3.js"></script>
    <script src="/lib/tableExport/libs/pdfMake/pdfmake.min.js"></script>
    <script src="/lib/tableExport/libs/pdfMake/vfs_fonts.js"></script>
    <script type="text/javascript" src="/lib/tableExport/libs/FileSaver/FileSaver.min.js"></script>
    <script type="text/javascript" src="/lib/tableExport/libs/js-xlsx/xlsx.core.min.js"></script>
    <script type="text/javascript" src="/lib/tableExport/libs/jsPDF/jspdf.min.js"></script>
    <script type="text/javascript" src="/lib/tableExport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
    <script type="text/javascript" src="/lib/tableExport/libs/html2canvas/html2canvas.min.js"></script>
    <script type="text/javascript" src="/lib/tableExport/tableExport.js"></script>
    <script type="text/javascript" src="/static/admin/js/export.js"></script>
    <script type="text/javascript">
//        list.init();
        $(function(){
            $('.open-print').on('click',function(){
                $("#print_div").jqprint({
                    debug: false,
                    importCSS: true,
                    printContainer: true,
                    operaSupport: true
                });
            });

            $(".check").on('click',function(){
                var url=$(this).attr("data-url");
                layer.confirm('一旦审核数据将无法修改，确定审核吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){

                    $.get(url,function(data){
                        if(data.status==0){
                            layer.msg(data.info, {
                                icon: 6,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){
                                //do something
                                parent.parent.parent.location.reload();

                            });
                        }
                        if(data.status==1){
                            layer.alert(data.info);
                        }
                    });
                }, function(){

                });
            });

            $(".edit-data").on('click',function() {
                var title = $(this).attr('data-title');
                var url = $(this).attr('data-url');
                layer.open({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    area: ['95%', '95%'],
                    content: url,
                });
            })
        });

    </script>
@stop
