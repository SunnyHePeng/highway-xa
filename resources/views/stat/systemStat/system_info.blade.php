@extends('admin.layouts.iframe')

@section('container')
    <style type="text/css">
        th, td { font-size: 14px; height: 20px;}
    </style>
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

    <article class="page-container col-sm-10" id="show_detail" style="width:100%; margin: 0 auto;">
        <div class="cl pd-5 bg-1 bk-gray mt-20 col-xs-11 col-sm-11 ">
          <span class="l">
             <a class="btn open-add btn-primary radius" data-title="信息化各系统运行情况录入" data-url="{{url('stat/system_run_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>信息化各系统运行情况录入</a>
          </span>
        </div>
        <div class="cl mt-10 pd-5 bg-1 bk-gray noprint  text-r col-xs-11 col-sm-11">
            <span class="ml-10 export-file export-dy open-print " data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <!--startprint-->
        <div class="row cl con-print">
            <div class="col-xs-11 col-sm-11" class="padding-left: 0;">
                <h2 class="text-c" style="position: relative;left: 0;top: 0;">信息化各系统运行情况汇报表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h2>
                <h4 class="clearfix"><span style="display: block;float: left">日期：@if($data){{date('Y-m-d',$data['time'])}}@endif</span><span style="display: block;float: right">填报单位：@if($unit_name){{$unit_name}}@endif</span></h4>
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td width="120">项目</td>
                            <td width="100">状态</td>
                            <td width="150">备注</td>
                        </tr>
                    </tdead>
                    <tbody>
                      <tr class="text-c">
                         <td>试验数据监控系统</td>
                          <td>
                              @if($data)
                                  @if($data['lab_data_monitor_status']==1)
                                      <span>正常</span>
                                  @elseif($data['lab_data_monitor_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                 {{$data['lab_data_monitor_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>拌和数据监控系统</td>
                          <td>
                              @if($data)
                                  @if($data['blend_data_monitor_status']==1)
                                      <span>正常</span>
                                  @elseif($data['blend_data_monitor_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['blend_data_monitor_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>视频监控系统</td>
                          <td>
                              @if($data)
                                  @if($data['video_monitor_status']==1)
                                      <span>正常</span>
                                  @elseif($data['video_monitor_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['video_monitor_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>隧道定位系统</td>
                          <td>
                              @if($data)
                                  @if($data['tunnel_location_status']==1)
                                      <span>正常</span>
                                  @elseif($data['tunnel_location_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['tunnel_location_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>高边坡监测系统</td>
                          <td>
                              @if($data)
                                  @if($data['high_side_monitor_status']==1)
                                      <span>正常</span>
                                  @elseif($data['high_side_monitor_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['high_side_monitor_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>电子档案管理系统</td>
                          <td>
                              @if($data)
                                  @if($data['electronic_recode_status']==1)
                                      <span>正常</span>
                                  @elseif($data['electronic_recode_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['electronic_recode_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>试验数据报警</td>
                          <td>
                              @if($data)
                                  @if($data['lab_data_alarm_status']==1)
                                      <span>正常</span>
                                  @elseif($data['lab_data_alarm_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['lab_data_alarm_remark']}}
                              @endif
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td>拌和数据报警</td>
                          <td>
                              @if($data)
                                  @if($data['blend_data_alarm_status']==1)
                                      <span>正常</span>
                                  @elseif($data['blend_data_alarm_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          </td>
                          <td>
                              @if($data)
                                  {{$data['blend_data_alarm_remark']}}
                              @endif
                          </td>
                      </tr>


                    </tbody>

                </table>

            </div>
        </div>
        <!--endprint-->
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script>
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
            $('.open-add').on('click',function(){

                var title=$(this).attr('data-title');
                var url=$(this).attr('data-url');
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