@if(Auth::user()->role==4)
<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs">
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="Hui-iconfont">&#xe600;</i>今日完成情况录入</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li class="text-c open-add" data-title="左洞今日完成情况录入" data-url="{{url('stat/today_add?type=1').'&section_id='.$section['id']}}"><a href="javascript:;">左洞</a></li>
      <li class="text-c open-add" data-title="右洞今日完成情况录入" data-url="{{url('stat/today_add?type=2').'&section_id='.$section['id']}}"><a href="javascript:;">右洞</a></li>
      <li class="text-c open-add" data-title="土方开挖今日完成情况录入" data-url="{{url('stat/today_add?type=3').'&section_id='.$section['id']}}"><a href="javascript:;">土方开挖</a></li>
    </ul>
  </span>
</div>
@endif

<div class=" col-xs-12 col-sm-12 mt-20" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-header text-c">白鹿原隧道{{$section['name']}}标段每日完成情况</div>
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="100" class="hidden-xs hidden-sm">时间</th>
            <th width="100">位置</th>
            <th width="100">施工长度(米)</th>
            <th >初期支护(米)</th>
            <th width="100" class="hidden-xs hidden-sm">仰拱开挖(米)</th>
            <th  class="hidden-xs hidden-sm">仰拱浇筑(米)</th>
            <th  class="hidden-xs hidden-sm">防水板铺挂</th>
            <th  class="hidden-xs hidden-sm">二衬浇筑</th>
            <th  class="hidden-xs hidden-sm">二衬钢筋绑扎</th>
            <th  class="hidden-xs hidden-sm">土方开挖(万立方米)</th>

            <th >操作</th>
            </tr>
            </thead>
                <tbody>
          @if($data)
            @foreach($data as $v)
            <tr class="text-c">
             <td>{{$start_num++}}</td>
             <td>{{date('Y-m-d',$v['lf_time'])}}</td>
             <td>@if($v['site']==1)左洞@else右洞@endif</td>
             <td>{{$v['adjj']}}</td>
             <td>{{$v['cqzh']}}</td>
             <td>{{$v['ygkw']}}</td>
             <td>{{$v['ygjz']}}</td>
             <td>{{$v['fsbpg']}}</td>
             <td>{{$v['ecjz']}}</td>
             <td>{{$v['gjbz']}}</td>
             <td>
                 @if($v['site']==1)
                {{$v['tfkw']}}
                 @endif
             </td>
             <td>
             <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="{{date('Y-m-d',$v['lf_time'])}}当日进度报告" data-url="{{url('stat/get_today_report').'?time='.$v['lf_time'].'&section_id='.$v['lf_section_id']}}">查看当日进度报告</a>
             </td>
            </tr>
            @endforeach
           @endif

            </tbody>
            </table>

        </div>
    </div>
</div>