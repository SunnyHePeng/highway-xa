@extends('admin.layouts.iframe')

@section('container')
    <style type="text/css">
        th, td { font-size: 12px; height: 20px;}
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
    <article class="page-container" id="show_detail" style="width:780px; margin: 0 auto;">
        <div class="cl pd-5 bg-1 bk-gray noprint">
            <span onclick="javascript:window.print();" class="ml-10 export-file export-dy" data-name="白鹿原隧道施工进度表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <div class="row cl">
            <div class="col-xs-12 col-sm-12" style="width: 90%;">
                <h3 class="text-c">白鹿原隧道进度统计表</h3>
                <h4 class="text-l">施工单位:{{$section}}</h4>
                <h4 class="clearfix"><span style="display: block;float: left">填报单位(监理)：{{$supervision}}</span> <span style="display: block;float: right">填报时间：{{date('Y-m-d',$time)}}</span></h4>
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td width="30">位置</td>
                            <td width="80">项目</td>
                            <td width="80">总量(米)</td>
                            <td width="80">当日完成(米)</td>
                            <td width="80">累计完成(米)</td>
                            <td width="80">剩余量(米)</td>
                        </tr>
                    </tdead>
                    <tbody>

                     <tr class="text-c">
                         <td rowspan="7">左洞</td>
                         <td>施工长度</td>
                         <td>{{$total['left'][0]['zl']}}</td>
                         <td>
                             @if($left_data)
                                 @if(!empty($left_data[0]))
                                 {{$left_data[0]['adjj']}}
                                 @endif
                             @endif
                         </td>
                         <td>
                             @if($finish['left_finish'])
                             {{$finish['left_finish']['adjj']}}
                             @endif
                         </td>
                         <td>
                             @if($finish['left_finish'])
                             {{$total['left'][0]['zl']-$finish['left_finish']['adjj']}}
                              @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>初期支护</td>
                         <td >{{$total['left'][1]['zl']}}</td>
                         <td>
                             @if($left_data)
                             @if(!empty($left_data[0]))
                             {{$left_data[0]['cqzh']}}
                             @endif
                             @endif
                         </td>
                         <td>
                             @if($finish['left_finish'])
                             {{$finish['left_finish']['cqzh']}}
                             @endif
                         </td>
                         <td>
                             @if($finish['left_finish'])
                             {{$total['left'][1]['zl']-$finish['left_finish']['cqzh']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>仰拱开挖</td>
                         <td>{{$total['left'][2]['zl']}}</td>
                         <td>
                             @if($left_data)
                             @if(!empty($left_data[0]))
                             {{$left_data[0]['ygkw']}}
                             @endif
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$finish['left_finish']['ygkw']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$total['left'][2]['zl']-$finish['left_finish']['ygkw']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>仰拱浇筑</td>
                         <td>{{$total['left'][3]['zl']}}</td>
                         <td>
                             @if($left_data)
                             @if(!empty($left_data[0]))
                             {{$left_data[0]['ygjz']}}
                             @endif
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$finish['left_finish']['ygjz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$total['left'][3]['zl']-$finish['left_finish']['ygjz']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>防水板铺挂</td>
                         <td>{{$total['left'][4]['zl']}}</td>
                         <td>
                             @if($left_data)
                             @if(!empty($left_data[0]))
                             {{$left_data[0]['fsbpg']}}
                             @endif
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$finish['left_finish']['fsbpg']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$total['left'][4]['zl']-$finish['left_finish']['fsbpg']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>二衬钢筋绑扎</td>
                         <td>{{$total['left'][6]['zl']}}</td>
                         <td>
                             @if($left_data)
                                 @if(!empty($left_data[0]))
                                     {{$left_data[0]['gjbz']}}
                                 @endif
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                                 {{$finish['left_finish']['gjbz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                                 {{$total['left'][6]['zl']-$finish['left_finish']['gjbz']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>二衬浇筑</td>
                         <td>{{$total['left'][5]['zl']}}</td>
                         <td>
                             @if($left_data)
                              @if(!empty($left_data[0]))
                             {{$left_data[0]['ecjz']}}
                              @endif
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$finish['left_finish']['ecjz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['left_finish']))
                             {{$total['left'][5]['zl']-$finish['left_finish']['ecjz']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td rowspan="7">右洞</td>
                         <td>施工长度</td>
                         <td>{{$total['right'][0]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['adjj']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['adjj']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][0]['zl']-$finish['right_finish']['adjj']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>初期支护</td>
                         <td>{{$total['right'][1]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['cqzh']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['cqzh']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][1]['zl']-$finish['right_finish']['cqzh']}}
                              @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>仰拱开挖</td>
                         <td>{{$total['right'][2]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['ygkw']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['ygkw']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][2]['zl']-$finish['right_finish']['ygkw']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>仰拱浇筑</td>
                         <td>{{$total['right'][3]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['ygjz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['ygjz']}}
                              @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][3]['zl']-$finish['right_finish']['ygjz']}}
                              @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>防水板铺挂</td>
                         <td>{{$total['right'][4]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['fsbpg']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['fsbpg']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][5]['zl']-$finish['right_finish']['fsbpg']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>二衬钢筋绑扎</td>
                         <td>{{$total['right'][6]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                                 {{$right_data[0]['gjbz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                                 {{$finish['right_finish']['gjbz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                                 {{$total['right'][6]['zl']-$finish['right_finish']['gjbz']}}
                             @endif
                         </td>
                     </tr>
                     <tr class="text-c">
                         <td>二衬浇筑</td>
                         <td>{{$total['right'][5]['zl']}}</td>
                         <td>
                             @if(!empty($right_data[0]))
                             {{$right_data[0]['ecjz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$finish['right_finish']['ecjz']}}
                             @endif
                         </td>
                         <td>
                             @if(!empty($finish['right_finish']))
                             {{$total['right'][5]['zl']-$finish['right_finish']['ecjz']}}
                             @endif
                         </td>
                     </tr>
                     @if(isset($finish['tfkw_finish'][0]))
                    <tr class="text-c">
                        <td colspan="2">土方开挖(万立方米)</td>
                        <td>{{$total['tfkw'][0]['zl']}}</td>
                        <td>
                            @if(isset($left_data[0]))
                            {{$left_data[0]['tfkw']}}
                            @endif
                        </td>
                        <td>{{$finish['tfkw_finish'][0]['tfkw_finish']}}</td>
                        <td>{{$total['tfkw'][0]['zl']-$finish['tfkw_finish'][0]['tfkw_finish']}}</td>
                    </tr>
                     @endif
                    </tbody>

                </table>

            </div>
        </div>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
@stop