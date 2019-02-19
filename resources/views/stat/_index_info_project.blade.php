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
    <div class="ml-20">
        <form method="get" name="search">
            时间：
            <input name="date" placeholder="请输入时间" value="@if($search_date){{$search_date}}@endif" type="text" onfocus="WdatePicker()" id="date" class="input-text Wdate">
            <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

        </form>
    </div>
    <article class="page-container col-sm-11" id="show_detail" style="width:100%; margin: 0 auto;">
        <div class="cl pd-5 bg-1 bk-gray noprint">
            <span class="ml-10 export-file export-dy open-print" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <!--startprint-->
        <div class="row cl con-print">
            <div class="col-xs-12 col-sm-12" class="padding-left: 0;">
                <h3 class="text-c" style="position: relative;left: 0;top: 0;">白鹿原隧道进度统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
                @if(!empty($today_13_left))
                <h5>统计时间：{{date('Y-m-d',$today_13_left['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($today_13_right))
                    <h5>统计时间：{{date('Y-m-d',$today_13_right['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($today_14_left))
                    <h5>统计时间：{{date('Y-m-d',$today_14_left['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($today_14_right))
                    <h5>统计时间：{{date('Y-m-d',$today_14_right['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @endif
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td width="20" rowspan="2">位置</td>
                            <td width="80" rowspan="2">项目</td>
                            <td width="200" colspan="3" class="hidden-xs hidden-sm">总量(米)</td>
                            <td width="200" colspan="3" class="bg-1 bk-gray">当日完成(米)</td>
                            <td width="200" colspan="3">累计完成(米)</td>
                            <td width="200" colspan="3" >剩余量(米)</td>
                        </tr>
                        <tr class="text-c">
                           <td class="hidden-xs hidden-sm">LJ-13</td>
                           <td class="hidden-xs hidden-sm">LJ-14</td>
                           <td class="hidden-xs hidden-sm">合计</td>
                            <td class="bg-1 bk-gray">LJ-13</td>
                            <td class="bg-1 bk-gray">LJ-14</td>
                            <td class="bg-1 bk-gray">合计</td>
                            <td>LJ-13</td>
                            <td>LJ-14</td>
                            <td style="font-weight: bold;">合计</td>
                            <td >LJ-13</td>
                            <td >LJ-14</td>
                            <td style="font-weight: bold;">合计</td>
                        </tr>
                    </tdead>
                    <tbody>
                        @if(!empty($left))
                            @foreach($left[19] as$k=>$v)
                        <tr class="text-c">
                            @if($k==0)
                            <td rowspan="7">左洞</td>
                            @endif
                            <td>{{$v['type_name']}}</td>
                            <td class="hidden-xs hidden-sm">{{$v['zl']}}</td>
                            <td class="hidden-xs hidden-sm">{{$left[20][$k]['zl']}}</td>
                            <td class="hidden-xs hidden-sm">{{$v['zl']+$left[20][$k]['zl']}}</td>
                            <td class="bg-1 bk-gray">
                                @if(!empty($today_13_left))
                                {{$today_13_left[$v['type']]}}
                                @endif
                            </td>
                            <td class="bg-1 bk-gray">
                                @if(!empty($today_14_left))
                                   {{$today_14_left[$v['type']]}}
                                @endif
                            </td>
                            <td class="bg-1 bk-gray">
                                @if(!empty($today_13_left) && !empty($today_14_left))
                                    {{$today_13_left[$v['type']]+$today_14_left[$v['type']]}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($finish_13_left))
                                    {{$finish_13_left[$v['type']]}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($finish_14_left))
                                    {{$finish_14_left[$v['type']]}}
                                @endif
                            </td>
                            <td style="font-weight: bold;">
                                @if(!empty($finish_13_left) && !empty($finish_14_left))
                                    {{$finish_13_left[$v['type']]+$finish_14_left[$v['type']]}}
                                @endif
                            </td>
                            <td >
                                @if(!empty($finish_13_left))
                                    {{$v['zl']-$finish_13_left[$v['type']]}}
                                @endif
                            </td>
                            <td >
                                @if(!empty($finish_14_left))
                                    {{$left[20][$k]['zl']-$finish_14_left[$v['type']]}}
                                @endif
                            </td>
                            <td style="font-weight: bold;">
                                @if(!empty($finish_13_left) && !empty($finish_14_left))
                                    {{$v['zl']-$finish_13_left[$v['type']]+$left[20][$k]['zl']-$finish_14_left[$v['type']]}}
                                @endif
                            </td>

                        </tr>
                        @endforeach
                        @endif

                        @if(!empty($right))
                            @foreach($right[19] as$k=>$v)
                                <tr class="text-c">
                                    @if($k==0)
                                        <td rowspan="7">右洞</td>
                                    @endif
                                    <td>{{$v['type_name']}}</td>
                                    <td class="hidden-xs hidden-sm">@if($v['zl'] > 0){{$v['zl']}}@endif</td>
                                    <td class="hidden-xs hidden-sm @if($right[20][$k]['zl']==0) bg-1 bk-gray active @endif">@if($right[20][$k]['zl']>0){{$right[20][$k]['zl']}}@endif</td>
                                    <td class="hidden-xs hidden-sm">{{$v['zl']+$right[20][$k]['zl']}}</td>
                                    <td class="bg-1 bk-gray">
                                        @if(!empty($today_13_right))
                                            {{$today_13_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td class="bg-1 bk-gray">
                                        @if(!empty($today_14_right))
                                            {{$today_14_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td class="bg-1 bk-gray">
                                        @if(!empty($today_13_right) && !empty($today_14_right))
                                            {{$today_13_right[$v['type']]+$today_14_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($finish_13_right))
                                            {{$finish_13_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td @if(!empty($finish_14_right) && $right[20][$k]['zl']==0) class="bg-1 active" @endif>
                                        @if(!empty($finish_14_right))
                                            {{$finish_14_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td style="font-weight: bold;">
                                        @if(!empty($finish_13_right) && !empty($finish_14_right))
                                            {{$finish_13_right[$v['type']]+$finish_14_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td >
                                        @if(!empty($finish_13_right))
                                            {{$v['zl']-$finish_13_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td >
                                        @if(!empty($finish_14_right) && $right[20][$k]['zl']>0)
                                            {{$right[20][$k]['zl']-$finish_14_right[$v['type']]}}
                                        @endif
                                    </td>
                                    <td style="font-weight: bold;">
                                        @if(!empty($finish_13_right) && !empty($finish_14_right))
                                            {{$v['zl']-$finish_13_right[$v['type']]+$right[20][$k]['zl']-$finish_14_right[$v['type']]}}
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @endif

                        @if(!empty($tfkw))
                           @foreach($tfkw[19] as $k=>$v)
                        <tr class="text-c">
                        <td colspan="2">土方开挖(万立方米)</td>
                        <td class="hidden-xs hidden-sm">{{$v['zl']}}</td>
                        <td class="hidden-xs hidden-sm">{{$tfkw[20][$k]['zl']}}</td>
                        <td class="hidden-xs hidden-sm">{{$v['zl']+$tfkw[20][$k]['zl']}}</td>
                        <td class="bg-1 bk-gray">
                            @if(!empty($today_13_tfkw))
                            {{$today_13_tfkw['tfkw']}}
                            @endif
                        </td>
                        <td class="bg-1 bk-gray">
                            @if(!empty($today_14_tfkw))
                            {{$today_14_tfkw['tfkw']}}
                            @endif
                        </td>
                        <td class="bg-1 bk-gray">
                           @if(!empty($today_13_tfkw) && !empty($today_14_tfkw))
                               {{$today_13_tfkw['tfkw']+$today_14_tfkw['tfkw']}}
                           @endif
                        </td>
                        <td>
                            @if(!empty($finish_13_tfkw))
                                {{$finish_13_tfkw['tfkw_finish']}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($finish_14_tfkw))
                                {{$finish_14_tfkw['tfkw_finish']}}
                            @endif
                        </td>
                        <td style="font-weight: bold;">
                            @if(!empty($finish_13_tfkw) && !empty($finish_14_tfkw))
                                {{$finish_13_tfkw['tfkw_finish']+$finish_14_tfkw['tfkw_finish']}}
                            @endif
                        </td>
                        <td >
                            @if(!empty($finish_13_tfkw))
                                {{$v['zl']-$finish_13_tfkw['tfkw_finish']}}
                            @endif
                        </td>
                        <td >
                            @if(!empty($finish_14_tfkw))
                                {{$tfkw[20][$k]['zl']-$finish_14_tfkw['tfkw_finish']}}
                            @endif
                        </td>
                        <td style="font-weight: bold;">
                            @if(!empty($finish_13_tfkw) && !empty($finish_14_tfkw))
                                {{$v['zl']-$finish_13_tfkw['tfkw_finish']+$tfkw[20][$k]['zl']-$finish_14_tfkw['tfkw_finish']}}
                            @endif
                        </td>

                        </tr>
                        @endforeach
                       @endif

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