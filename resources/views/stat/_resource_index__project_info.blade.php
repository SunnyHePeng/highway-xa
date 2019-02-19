@extends('admin.layouts.iframe')

@section('container')
    <style type="text/css">
        th, td { font-size: 14px; height: 20px;}
    </style>
    <style type="text/css" media="print">
        .noprint{display : none}
        .jxsb {
            text-decoration:underline;
            font-weight: 700;
        }
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
    <div class="mb-10 ml-20">
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
                <h3 class="text-c" style="position: relative;left: 0;top: 0;">白鹿原隧道资源配置统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
                @if(!empty($left_sgry_13))
                    <h5>统计时间：{{date('Y-m-d',$left_sgry_13['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($left_sgry_14))
                    <h5>统计时间：{{date('Y-m-d',$left_sgry_14['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($right_sgry_13))
                    <h5>统计时间：{{date('Y-m-d',$right_sgry_13['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($right_sgry_14))
                    <h5>统计时间：{{date('Y-m-d',$right_sgry_14['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($left_jxsb_13))
                    <h5>统计时间：{{date('Y-m-d',$left_jxsb_13['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($left_jxsb_14))
                    <h5>统计时间：{{date('Y-m-d',$left_jxsb_14['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($right_jxsb_13))
                    <h5>统计时间：{{date('Y-m-d',$right_jxsb_13['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif(!empty($right_jxsb_14))
                    <h5>统计时间：{{date('Y-m-d',$right_jxsb_14['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @endif
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td width="30" rowspan="2">位置</td>
                            <td width="150" rowspan="2">项目</td>
                            <td width="200" colspan="2" >施工人员数量(人)</td>
                            <td width="400" colspan="2" class="">机械设备数量(台)</td>
                        </tr>
                        <tr class="text-c">
                            <td  width="100">LJ-13合同段</td>
                            <td  width="100">LJ-14合同段</td>
                            <td  width="200">LJ-13合同段</td>
                            <td width="200">LJ-14合同段</td>
                        </tr>
                    </tdead>
                    <tbody>
                        <tr class="text-c">
                            <td rowspan="7">左洞</td>
                            <td>掌子面开挖</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                    {{$left_sgry_13['zzmkw']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['zzmkw']}}
                                @endif
                            </td>
                            <td rowspan="7">
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['spjxs'] !=0)
                                湿喷机械手<span class="c-blue jxsb">{{$left_jxsb_13['spjxs']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['fsbpgtc'] != 0)
                                防水板铺挂台车<span class="c-blue jxsb">{{$left_jxsb_13['fsbpgtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['ectc'] !=0)
                                二衬台车<span class="c-blue jxsb">@if(!empty($left_jxsb_13)){{$left_jxsb_13['ectc']}}@endif</span>  台； <br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['ecystc'] !=0)
                                二衬养生台车 <span class="c-blue jxsb">{{$left_jxsb_13['ecystc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['ygmbtc'] != 0)
                                自行式整体栈桥液压仰拱模板台车 <span class="c-blue jxsb">{{$left_jxsb_13['ygmbtc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['sgdlctc'] != 0)
                                液压水沟电缆槽台车 <span class="c-blue jxsb">{{$left_jxsb_13['sgdlctc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['wpc'] != 0)
                                雾炮车<span class="c-blue jxsb">{{$left_jxsb_13['wpc']}}</span>  台<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['wjj'] !=0)
                                挖掘机 <span class="c-blue jxsb">{{$left_jxsb_13['wjj']}}</span> 台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['zzj'] != 0)
                                装载机<span class="c-blue jxsb">{{$left_jxsb_13['zzj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_13) && $left_jxsb_13['zxc'] != 0)
                                自卸车<span class="c-blue jxsb">{{$left_jxsb_13['zxc']}}</span>  台。<br/>
                                @endif
                            </td>
                            <td rowspan="7">
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['spjxs'] != 0)
                                湿喷机械手<span class="c-blue jxsb">{{$left_jxsb_14['spjxs']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['fsbpgtc'] != 0)
                                防水板铺挂台车<span class="c-blue jxsb">{{$left_jxsb_14['fsbpgtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['ectc'] != 0)
                                二衬台车<span class="c-blue jxsb">{{$left_jxsb_14['ectc']}}</span>  台； <br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['ecystc'] != 0)
                                二衬养生台车<span class="c-blue jxsb">{{$left_jxsb_14['ecystc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['ygmbtc'] != 0)
                                自行式整体栈桥液压仰拱模板台车<span class="c-blue jxsb">{{$left_jxsb_14['ygmbtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['sgdlctc'] != 0)
                                液压水沟电缆槽台车<span class="c-blue jxsb">{{$left_jxsb_14['sgdlctc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['wpc'] != 0)
                                雾炮车<span class="c-blue jxsb">{{$left_jxsb_14['wpc']}}</span>  台。<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['wjj'] != 0)
                                挖掘机<span class="c-blue jxsb">{{$left_jxsb_14['wjj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['zzj'] !=0)
                                装载机<span class="c-blue jxsb">{{$left_jxsb_14['zzj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($left_jxsb_14) && $left_jxsb_14['zxc'] != 0)
                                自卸车<span class="c-blue jxsb">{{$left_jxsb_14['zxc']}}</span>  台。<br/>
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td>初期支护</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                   {{$left_sgry_13['cqzh']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                   {{$left_sgry_14['cqzh']}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td>仰拱开挖</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                    {{$left_sgry_13['ygkw']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['ygkw']}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td>仰拱浇筑</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                    {{$left_sgry_13['ygjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['ygjz']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>防水板铺挂</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                    {{$left_sgry_13['fsbpg']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['fsbpg']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>二衬施工</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                    {{$left_sgry_13['ecjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['ecjz']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>合计</td>
                            <td>
                                @if(!empty($left_sgry_13))
                                 {{$left_sgry_13['zzmkw']+$left_sgry_13['cqzh']+$left_sgry_13['ygkw']+$left_sgry_13['ygjz']+$left_sgry_13['fsbpg']+$left_sgry_13['ecjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($left_sgry_14))
                                    {{$left_sgry_14['zzmkw']+$left_sgry_14['cqzh']+$left_sgry_14['ygkw']+$left_sgry_14['ygjz']+$left_sgry_14['fsbpg']+$left_sgry_14['ecjz']}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td rowspan="7">右洞</td>
                            <td>掌子面开挖</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['zzmkw']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['zzmkw']}}
                                @endif
                            </td>
                            <td rowspan="7">
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['spjxs'] != 0)
                                湿喷机械手<span class="c-blue jxsb">{{$right_jxsb_13['spjxs']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['fsbpgtc'] != 0)
                                防水板铺挂台车<span class="c-blue jxsb">{{$right_jxsb_13['fsbpgtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['ectc'] != 0)
                                二衬台车<span class="c-blue jxsb">{{$right_jxsb_13['ectc']}}</span>  台； <br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['ecystc'] != 0)
                                二衬养生台车 <span class="c-blue jxsb">{{$right_jxsb_13['ecystc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['ygmbtc'] != 0)
                                自行式整体栈桥液压仰拱模板台车 <span class="c-blue jxsb">{{$right_jxsb_13['ygmbtc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['sgdlctc'] != 0)
                                液压水沟电缆槽台车<span class="c-blue jxsb">{{$right_jxsb_13['sgdlctc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['wpc'] != 0)
                                雾炮车<span class="c-blue jxsb">{{$right_jxsb_13['wpc']}}</span>  台;<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['wjj'] != 0)
                                挖掘机<span class="c-blue jxsb">{{$right_jxsb_13['wjj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['zzj'] != 0)
                                装载机 <span class="c-blue jxsb">{{$right_jxsb_13['zzj']}}</span> 台；<br/>
                                @endif
                                @if(!empty($right_jxsb_13) && $right_jxsb_13['zxc'] !=0)
                                自卸车<span class="c-blue jxsb">{{$right_jxsb_13['zxc']}}</span>  台。<br/>
                                @endif
                            </td>
                            <td rowspan="7">
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['spjxs'] != 0)
                                湿喷机械手<span class="c-blue jxsb">{{$right_jxsb_14['spjxs']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['fsbpgtc'] != 0)
                                防水板铺挂台车<span class="c-blue jxsb">{{$right_jxsb_14['fsbpgtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['ectc'] != 0)
                                二衬台车<span class="c-blue jxsb">{{$right_jxsb_14['ectc']}}</span>  台； <br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['ecystc'] != 0)
                                二衬养生台车<span class="c-blue jxsb">{{$right_jxsb_14['ecystc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['ygmbtc'] != 0)
                                自行式整体栈桥液压仰拱模板台车<span class="c-blue jxsb">{{$right_jxsb_14['ygmbtc']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['sgdlctc'] != 0)
                                液压水沟电缆槽台车 <span class="c-blue jxsb">{{$right_jxsb_14['sgdlctc']}}</span> 台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['wpc'] != 0)
                                雾炮车<span class="c-blue jxsb">{{$right_jxsb_14['wpc']}}</span>  台;<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['wjj'] != 0)
                                挖掘机<span class="c-blue jxsb">{{$right_jxsb_14['wjj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['zzj'] !=0)
                                装载机<span class="c-blue jxsb">{{$right_jxsb_14['zzj']}}</span>  台；<br/>
                                @endif
                                @if(!empty($right_jxsb_14) && $right_jxsb_14['zxc'] !=0)
                                自卸车<span class="c-blue jxsb">{{$right_jxsb_14['zxc']}}</span>  台。<br/>
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td>初期支护</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['cqzh']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['cqzh']}}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-c">
                            <td>仰拱开挖</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['ygkw']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['ygkw']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>仰拱浇筑</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['ygjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['ygjz']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>防水板铺挂</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['fsbpg']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['fsbpg']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>二衬施工</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['ecjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['ecjz']}}
                                @endif
                            </td>

                        </tr>
                        <tr class="text-c">
                            <td>合计</td>
                            <td>
                                @if(!empty($right_sgry_13))
                                    {{$right_sgry_13['zzmkw']+$right_sgry_13['cqzh']+$right_sgry_13['ygkw']+$right_sgry_13['ygjz']+$right_sgry_13['fsbpg']+$right_sgry_13['ecjz']}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($right_sgry_14))
                                    {{$right_sgry_14['zzmkw']+$right_sgry_14['cqzh']+$right_sgry_14['ygkw']+$right_sgry_14['ygjz']+$right_sgry_14['fsbpg']+$right_sgry_14['ecjz']}}
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