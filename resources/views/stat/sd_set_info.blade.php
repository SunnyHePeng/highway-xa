<div class="cl mt-10">
	<span class="l">
		<a class="btn btn-primary radius add-a" data-for="project" data-title="添加总量信息" href="javascript:;" data-url="{{url('stat/sd_set_add')}}"><i class="Hui-iconfont">&#xe600;</i>添加总量信息</a>
	</span>
</div>

<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="80" class="hidden-xs hidden-sm hidden-md">标段</th>
            <th width="80">位置</th>
            <th width="80">项目</th>
            <th width="80">总量</th>
        </tr>
        </thead>
        <tbody>
         @if($total)
             @foreach($total as $k=>$v)
          <tr class="text-c">
             <td>{{$k+1}}</td>
             <td>{{$v['section']['name']}}</td>
             <td>
                 @if($v['site']==1)
                     左洞
                 @elseif($v['site']==2)
                     右洞
                 @elseif($v['site']==0)
                     土方开挖
                 @endif
             </td>
              <td>{{$v['type_name']}}</td>
             <td>{{$v['zl']}}</td>
          </tr>
             @endforeach
         @endif
        </tbody>
    </table>
</div>