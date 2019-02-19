@extends('admin.layouts.iframe')

@section('container')
    <div class="cl pd-5 bg-1 bk-gray noprint">
        <span class="ml-10 export-file export-excel" onclick="layer.msg('正在导出数据,请稍后')" data-name="环境监测历史数据" data-obj="table_list" data-type="excel"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
    </div>
    <!--startprint-->
    <h3 class="text-c">
        @if($search)
          {{$search['start_date'].'~'.$search['end_date']}}环境监测数据
        @endif
        <small class="c-black f-12">
            @if($search['d']=='all')
                全部数据
            @else
                第{{$search['page']}}页数据
            @endif
        </small>
    </h3>
    @include('smog.environment_monitor._environment_history')
    <!--endprint-->

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
        list.init();
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