
<div class=" col-xs-11 col-sm-11 mt-10">
    <div class="panel panel-info">
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40" class="hidden-xs hidden-sm" rowspan="2">序号</th>
                    <th width="150" rowspan="2">时间</th>
                    <th width="100" rowspan="2">合同段</th>
                    <th colspan="5" width="500">左洞</th>
                    <th colspan="5" width="500">右洞</th>
                </tr>
                <tr class="text-c">
                    <td width="100">桩号</td>
                   <td width="100">洞内外观察</td>
                   <td width="100">洞内收敛</td>
                   <td width="100">拱顶下沉</td>
                   <td width="100">地表下沉</td>
                    <td width="100">桩号</td>
                    <td width="100">洞内外观察</td>
                    <td width="100">洞内收敛</td>
                    <td width="100">拱顶下沉</td>
                    <td width="100">地表下沉</td>
                </tr>
                </thead>
                <tbody>
                  @if($data)
                    @foreach($data as $k=>$v)
                        <tr class="text-c">
                           <td class="hidden-xs hidden-sm">@if(isset($from)){{$from++}}@else{{$k+1}}@endif</td>
                           <td>{{date('Y-m-d',$v['time'])}}</td>
                           <td>{{$v['section']['name']}}</td>
                           <td>{{$v['l_stake_number']}}</td>
                           <td>
                               @if($v['l_dnwgc_status']==1)
                                   正常
                               @else
                                   异常{{$v['l_dnwgc_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['l_zbwy_measure_value']}}mm
                               @if($v['l_zbwy_status']==1)
                                   (正常)
                               @else
                                   {{$v['l_zbwy_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['l_gdxc_measure_value']}}mm
                               @if($v['l_gdxc_status']==1)
                                   (正常)
                               @else
                                   {{$v['l_gdxc_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['l_dbxc_measure_value']}}mm
                               @if($v['l_dbxc_status']==1)
                                 (正常)
                               @else
                                   {{$v['l_dbxc_remark']}}
                               @endif
                           </td>
                           <td>{{$v['r_stake_number']}}</td>
                           <td>
                               @if($v['r_dnwgc_status']==1)
                                   正常
                               @else
                                   异常{{$v['r_dnwgc_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['r_zbwy_measure_value']}}mm
                               @if($v['r_zbwy_status']==1)
                                 (正常)
                               @else
                                  {{$v['r_zbwy_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['r_gdxc_measure_value']}}mm
                               @if($v['r_gdxc_status']==1)
                                   (正常)
                               @else
                                   {{$v['r_gdxc_remark']}}
                               @endif
                           </td>
                           <td>
                               {{$v['r_dbxc_measure_value']}}mm
                               @if($v['r_dbxc_status']==1)
                                  (正常)
                               @else
                                  {{$v['r_dbxc_remark']}}
                               @endif
                           </td>
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