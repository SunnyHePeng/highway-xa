<div class="mt-10 ml-10">
    <form method="get" name="search">
        时间：
        <input name="date" placeholder="请输入时间" value="@if(isset($search_date)){{$search_date}}@endif" type="text" onfocus="WdatePicker()" id="date" class="input-text Wdate">
        <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

    </form>
</div>

@if(Auth::user()->role==4)
<div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l">
    <a class="btn open-add btn-primary radius" data-title="隧道监控量测信息录入" data-url="{{url('stat/monitor_add')}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>隧道监控量测信息录入</a>
  </span>
</div>
@endif

<div class=" mt-20 cl pd-5 bg-0 bk-gray noprint text-r">
    <span class="ml-10 export-file export-dy open-print" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
</div>
<!--startprint-->
<div class=" col-xs-10 col-sm-10 mt-20" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-header text-c">
            <h3 class="text-c" style="position: relative;left: 0;top: 0;">白鹿原隧道{{$section['name']}}合同段监控量测统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
            @if(isset($data) && !empty($data[0]))
            <h5 class="text-l">统计时间：{{date('Y-m-d',$data[0]['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
            @endif
        </div>
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="50" rowspan="2">序号</th>
                    <th width="100" rowspan="2">监测项目</th>
                    <th width="100">左洞状态</th>
                    <th width="100">右洞状态</th>
                    <th width="150" rowspan="2">备注(异常原因)</th>
                </tr>
                <tr class="text-c">
                    <th width="100">桩号：@if(!empty($data[0])){{$data[0]['l_stake_number']}}@endif</th>
                    <th width="100">桩号：@if(!empty($data[0])){{$data[0]['r_stake_number']}}@endif</th>
                </tr>
                </thead>
                <tbody>

                <tr class="text-c">
                    <td>1</td>
                    <td>洞内外观察</td>
                    <td>
                          @if(!empty($data[0]))
                              @if($data[0]['l_dnwgc_status']==1)
                                   正常
                              @elseif($data[0]['l_dnwgc_status']==2)
                                <span class="c-red">异常</span>
                              @endif
                          @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            @if($data[0]['r_dnwgc_status']==1)
                                正常
                            @elseif($data[0]['r_dnwgc_status']==2)
                                <span class="c-red">异常</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            @if(!empty($data[0]['l_dnwgc_remark']))
                                左洞：{{$data[0]['l_dnwgc_remark']}}<br>
                            @endif
                            @if(!empty($data[0]['r_dnwgc_remark']))
                                右洞：{{$data[0]['r_dnwgc_remark']}}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr class="text-c">
                    <td>2</td>
                    <td>洞内收敛(警戒值5mm)</td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['l_zbwy_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['l_zbwy_status']==1)
                                    正常
                                @elseif($data[0]['l_zbwy_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['r_zbwy_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['r_zbwy_status']==1)
                                    正常
                                @elseif($data[0]['r_zbwy_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            @if(!empty($data[0]['l_zbwy_remark']))
                                左洞：{{$data[0]['l_zbwy_remark']}}<br>
                            @endif
                            @if(!empty($data[0]['r_zbwy_remark']))
                                右洞：{{$data[0]['r_zbwy_remark']}}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr class="text-c">
                    <td>3</td>
                    <td>拱顶下沉(警戒值15mm)</td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['l_gdxc_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['l_gdxc_status']==1)
                                    正常
                                @elseif($data[0]['l_gdxc_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['r_gdxc_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['r_gdxc_status']==1)
                                    正常
                                @elseif($data[0]['r_gdxc_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            @if(!empty($data[0]['l_gdxc_remark']))
                                左洞：{{$data[0]['l_gdxc_remark']}}<br>
                            @endif
                            @if(!empty($data[0]['r_gdxc_remark']))
                                右洞：{{$data[0]['r_gdxc_remark']}}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr class="text-c">
                    <td>4</td>
                    <td>地表下沉(警戒值15mm)</td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['l_dbxc_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['l_dbxc_status']==1)
                                    正常
                                @elseif($data[0]['l_dbxc_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            <span class="f-16">{{$data[0]['r_dbxc_measure_value'].'mm'}}</span>
                            <span>{{'('}}@if($data[0]['r_dbxc_status']==1)
                                    正常
                                @elseif($data[0]['r_dbxc_status']==2)
                                    <span class="c-red">超限</span>
                                @endif{{')'}}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($data[0]))
                            @if(!empty($data[0]['l_dbxc_remark']))
                                左洞：{{$data[0]['l_dbxc_remark']}}<br>
                            @endif
                            @if(!empty($data[0]['r_dbxc_remark']))
                                右洞：{{$data[0]['r_dbxc_remark']}}
                            @endif
                        @endif
                    </td>
                </tr>

                </tbody>
            </table>
            <div class="hidden-xs hidden-sm">
                <p>填表说明：</p>
                <p>1、洞内观察无异常时填写正常，存在异常时描述地表及洞内是否有裂缝等实际异常情况；</p>
                <p>2、拱顶下沉、周边位移及地表观测，填写当日测量变化最大数据即可，格式为：数据（正常/超限）；</p>
                <p>3、备注里描述清楚出现异常的原因及异常段落里程。</p>
            </div>
        </div>
    </div>
</div>
<!--endprint-->