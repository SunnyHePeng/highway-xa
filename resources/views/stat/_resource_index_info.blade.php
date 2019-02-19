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
    <article class="page-container col-sm-11" id="show_detail" style="width:100%; margin: 0 auto;">
        @if(Auth::user()->role==4)
        <div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs">
            <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="Hui-iconfont">&#xe600;</i>资源配置信息录入</a>
                <ul class="dropDown-menu menu radius box-shadow">
                 <li class="text-c open_add" data-title="施工人员数量录入" data-url="{{url('stat/resource_add').'?type=1'}}"><a href="javascript:;">施工人员数量</a></li>
                 <li class="text-c open_add" data-title="机械设备数量录入" data-url="{{url('stat/resource_add').'?type=2'}}"><a href="javascript:;">机械设备数量</a></li>
              </ul>
          </span>
        </div>
        @endif
        <div class=" mt-20 cl pd-5 bg-0 bk-gray noprint text-r">
            <span class="ml-10 export-file export-dy open-print" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <!--startprint-->
        <div class="row cl con-print">
            <div class="col-xs-10 col-sm-10" class="padding-left: 0;">
                <h3 class="text-c" style="position: relative;left: 0;top: 0;">白鹿原隧道{{$section['name']}}合同段资源配置统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
                @if($left_sgry)
                    <h5>统计时间：{{date('Y-m-d',$left_sgry['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif($right_sgry)
                    <h5>统计时间：{{date('Y-m-d',$right_sgry['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif($left_jxsb)
                    <h5>统计时间：{{date('Y-m-d',$left_jxsb['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @elseif($right_jxsb)
                    <h5>统计时间：{{date('Y-m-d',$right_jxsb['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                @endif
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td width="30" rowspan="2">位置</td>
                            <td width="100" rowspan="2">项目</td>
                            <td width="100" >施工人员数量(人)</td>
                            <td width="100" class="">机械设备数量(台)</td>
                        </tr>

                    </tdead>
                    <tbody>
                    <tr class="text-c">
                        <td rowspan="7">左洞</td>
                        <td width="100">掌子面开挖</td>
                        <td width="100">
                            @if($left_sgry)
                            {{$left_sgry->zzmkw}}
                            @endif
                        </td>
                        <td width="100" rowspan="7">
                            湿喷机械手<span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->spjxs}}@endif</span>  台；<br/>
                            防水板铺挂台车<span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->fsbpgtc}}@endif</span>  台；<br/>
                            二衬台车 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->ectc}}@endif</span> 台； <br/>
                            二衬养生台车 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->ecystc}}@endif</span> 台；<br/>
                            自行式整体栈桥液压仰拱模板台车 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->ygmbtc}}@endif</span> 台；<br/>
                            液压水沟电缆槽台车 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->sgdlctc}}@endif</span> 台；<br/>
                            雾炮车<span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->wpc}}@endif</span> 台;<br/>
                            挖掘机<span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->wjj}}@endif</span>  台；<br/>
                            装载机 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->zzj}}@endif</span> 台；<br/>
                            自卸车 <span class="c-blue jxsb">@if($left_jxsb){{$left_jxsb->zxc}}@endif</span> 台。<br/>
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">初期支护</td>
                        <td width="100">
                            @if($left_sgry)
                               {{$left_sgry->cqzh}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">仰拱开挖</td>
                        <td width="100">
                            @if($left_sgry)
                               {{$left_sgry->ygkw}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">仰拱浇筑</td>
                        <td width="100">
                            @if($left_sgry)
                               {{$left_sgry->ygjz}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">防水板铺挂</td>
                        <td width="100">
                            @if($left_sgry)
                              {{$left_sgry->fsbpg}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">二衬施工</td>
                        <td width="100">
                            @if($left_sgry)
                                {{$left_sgry->ecjz}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">合计</td>
                        <td width="100">
                            @if($left_sgry)
                               {{$left_sgry->zzmkw+$left_sgry->cqzh+$left_sgry->ygkw+$left_sgry->ygjz+$left_sgry->fsbpg+$left_sgry->ecjz}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td rowspan="7">右洞</td>
                        <td width="100">掌子面开挖</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->zzmkw}}
                            @endif
                        </td>
                        <td width="100" rowspan="7">
                            湿喷机械手 <span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->spjxs}}@endif</span> 台；<br/>
                            防水板铺挂台车 <span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->fsbpgtc}}@endif</span>  台；<br/>
                            二衬台车<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->ectc}}@endif</span>  台； <br/>
                            二衬养生台车<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->ecystc}}@endif</span>  台；<br/>
                            自行式整体栈桥液压仰拱模板台车<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->ygmbtc}}@endif</span>  台；<br/>
                            液压水沟电缆槽台车 <span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->sgdlctc}}@endif</span> 台；<br/>
                            雾炮车<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->wpc}}@endif</span>  台。<br/>
                            挖掘机<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->wjj}}@endif</span>  台；<br/>
                            装载机<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->zzj}}@endif</span>  台；<br/>
                            自卸车<span class="c-blue jxsb">@if($right_jxsb){{$right_jxsb->zxc}}@endif</span>  台。<br/>
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">初期支护</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->cqzh}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">仰拱开挖</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->ygkw}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">仰拱浇筑</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->ygjz}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">防水板铺挂</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->fsbpg}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">二衬施工</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->ecjz}}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-c">
                        <td width="100">合计</td>
                        <td width="100">
                            @if($right_sgry)
                               {{$right_sgry->zzmkw+$right_sgry->cqzh+$right_sgry->ygkw+$right_sgry->ygjz+$right_sgry->fsbpg+$right_sgry->ecjz}}
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
            $(".open_add").on('click',function(){
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