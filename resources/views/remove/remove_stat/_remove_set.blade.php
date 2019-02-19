 <div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l">
    <a class="btn open-add btn-primary radius" data-title="添加总量信息" data-url="{{url('remove/remove_total_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加总量信息</a>
  </span>
    </div>

<div class=" col-xs-12 col-sm-12 mt-20" class="padding-left: 0;">
    <div>
        <div >
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40" class="hidden-xs hidden-sm">序号</th>
                    <th width="100" class="hidden-xs hidden-sm">监理</th>
                    <th width="100">合同段</th>
                    <th width="100">所属区县</th>
                    <th width="100">所属乡镇</th>
                    <th width="100" >里程桩号</th>
                    <th>长度(KM)</th>
                    <th>总占地(亩)</th>
                    <th>拆迁房屋总涉及(户)</th>
                    <th>铁塔总数(座)</th>
                    <th>双杆总数(处)</th>
                    <th>地埋电缆总量(米)</th>
                    <th>输水管道总量(米)</th>
                    <th>天然气管道总量</th>
                    <th>特殊拆除物总量(处)</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                 @if($remove_total_data)
                   @foreach($remove_total_data as $k=>$v)
                       <tr class="text-c" id="list-{{$v->id}}">
                          <td class="hidden-xs hidden-sm">{{$k+1}}</td>
                          <td class="hidden-xs hidden-sm">{{$v->supervision->name}}</td>
                          <td>{{$v->section->name}}</td>
                          <td>{{$v->district_name}}</td>
                          <td>{{$v->town_name}}</td>
                          <td>{{$v->mileage_stake}}</td>
                          <td>{{$v->length}}</td>
                          <td>{{$v->occupation_total}}</td>
                          <td>{{$v->house_total}}</td>
                          <td>{{$v->pylon_total}}</td>
                          <td>{{$v->parallels_total}}</td>
                          <td>{{$v->optical_cable_total}}</td>
                          <td>{{$v->water_pipe_total}}</td>
                          <td>{{$v->natural_gas_pipeline_total}}</td>
                          <td>{{$v->special_remove_total}}</td>
                          <td>
                              <a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$v->id}}" data-url="{{url("remove/remove_total_del").'/'.$v->id}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                          </td>
                       </tr>
                   @endforeach
                 @endif
                </tbody>
            </table>

        </div>
    </div>
</div>