@extends('admin.layouts.default')

@section('container')
    <div class="text-l">
        <form  class="mb-10" id="search_form" data="year,month,pro_id,sec_id" method="get" data-url="{{url('lab/lab_data_info')}}">
            <span class="select-box" style="width:auto; padding: 3px 5px;">
        <select name="year" id="year" class="select select2">
             <option value="0">请选择</option>
             <option value="2018"@if(array_key_exists('year',$search) && $search['year']==2018) selected="selected"@endif>2018</option>
             <option value="2019" @if(array_key_exists('year',$search) && $search['year']==2019) selected="selected"@endif>2019</option>
             <option value="2020" @if(array_key_exists('year',$search) && $search['year']==2020) selected="selected"@endif>2020</option>
            </select>
        </span>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <span class="select-box" style="width:auto; padding: 3px 5px;">
        <select name="month" id="month" class="select select2">
             <option value="0">请选择</option>
              <option value="01"@if(array_key_exists('month',$search) && $search['month']=='01') selected="selected"@endif >01月</option>
              <option value="02" @if(array_key_exists('month',$search) && $search['month']=='02') selected="selected"@endif>02月</option>
              <option value="03" @if(array_key_exists('month',$search) && $search['month']=='03') selected="selected"@endif>03月</option>
              <option value="04" @if(array_key_exists('month',$search) && $search['month']=='04') selected="selected"@endif>04月</option>
              <option value="05" @if(array_key_exists('month',$search) && $search['month']=='05') selected="selected"@endif>05月</option>
              <option value="06" @if(array_key_exists('month',$search) && $search['month']=='06') selected="selected"@endif>06月</option>
              <option value="07" @if(array_key_exists('month',$search) && $search['month']=='07') selected="selected"@endif>07月</option>
              <option value="08" @if(array_key_exists('month',$search) && $search['month']=='08') selected="selected"@endif>08月</option>
              <option value="09" @if(array_key_exists('month',$search) && $search['month']=='09') selected="selected"@endif>09月</option>
              <option value="10" @if(array_key_exists('month',$search) && $search['month']=='10') selected="selected"@endif>10月</option>
              <option value="11" @if(array_key_exists('month',$search) && $search['month']=='11') selected="selected"@endif>11月</option>
              <option value="12" @if(array_key_exists('month',$search) && $search['month']=='12') selected="selected"@endif>12月</option>
            </select>
        </span>月
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>
    <div class="col-xs-12 col-sm-12 pr-25 bg-1 bk-gray mt-10">
        <span class=" export-file export-dy open-print r" data-name="日投入人员，设备统计表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
    </div>
    <!--startprint-->
    <div class=" col-xs-12 col-sm-12 mt-20" class="padding-left: 0; ">
        <div class="">
            <h1 class="text-c ">白鹿原隧道施工情况月统计表</h1>
            <div class="panel-body" class="padding: 0;">
                <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                    <thead >
                    <tr class="text-c">
                        <th class="f-26" width="200" rowspan="2" height="80">位置</th>
                        <th  class="f-26" colspan="3" height="40">LJ-13</th>
                        <th class="f-26" colspan="3" height="40">LJ-14</th>
                        <th class="f-26" rowspan="2" height="80">月份</th>
                        <th  class="f-26" rowspan="2" height="80" width="80">年</th>
                    </tr>
                    <tr class="text-c">
                        <th class="f-24" height="40">总人数</th>
                        <th class="f-24" height="40">日均人数</th>
                        <th class="f-24" height="40">日均工作时间</th>
                        <th class="f-24" height="40">总人数</th>
                        <th class="f-24" height="40">日均人数</th>
                        <th class="f-24" height="40">日均工作时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($month_data)
                        @foreach($month_data as $v)
                      <tr class="text-c">
                        <td class="f-20" height="50">左洞施工情况</td>
                         <td class="f-20" height="50">
                           {{$v->l_people_month_13}}
                         </td>
                         <td class="f-20" height="50">
                             {{round($v->l_people_month_13/$v->number_of_days,0)}}
                         </td>
                         <td class="f-20" height="50">
                             {{round($v->l_construction_duration_month_13/$v->number_of_days,0)}}
                         </td>
                         <td class="f-20" height="50">
                             {{$v->l_people_month_14}}
                         </td>
                         <td class="f-20" height="50">
                             {{round($v->l_people_month_14/$v->number_of_days,0)}}
                         </td>
                         <td class="f-20" height="50">
                             {{round($v->l_construction_duration_month_14/$v->number_of_days,0)}}
                         </td>
                         <td class="f-20" rowspan="3" height="50">
                             {{$v->month}}
                         </td>
                         <td class="f-20" rowspan="3" height="50">
                             {{$v->year}}
                         </td>
                      </tr>
                      <tr class="text-c">
                         <td class="f-20" height="50">右洞施工情况</td>
                          <td class="f-20" height="50">
                              {{$v->r_people_month_13}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->r_people_month_13/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->r_construction_duration_month_13/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{$v->r_people_month_14}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->r_people_month_14/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->r_construction_duration_month_14/$v->number_of_days,0)}}
                          </td>
                      </tr>
                      <tr class="text-c">
                          <td class="f-20" height="50">钢筋加工厂</td>
                          <td class="f-20" height="50">
                              {{$v->reinforcement_yard_month_13}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->reinforcement_yard_month_13/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->reinforcement_yard_construction_duration_month_13/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{$v->reinforcement_yard_month_14}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->reinforcement_yard_month_14/$v->number_of_days,0)}}
                          </td>
                          <td class="f-20" height="50">
                              {{round($v->reinforcement_yard_construction_duration_month_14/$v->number_of_days,0)}}
                          </td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!--endprint-->
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        //        list.init();
        $(function(){
            $('.open-print').on('click',function(){
                bdhtml = window.document.body.innerHTML;
                sprnstr = "<!--startprint-->";
                eprnstr = "<!--endprint-->";
                prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
                prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
                window.document.body.innerHTML = prnhtml;
                window.print();
                location.reload();
            });
        });
    </script>
@stop
