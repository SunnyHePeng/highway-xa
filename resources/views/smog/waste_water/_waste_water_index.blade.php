{{--<h1 class="text-c">正在开发....</h1>--}}
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="80" class="hidden-xs hidden-sm hidden-md">监理单位</th>
            <th width="80">标段名称</th>
            <th width="80">设备名称</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
           @if($device_data)
             @foreach($device_data as$k=> $v)
                <tr class="text-c">
                   <td>{{$k+1}}</td>
                   <td>{{$v['sup']['name']}}</td>
                   <td>{{$v['section']['name']}}</td>
                   <td>{{$v['name']}}</td>
                   <td>
                       <input class="btn btn-primary radius size-S open-data" data-title="污水处理数据" data-url="{{url('smog/get_waste_water_data').'/'.$v['id']}}" type="button" value="污水处理数据">
                   </td>
                </tr>
             @endforeach
           @endif
        </tbody>
    </table>
</div>