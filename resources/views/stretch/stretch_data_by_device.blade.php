@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-c">
        <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="50">序号</th>
                <th width="80">梁号</th>
                <th width="100" >张拉开始时间</th>
                <th width="80" class="hidden-xs">张拉单位</th>
                <th width="80" class="hidden-xs">监理单位</th>
                <th width="80" class="hidden-xs">砼设计强度(MPa)</th>
                <th width="50">砼强度(MPa)</th>
                <th width="100" class="hidden-xs hidden-sm">张拉顺序</th>
                <th width="100">工程名称</th>
                <th width="80">预制梁场</th>
                <th width="50">张拉工艺</th>
                <th width="50">构件类型</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
              @if($data)
                @foreach($data as $k=>$v)
                    <tr class="text-c">
                        <td>{{$k+1}}</td>
                        <td>{{$v['girder_number']}}</td>
                        <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                        <td class="hidden-xs">{{$v['stretch_unit']}}</td>
                        <td class="hidden-xs">{{$v['supervisor_unit']}}</td>
                        <td>{{$v['concrete_design_intensity']}}</td>
                        <td>{{$v['concrete_reality_intensity']}}</td>
                        <td class="hidden-xs hidden-sm">{{$v['stretch_order']}}</td>
                        <td>{{$v['engineering_name']}}</td>
                        <td>{{$v['precasting_yard']}}</td>
                        <td>{{$v['stretch_craft']}}</td>
                        <td>{{$v['component_type']}}</td>
                        <td>
                            <input class="btn radius btn-secondary size-S open-detail" data-title="详细数据" type="button" data-url="{{url('stretch/stretch_detail'.'/'.$v['id'])}}" value="详细数据">
                        </td>
                    </tr>
                @endforeach
              @endif
            </tbody>
        </table>
        @if($last_page>1)
            @include('admin.layouts.page')
        @endif
    </div>

@stop


@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>

    <script type="text/javascript">
        list.init();
        $(".open-detail").on('click',function(){
            var url=$(this).attr('data-url');
            var title=$(this).attr('data-title');
            layer.open({
                type: 2,
                title:title,
                area: ['90%','90%'],
                content: url
            });
        });

    </script>
@stop