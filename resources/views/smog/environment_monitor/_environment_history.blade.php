

<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="10" class="hidden-xs hidden-sm">序号</th>
            <th width="50">设备</th>
            <th width="50">标段</th>
            <th width="50">PM2.5<br/>（24小时周期内平均不超过75&mu;g&frasl;m&sup3;）</th>
            <th width="50">PM10<br/>（24小时周期内不超过150&mu;g&frasl;m&sup3;）</th>
            <th width="50">温度(℃)</th>
            <th width="50">相对湿度(%)</th>
            <th width="50">噪音(db)</th>
            <th width="50">监测位置</th>
            <th width="100">监测时间</th>
            <th width="100" class="hidden-xs hidden-sm">上传日期</th>
        </tr>
        </thead>
        <tbody>
           @if($data)
             @foreach($data as $k=>$v)
                 <tr class="text-c">
                    <td class="hidden-xs hidden-sm">@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                    <td>{{$v['device']['name']}}</td>
                    <td>{{$v['section']['name']}}</td>
                    <td>{{$v['pm25']}}</td>
                    <td>{{$v['pm10']}}</td>
                    <td>{{$v['temperature']}}</td>
                    <td>{{$v['moisture']}}</td>
                    <td>{{$v['noise']}}</td>
                    <td>{{$v['place']}}</td>
                    <td>{{$v['datetime']}}</td>
                    <td  class="hidden-xs hidden-sm">{{$v['created_at']}}</td>
                 </tr>
             @endforeach
           @endif
        </tbody>
    </table>
    @if(isset($last_page) && !array_key_exists('d',$search))
    @if($last_page>1)
        @include('admin.layouts.page')
    @endif
    @endif
    @if(isset($hint))
        <span class="c-red f-20">{{$hint}}</span>
    @endif
</div>