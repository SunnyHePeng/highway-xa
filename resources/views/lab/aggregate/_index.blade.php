

<div class="mt-30 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40">序号</th>
            <th width="100">监理合同段</th>
            <th width="100">标段名称</th>
            <th width="150">操作</th>
        </tr>
        </thead>
        <tbody>
          @if($device_data)
            @foreach($device_data as$k=>$v)
              <tr class="text-c">
                  <td>{{$k+1}}</td>
                  <td>{{$v->sup->name}}</td>
                  <td>{{$v->section->name}}</td>
                  <td>
                      <input class="btn btn-primary size-M radius mr-30 open-video-data" type="button" data-title="{{$v->section->name.'-集料试验实时视频'}}" data-url="{{url('lab/aggregate_video').'/'.$v['id']}}" value="实时视频">
                      <input class="btn btn-primary size-M radius mr-10 open-video-data" data-title="{{$v->section->name.'-集料试验数据'}}" data-url="{{url('lab/get_aggregate_data').'/'.$v['id']}}" type="button" value="试验数据">
                  </td>
              </tr>
            @endforeach
          @endif
        </tbody>
    </table>
</div>