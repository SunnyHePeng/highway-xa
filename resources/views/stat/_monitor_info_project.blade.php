<div class="mb-10 ml-10">
    <form method="get" name="search">
        时间：
        <input name="date" placeholder="请输入时间" value="@if(isset($search_date)){{$search_date}}@endif" type="text" onfocus="WdatePicker()" id="date" class="input-text Wdate">
        <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray noprint">
    <span class="ml-10 export-file export-dy open-print" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
</div>
<!--startprint-->
<div class=" col-xs-11 col-sm-11 mt-20" class="padding-left: 0; ">
    <div class="panel panel-info">
        <div class="panel-header text-c">
            <h3 class="text-c" style="position: relative;left: 0;top: 0;">白鹿原隧道监控量测统计表 <span style="font-size: 14px;position: absolute;right: 0;bottom: 0;"></span></h3>
                @if($data)
                   @if(!empty($data[19]))
                    <h5 class="text-l">统计时间：{{date('Y-m-d',$data[19]['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                   @elseif(!empty($data[20]))
                    <h5 class="text-l">统计时间：{{date('Y-m-d',$data[20]['time'])}}(统计数据为前日早晨8:00---当日早晨8:00)</h5>
                   @endif
                @endif

        </div>
        <div class="panel-body" class="padding: 0;">
            <table id="table_list" class="table table-border table-bordered">
                <thead>
                <tr class="text-c">
                    <th width="40" rowspan="3">序号</th>
                    <th width="100"  rowspan="3">监测项目</th>
                    <th width="160" colspan="2">LJ-13合同段</th>
                    <th width="160" colspan="2">LJ-14合同段</th>
                    <th width="200" rowspan="3">备注(异常原因)</th>
                </tr>
                <tr class="text-c">
                    <th width="80" >左洞状态</th>
                    <th width="80" >右洞状态</th>
                    <th width="80">左洞状态</th>
                    <th width="80">右洞状态</th>
                </tr>
                <tr class="text-c">
                    <th width="80">桩号:@if($data && !empty($data[19])){{$data[19]['l_stake_number']}}@endif</th>
                    <th width="80">桩号:@if($data && !empty($data[19])){{$data[19]['r_stake_number']}}@endif</th>
                    <th width="80">桩号:@if($data && !empty($data[20])){{$data[20]['l_stake_number']}}@endif</th>
                    <th width="80">桩号:@if($data && !empty($data[20])){{$data[20]['r_stake_number']}}@endif</th>
                </tr>
                </thead>
                <tbody>
                  <tr class="text-c">
                      <td>1</td>
                      <td>洞内外观察</td>
                      <td>
                          @if($data)
                             @if(!empty($data[19]))
                                 @if($data[19]['l_dnwgc_status']==1)
                                   正常
                                 @elseif($data[19]['l_dnwgc_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                             @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  @if($data[19]['r_dnwgc_status']==1)
                                      正常
                                  @elseif($data[19]['r_dnwgc_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  @if($data[20]['l_dnwgc_status']==1)
                                      正常
                                  @elseif($data[20]['l_dnwgc_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  @if($data[20]['r_dnwgc_status']==1)
                                      正常
                                  @elseif($data[20]['r_dnwgc_status']==2)
                                      <span class="c-red">异常</span>
                                  @endif
                              @endif
                          @endif
                      </td>
                      <td>
                         @if($data)
                           @if(!empty($data[19]))
                             @if(!empty($data[19]['l_dnwgc_remark']))
                                 LJ-13合同段左洞：{{$data[19]['l_dnwgc_remark']}}<br/>
                             @endif
                                 @if(!empty($data[19]['r_dnwgc_remark']))
                                     LJ-13合同段右洞：{{$data[19]['r_dnwgc_remark']}}<br/>
                                 @endif
                           @endif
                               @if(!empty($data[20]))
                                   @if(!empty($data[20]['l_dnwgc_remark']))
                                       LJ-14合同段左洞：{{$data[20]['l_dnwgc_remark']}}<br/>
                                   @endif
                                   @if(!empty($data[20]['r_dnwgc_remark']))
                                       LJ-14合同段右洞：{{$data[20]['r_dnwgc_remark']}}<br/>
                                   @endif
                               @endif
                         @endif
                      </td>
                  </tr>
                  <tr class="text-c">
                      <td>2</td>
                      <td>洞内收敛(警戒值5mm)</td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['l_zbwy_measure_value'].'mm'}}</span>
                                  <sapn>
                                      {{'('}}
                                      @if($data[19]['l_zbwy_status']==1)
                                          正常
                                      @elseif($data[19]['l_zbwy_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </sapn>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['r_zbwy_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[19]['r_zbwy_status']==1)
                                          正常
                                      @elseif($data[19]['r_zbwy_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['l_zbwy_measure_value'].'mm'}}</span>
                                  <sapn>
                                      {{'('}}
                                      @if($data[20]['l_zbwy_status']==1)
                                          正常
                                      @elseif($data[20]['l_zbwy_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </sapn>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['r_zbwy_measure_value'].'mm'}}</span>
                                  <sapn>
                                      {{'('}}
                                      @if($data[20]['r_zbwy_status']==1)
                                          正常
                                      @elseif($data[20]['r_zbwy_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </sapn>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  @if(!empty($data[19]['l_zbwy_remark']))
                                      LJ-13合同段左洞：{{$data[19]['l_zbwy_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[19]['r_zbwy_remark']))
                                      LJ-13合同段右洞：{{$data[19]['r_zbwy_remark']}}<br/>
                                  @endif
                              @endif
                              @if(!empty($data[20]))
                                  @if(!empty($data[20]['l_zbwy_remark']))
                                      LJ-14合同段左洞：{{$data[20]['l_zbwy_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[20]['r_zbwy_remark']))
                                      LJ-14合同段右洞：{{$data[20]['r_zbwy_remark']}}<br/>
                                  @endif
                              @endif
                          @endif
                      </td>
                  </tr>
                  <tr class="text-c">
                      <td>3</td>
                      <td>拱顶下沉(警戒值15mm)</td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['l_gdxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[19]['l_gdxc_status']==1)
                                          正常
                                      @elseif($data[19]['l_gdxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['r_gdxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[19]['r_gdxc_status']==1)
                                          正常
                                      @elseif($data[19]['r_gdxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['l_gdxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[20]['l_gdxc_status']==1)
                                          正常
                                      @elseif($data[20]['l_gdxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['r_gdxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[20]['r_gdxc_status']==1)
                                          正常
                                      @elseif($data[20]['r_gdxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  @if(!empty($data[19]['l_gdxc_remark']))
                                      LJ-13合同段左洞：{{$data[19]['l_gdxc_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[19]['r_gdxc_remark']))
                                      LJ-13合同段右洞：{{$data[19]['r_gdxc_remark']}}<br/>
                                  @endif
                              @endif
                              @if(!empty($data[20]))
                                  @if(!empty($data[20]['l_gdxc_remark']))
                                      LJ-14合同段左洞：{{$data[20]['l_gdxc_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[20]['r_gdxc_remark']))
                                      LJ-14合同段右洞：{{$data[20]['r_gdxc_remark']}}<br/>
                                  @endif
                              @endif
                          @endif
                      </td>
                  </tr>
                  <tr class="text-c">
                      <td>4</td>
                      <td>地表下沉(警戒值15mm)</td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['l_dbxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[19]['l_dbxc_status']==1)
                                          正常
                                      @elseif($data[19]['l_dbxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  <span class="f-16">{{$data[19]['r_dbxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[19]['r_dbxc_status']==1)
                                          正常
                                      @elseif($data[19]['r_dbxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['l_dbxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[20]['l_dbxc_status']==1)
                                          正常
                                      @elseif($data[20]['l_dbxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[20]))
                                  <span class="f-16">{{$data[20]['r_dbxc_measure_value'].'mm'}}</span>
                                  <span>
                                      {{'('}}
                                      @if($data[20]['r_dbxc_status']==1)
                                          正常
                                      @elseif($data[20]['r_dbxc_status']==2)
                                          <span class="c-red">超限</span>
                                      @endif
                                      {{')'}}
                                  </span>
                              @endif
                          @endif
                      </td>
                      <td>
                          @if($data)
                              @if(!empty($data[19]))
                                  @if(!empty($data[19]['l_dbxc_remark']))
                                      LJ-13合同段左洞：{{$data[19]['l_dbxc_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[19]['r_dbxc_remark']))
                                      LJ-13合同段右洞：{{$data[19]['r_dbxc_remark']}}<br/>
                                  @endif
                              @endif
                              @if(!empty($data[20]))
                                  @if(!empty($data[20]['l_dbxc_remark']))
                                      LJ-14合同段左洞：{{$data[20]['l_dbxc_remark']}}<br/>
                                  @endif
                                  @if(!empty($data[20]['r_dbxc_remark']))
                                      LJ-14合同段右洞：{{$data[20]['r_dbxc_remark']}}<br/>
                                  @endif
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
