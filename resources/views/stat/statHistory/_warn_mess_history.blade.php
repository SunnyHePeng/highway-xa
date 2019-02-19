
<div class=" col-xs-12 col-sm-12 mt-10" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40" class="hidden-xs hidden-sm">序号</th>
                    <th width="100">时间</th>
                    <th width="100">项目</th>
                    <th width="100" >合同段</th>

                    <th width="100">报警数量</th>
                    <th width="100">处理数量</th>
                </tr>
                </thead>
                <tbody>
                  @if($data)
                    @foreach($data as$k=>$v)
                        <tr class="text-c">
                          <td>@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                          <td>{{date('Y-m-d',$v['time'])}}</td>
                          <td>
                              @if($v['module_id']==3)
                                  试验室报警
                              @elseif($v['module_id']==4)
                                  拌合站报警
                              @endif
                          </td>
                            <td>{{$v['section_name']}}</td>
                          <td>{{$v['bj_num']}}</td>
                          <td>{{$v['cl_num']}}</td>
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