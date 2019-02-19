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
                <th width="10" class="hidden-xs hidden-sm">序号</th>
                <th width="100" >梁号</th>
                <th width="80">张拉开始时间</th>
                <th width="80">张拉顺序</th>
                <th width="80">张拉单位</th>
                <th width="50">预制梁场</th>
                <th width="100">构件类型</th>
                <th width="80">操作</th>
            </tr>
            </thead>
            <tbody>
               @if($data)
                 @foreach($data as $v)
                     <tr class="text-c">
                        <td class="hidden-xs hidden-sm">{{$from++}}</td>
                        <td>{{$v['girder_number']}}</td>
                        <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                         <td>{{$v['stretch_order']}}</td>
                         <td>{{$v['stretch_unit']}}</td>
                         <td>{{$v['precasting_yard']}}</td>
                         <td>{{$v['component_type']}}</td>
                         <td>
                             <input class="btn radius btn-secondary size-MINI open-detail" data-title="详细数据" type="button" data-url="{{url('stretch/stretch_detail'.'/'.$v['id'])}}" value="详细数据">
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
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
        list.openIframe();
        $(".open-detail").on('click',function(){
            var url=$(this).attr('data-url');
            var title=$(this).attr('data-title');
            layer.open({
                type: 2,
                title:title,
                area: ['95%','95%'],
                content: url
            });
        });
    </script>
@stop