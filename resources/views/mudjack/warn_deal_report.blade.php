@extends('admin.layouts.iframe')

@section('container')
    <style type="text/css">
        th, td { font-size: 14px; height: 40px;}
    </style>
    <style type="text/css" media="print">
        .noprint{display : none}
    </style>
    <article class="page-container" id="show_detail" style="width:780px; margin: 0 auto;">
        <div class="cl pd-5 bg-1 bk-gray noprint">
            <span onclick="javascript:window.print();" class="ml-10 export-file export-dy" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
        </div>
        <div class="row cl">
            <div class="col-xs-12 col-sm-12" class="padding-left: 0;">
                <h1 class="text-c"></h1>
                <h2 class="text-c">压浆信息化不合格处理记录表</h2>
                <p class="f-r">编号：第{{$detail->info->section->name}}-{{date('YmdHis',$detail->start_time)}}号</p>
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr>
                            <td width="120">合同段</td>
                            <td>{{$detail->info->section->name}}</td>
                        </tr>
                        <tr>
                            <td width="120">预制梁场</td>
                            <td>{{$detail->info->precasting_yard}}</td>
                        </tr>
                        <tr>
                            <td width="120">设备名称</td>
                            <td>{{$detail->info->device->name}}</td>
                        </tr>
                        <tr>
                            <td width="120">压浆日期</td>
                            <td>{{date('Y-m-d H:i:s',$detail->start_time)}}</td>
                        </tr>
                        <tr>
                            <td width="120">压浆梁号</td>
                            <td>
                                {{$detail->info->girder_number}}
                            </td>
                        </tr>
                        <tr>
                            <td width="120">孔道名称</td>
                            <td>{{$detail->pore_canal_name}}</td>
                        </tr>
                        <tr height="100px">
                            <td width="120">不合格情况</td>
                            <td>报警信息：
                                {{$detail->warn_info}}
                            </td>
                        </tr>
                        <tr height="150px">
                            <td width="120">施工单位原因分析及处理结果</td>
                            <td>@if($warn_deal_info){{$warn_deal_info->section_deal_info}}@endif</td>
                        </tr>
                        <tr height="150px">
                            <td width="120">监理单位原因分析及处理结果</td>
                            <td>@if($warn_deal_info){{$warn_deal_info->supervision_deal_info}}@endif</td>
                        </tr>

                    </tdead>
                </table>
                <div class="col-xs-4 col-sm-6 text-l">监理单位:@if($warn_deal_info){{$warn_deal_info->supervision_user_name}}@endif</div>
                <div class="col-xs-4 col-sm-6 text-r">施工单位:@if($warn_deal_info){{$warn_deal_info->section_user_name}}@endif</div>
            </div>
        </div>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
@stop