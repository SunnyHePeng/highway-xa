
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="10" class="hidden-xs hidden-sm" rowspan="2">序号</th>
            <th width="50" rowspan="2">监测时间</th>
            <th width="50" rowspan="2">标段</th>
            <th width="80" >进水BOD(生化需氧量)</th>
            <th width="80">出水BOD(生化需氧量)</th>
            <th width="50" >pH(酸碱性)</th>
            <th width="50">色度(稀释倍数)</th>
            <th width="100" rowspan="2">监测位置</th>
            <th width="100" class="hidden-xs hidden-sm" rowspan="2">上传时间</th>
        </tr>
        <tr class="text-c">
          <th width="80">(10~40)mg/L</th>
          <th width="80">(10~40)mg/L</th>
          <th width="50">6~9</th>
          <th width="50">30~50</th>
        </tr>
        </thead>
        <tbody>
            @if($data)
              @foreach($data as $k=>$v)
                  <tr class="text-c">
                     <td>@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                     <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                     <td>{{$v['section']['name']}}</td>
                     <td>{{$v['enter_BOD']}}</td>
                     <td>{{$v['exit_BOD']}}</td>
                     <td>{{$v['pH']}}</td>
                     <td>{{$v['chrominance']}}</td>
                     <td>{{$v['place']}}</td>
                     <td>{{date('Y-m-d H:i:s',$v['created_at'])}}</td>
                  </tr>
              @endforeach
            @else
              <tr>
                 <td colspan="13">没有污水处理数据</td>
              </tr>
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