
<div class=" col-xs-12 col-sm-12" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-header text-c"></div>
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40" class="hidden-xs hidden-sm">序号</th>
                    <th width="100">时间</th>
                    <th width="100" >合同段</th>
                    <th width="50">位置</th>
                    <th width="50">暗洞掘进(米)</th>
                    <th width="50">初期支护(米)</th>
                    <th width="50" >仰拱开挖(米)</th>
                    <th  width="50">仰拱浇筑(米)</th>
                    <th width="50">防水板铺挂(米)</th>
                    <th width="50">二衬浇筑(米)</th>
                    <th width="50">二衬钢筋绑扎(米)</th>
                </tr>
                </thead>
                <tbody>
                  @if($data)
                       @foreach($data as $k=>$v)
                           <tr class="text-c">
                              <td class="hidden-xs hidden-sm">@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                              <td>{{date('Y-m-d',$v['time'])}}</td>
                              <td>{{$v['section']['name']}}</td>
                               <td>
                                   @if($v['site']==1)
                                       左洞
                                   @else
                                       右洞
                                   @endif
                               </td>
                               <td>{{$v['adjj']}}</td>
                               <td>{{$v['cqzh']}}</td>
                               <td>{{$v['ygkw']}}</td>
                               <td>{{$v['ygjz']}}</td>
                               <td>{{$v['fsbpg']}}</td>
                               <td>{{$v['ecjz']}}</td>
                               <td>{{$v['gjbz']}}</td>
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
        </div>
    </div>
</div>