
<div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l">
    <a class="btn open-add btn-primary radius" data-title="录入今日征地拆迁数据" data-url="{{url('remove/remove_day_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>录入今日拆迁数据</a>
  </span>
</div>

<div class=" col-xs-pull-12 col-sm-pull-12 mt-20" class="padding-left: 0;">
    <div>
        <div >
            <table id="table_list" class="table table-border table-bordered">
                <thead>
                <tr class="text-c">
                    <th width="40" class="hidden-xs hidden-sm">序号</th>
                    <th width="100" class="hidden-xs hidden-sm">时间</th>
                    <th width="80">合同段</th>
                    <th  class="hidden-xs hidden-sm">用地今日交付(亩)</th>
                    <th  class="hidden-xs hidden-sm">房屋今日拆迁(户)</th>
                    <th  class="hidden-xs hidden-sm">铁塔今日迁改(座)</th>
                    <th  class="hidden-xs hidden-sm">双杆今日迁改(处)</th>
                    <th  class="hidden-xs hidden-sm">地埋电缆今日迁改(米)</th>
                    <th  class="hidden-xs hidden-sm">输水管道今日迁改(米)</th>
                    <th  class="hidden-xs hidden-sm">天然气管道今日迁改</th>
                    <th  class="hidden-xs hidden-sm">特殊拆除物今日拆除(处)</th>
                </tr>
                </thead>
                <tbody>
                  @if($data)
                    @foreach($data as $v)
                        <tr class="text-c">
                           <td>{{$from++}}</td>
                           <td>{{date('Y-m-d',$v['time'])}}</td>
                            <td>{{$v['section']['name']}}</td>
                            <td>{{$v['occupation_day']}}</td>
                            <td>{{$v['house_day']}}</td>
                            <td>{{$v['pylon_day']}}</td>
                            <td>{{$v['parallels_day']}}</td>
                            <td>{{$v['optical_cable_day']}}</td>
                            <td>{{$v['water_pipe_day']}}</td>
                            <td>{{$v['natural_gas_pipeline_day']}}</td>
                            <td>{{$v['special_remove_day']}}</td>
                        </tr>
                    @endforeach
                  @endif
                </tbody>
            </table>
            @if($last_page>1)
                @include('admin.layouts.page')
            @endif
        </div>
    </div>
</div>