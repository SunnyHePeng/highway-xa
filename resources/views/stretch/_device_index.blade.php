<div class="row cl mb-20">
    <div class="col-xs-12 col-sm-2">
        <div class="panel panel-primary">
            <div class="panel-header" style="text-align:center;">张拉设备</div>
            <a href="">
                <div class="panel-body">
                    总数 {{$device_number ? $device_number : 0}}台<br><br>
                    在线 {{$device_online_number ? $device_online_number : 0}}台
                </div>
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-2">
        <div class="panel panel-warning">
            <div class="panel-header" style="text-align:center;">当天操作信息</div>
            <a href="">
                <div class="panel-body">
                    张拉数 0<br>
                    报警数 0<br>
                    未处理报警 0
                </div>
            </a>
        </div>
    </div>
</div>

<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="80" class="hidden-xs hidden-sm hidden-md">监理名称</th>
            <th width="80">标段名称</th>
            <th width="80">所属梁场</th>
            <th width="80">设备名称</th>
            <th width="100">设备状态最新上报时间</th>
            <th width="100">张拉数据最新上报时间</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
           @if($device_data)
             @foreach($device_data as $k=>$v)
                 <tr class="text-c">
                   <td class="hidden-xs hidden-sm">{{$k+1}}</td>
                   <td class="hidden-xs hidden-sm hidden-md">{{$v->sup->name}}</td>
                   <td>{{$v->section->name}}</td>
                   <td>{{$v->beam_site->name}}</td>
                   <td>{{$v->name}}</td>
                   <td>{{$v->status_time ? date('Y-m-d H:i:s',$v->status_time) : ''}}</td>
                   <td>{{$v->last_time ? date('Y-m-d H:i:s',$v->last_time) : ''}}</td>
                   <td>
                       <input class="btn btn-primary radius size-S ml-10 open-data" data-title="张拉数据" data-url="{{url('stretch/stretch_data_by_device'.'/'.$v->id)}}" type="button" value="张拉数据">
                       <input class="btn btn-success radius size-S ml-10 open-video" data-title="实时视频" data-url="{{url('stretch/real_time_video'.'/'.$v->id)}}" type="button" value="实时视频">
                   </td>
                 </tr>
             @endforeach
           @endif
        </tbody>
    </table>
</div>