
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
                <h3 class="text-c" style="position: relative;left: 0;top: 0;">报警信息处理情况统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
                @if($data)
                 <h5 class="">统计时间：{{date('Y-m-d',$data[0]['time'])}}(统计时间为前日8:00---今日早晨8:00)</h5>
                @endif
                <table class="table table-border table-bordered table-bg">
                    <tdead>
                        <tr class="text-c">
                            <td rowspan="2" colspan="2">合同段</td>
                            <td colspan="2">试验数据报警</td>
                            <td colspan="2">拌和数据报警</td>
                        </tr>
                        <tr class="text-c">
                            <td>报警数量</td>
                            <td>处理数量</td>
                            <td>报警数量</td>
                            <td>处理数量</td>
                        </tr>
                    </tdead>
                    <tbody>
                    @if(!empty($data))
                        @foreach($data as $v)
                           @if($v['section_id']==18)
                            <tr class="text-c">
                              <td colspan="2">{{$v['section_name']}}</td>
                              <td>{{$v['lab_bj_num']}}</td>
                              <td>{{$v['lab_cl_num']}}</td>
                              <td>{{$v['bhz_bj_num']}}</td>
                              <td>{{$v['bhz_cl_num']}}</td>
                            </tr>
                          @else
                           <tr class="text-c">
                              <td>{{$v['supervision_name']}}驻地办</td>
                              <td>{{$v['section_name']}}合同段</td>
                              <td>{{$v['lab_bj_num']}}</td>
                              <td>{{$v['lab_cl_num']}}</td>
                              <td>{{$v['bhz_bj_num']}}</td>
                               <td>{{$v['bhz_cl_num']}}</td>
                           </tr>
                        @endif
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