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
    <div class="mt-10 ml-10">
        <form method="get" name="search">
            时间：
            <input name="date" placeholder="请输入时间" value="@if($search_date){{$search_date}}@endif" type="text" onfocus="WdatePicker()" id="date" class="input-text Wdate">
            <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

        </form>
    </div>
    <article class="page-container col-sm-11" id="show_detail" style="width:100%; margin: 0 auto;">
        <div class="cl pd-5 bg-1 bk-gray noprint">
            <span class="ml-10 export-file export-dy open-print" data-name="系统进度报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <!--startprint-->
        <div class="row cl con-print">
            <div class="col-xs-12 col-sm-12" class="padding-left: 0;">
                <h2 class="text-c" style="position: relative;left: 0;top: 0;">信息化各系统运行情况汇报表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h2>
                <h4 class="text-l">日期：@if($report_time){{date('Y-m-d',$report_time)}}@endif</h4>
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td colspan="2">填报单位</td>
                            <td>试验数据监控系统</td>
                            <td>拌和数据监控系统</td>
                            <td>视频监控系统</td>
                            <td>隧道定位系统</td>
                            <td>高边坡监测系统</td>
                            <td>电子档案管理系统</td>
                            <td>试验数据报警</td>
                            <td>拌和数据报警</td>
                        </tr>
                    </tdead>
                    <tbody>
                     {{--监理单位--}}
                         <tr class="text-c">
                             <td rowspan="2" width="20">监理单位</td>
                             <td>LJJ-4</td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['lab_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['lab_data_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['7']['lab_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['blend_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['blend_data_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['7']['blend_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['video_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['video_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['7']['video_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['tunnel_location_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['tunnel_location_status']==2)
                                         <span class="c-red">{{$sup_data['7']['tunnel_location_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['high_side_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['high_side_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['7']['high_side_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['electronic_recode_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['electronic_recode_status']==2)
                                         <span class="c-red">{{$sup_data['7']['electronic_recode_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['lab_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['lab_data_alarm_status']==2)
                                         <span class="c-red">{{$sup_data['7']['lab_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['7']))
                                     @if($sup_data['7']['blend_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['7']['blend_data_alarm_status']==2)
                                         <span class="c-red">{{$sup_data['7']['blend_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                         </tr>
                         <tr class="text-c">
                             <td>LJJ-5</td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['lab_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['lab_data_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['8']['lab_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['blend_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['blend_data_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['8']['blend_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['video_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['video_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['8']['video_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['tunnel_location_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['tunnel_location_status']==2)
                                         <span class="c-red">{{$sup_data['8']['tunnel_location_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['high_side_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['high_side_monitor_status']==2)
                                         <span class="c-red">{{$sup_data['8']['high_side_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['electronic_recode_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['electronic_recode_status']==2)
                                         <span class="c-red">{{$sup_data['8']['electronic_recode_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['lab_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['lab_data_alarm_status']==2)
                                         <span class="c-red">{{$sup_data['8']['lab_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sup_data) && isset($sup_data['8']))
                                     @if($sup_data['8']['blend_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sup_data['8']['blend_data_alarm_status']==2)
                                         <span class="c-red">{{$sup_data['8']['blend_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                         </tr>
                     {{--施工单位--}}
                         <tr class="text-c">
                             <td rowspan="2" width="20">施工单位</td>
                             <td>LJ-13</td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['lab_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['lab_data_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['19']['lab_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['blend_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['blend_data_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['19']['blend_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['video_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['video_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['19']['video_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['tunnel_location_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['tunnel_location_status']==2)
                                         <span class="c-red">{{$sec_data['19']['tunnel_location_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['high_side_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['high_side_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['19']['high_side_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['electronic_recode_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['electronic_recode_status']==2)
                                         <span class="c-red">{{$sec_data['19']['electronic_recode_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['lab_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['lab_data_alarm_status']==2)
                                         <span class="c-red">{{$sec_data['19']['lab_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['19']))
                                     @if($sec_data['19']['blend_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['19']['blend_data_alarm_status']==2)
                                         <span class="c-red">{{$sec_data['19']['blend_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                         </tr>
                         <tr class="text-c">
                             <td>LJ-14</td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['lab_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['lab_data_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['20']['lab_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['blend_data_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['blend_data_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['20']['blend_data_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['video_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['video_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['20']['video_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['tunnel_location_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['tunnel_location_status']==2)
                                         <span class="c-red">{{$sec_data['20']['tunnel_location_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['high_side_monitor_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['high_side_monitor_status']==2)
                                         <span class="c-red">{{$sec_data['20']['high_side_monitor_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['electronic_recode_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['electronic_recode_status']==2)
                                         <span class="c-red">{{$sec_data['20']['electronic_recode_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['lab_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['lab_data_alarm_status']==2)
                                         <span class="c-red">{{$sec_data['20']['lab_data_alarm_remark']}}</span>
                                     @endif
                                 @endif
                             </td>
                             <td>
                                 @if(!empty($sec_data) && isset($sec_data['20']))
                                     @if($sec_data['20']['blend_data_alarm_status']==1)
                                         <span>正常</span>
                                     @elseif($sec_data['20']['blend_data_alarm_status']==2)
                                         <span class="c-red">{{$sec_data['20']['blend_data_alarm_remark']}}</span>
                                     @endif
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
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>

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
        });
    </script>
@stop