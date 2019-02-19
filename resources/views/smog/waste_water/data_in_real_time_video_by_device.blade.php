@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <div>
        <form method="get" name="search">
            时间：
            <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">

            <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

        </form>
    </div>
    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="10" class="hidden-xs hidden-sm" rowspan="2">序号</th>
                <th width="100" rowspan="2">监测时间</th>
                <th width="80">进水BOD(生化需氧量)</th>
                <th width="80">出水BOD(生化需氧量)</th>
                <th width="50">pH(酸碱性)</th>
                <th width="50">色度(稀释倍数)</th>
                <th width="100" rowspan="2">监测位置</th>
            </tr>
            <tr class="text-c">
                <th width="80">(10~20)mg/L</th>
                <th width="80">(10~20)mg/L</th>
                <th width="50">6~9</th>
                <th width="50">30~50</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $v)
                    <tr class="text-c">
                        <td>{{$from++}}</td>
                        <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                        <td>{{$v['enter_BOD']}}</td>
                        <td>{{$v['exit_BOD']}}</td>
                        <td>{{$v['pH']}}</td>
                        <td>{{$v['chrominance']}}</td>
                        <td>{{$v['place']}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="13">暂时还没有上传污水处理数据</td>
                </tr>
            @endif
            </tbody>
        </table>
        @if($last_page>1)
            @include('admin.layouts.page')
        @endif
    </div>


@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
        list.openIframe();
    </script>
@stop