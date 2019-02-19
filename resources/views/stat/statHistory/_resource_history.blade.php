
@if($search['type']==1)
<div class=" col-xs-12 col-sm-12 mt-10" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40">序号</th>
                    <th width="100">时间</th>
                    <th width="100" >合同段</th>
                    <th width="80">位置</th>
                    <th width="50">掌子面开挖(人)</th>
                    <th width="50">初期支护(人)</th>
                    <th width="50">仰拱开挖(人)</th>
                    <th width="50">仰拱浇筑(人)</th>
                    <th width="50">防水板铺挂(人)</th>
                    <th width="50">二衬施工(人)</th>
                </tr>
                </thead>
                <tbody>
                   @if($data)
                     @foreach($data as $k=>$v)
                         <tr class="text-c">
                            <td>@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                            <td>{{date('Y-m-d',$v['time'])}}</td>
                            <td>{{$v['section']['name']}}</td>
                            <td>
                               @if($v['site']==1)
                                   左洞
                               @elseif($v['site']==2)
                                   右洞
                               @endif
                            </td>
                            <td>{{$v['zzmkw']}}</td>
                             <td>{{$v['cqzh']}}</td>
                             <td>{{$v['ygkw']}}</td>
                             <td>{{$v['ygjz']}}</td>
                             <td>{{$v['fsbpg']}}</td>
                             <td>{{$v['ecjz']}}</td>
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
@elseif($search['type']==2)
    <div class=" col-xs-12 col-sm-12 mt-10" class="padding-left: 0; ">
        <div class="panel panel-info">
            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="40">序号</th>
                        <th width="100">时间</th>
                        <th width="100" >合同段</th>
                        <th width="80">位置</th>
                        <th width="50">湿喷机械手(台)</th>
                        <th width="50">防水板铺挂台车(台)</th>
                        <th width="50">二衬台车(台)</th>
                        <th width="50">二衬养生台车(台)</th>
                        <th width="50">自行式整体栈桥液压仰拱模板台车(台)</th>
                        <th width="50">液压水沟电缆槽台车(台)</th>
                        <th width="50">雾炮车(台)</th>
                        <th width="50">挖掘机(台)</th>
                        <th width="50">装载机(台)</th>
                        <th width="50">自卸车(台)</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($data)
                          @foreach($data as $k=>$v)
                              <tr class="text-c">
                                <td>@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                                <td>{{date('Y-m-d',$v['time'])}}</td>
                                <td>{{$v['section']['name']}}</td>
                                <td>
                                    @if($v['site']==1)
                                        左洞
                                    @elseif($v['site']==2)
                                        右洞
                                    @endif
                                </td>
                                <td>{{$v['spjxs']}}</td>
                                <td>{{$v['fsbpgtc']}}</td>
                                <td>{{$v['ectc']}}</td>
                                <td>{{$v['ecystc']}}</td>
                                <td>{{$v['ygmbtc']}}</td>
                                <td>{{$v['sgdlctc']}}</td>
                                <td>{{$v['wpc']}}</td>
                                <td>{{$v['wjj']}}</td>
                                <td>{{$v['zzj']}}</td>
                                <td>{{$v['zxc']}}</td>
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
@endif