@extends('admin.layouts.iframe')

@section('container')
    <meta http-equiv="refresh" content="180">
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <div class="col-xs-12">
        <form id="search_form" style="float: left;" data="search_sylx,start_date,end_date" method="get" url="">

        试验类型
        <span class="select-box" style="width:auto; padding: 3px 5px;">
        <select name="search_sylx" id="search_sylx" class="select select2">
        <option value="0" @if($search && $search['search_sylx']==0) selected="selected"@endif>全部</option>
        <option value="14" @if($search && $search['search_sylx']==14) selected="selected" @endif>粗集料试验</option>
        <option value="15" @if($search && $search['search_sylx']==15) selected="selected"@endif>细集料试验</option>
        </select>
        </span>
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
        <div  class="col-xs-12 dataTables_wrapper mt-5">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class=" table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
        <th width="40">序号</th>
        <th width="100">组号</th>
        <th width="100">试验类型</th>
        <th width="130">试验时间</th>
        <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($data)
        @foreach($data as $v)
        <tr class="text-c">
        <td>{{$from++}}</td>
        <td>{{$v['syzh']}}</td>
        <td>
        @if($v['sylx']==14)
        粗集料试验
        @elseif($v['sylx']==15)
        细集料试验
        @endif
        </td>
        <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
        <td>
        <a style="text-decoration:none" class="mt-5 ml-5 btn btn-success radius size-S show-report" href="{{$v['reportFile']}}"  target="_blank" data-title="试验详细信息">试验报告</a>
        </td>
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
    <div class="col-xs-12 col-sm-6  pl-15 pr-5">
        <div class="panel panel-primary">
            <div class="panel-header" style="padding: 0;"><span class="f-18">压碎值试验最新数据</span></div>
            <div class="panel-body" style="padding: 0;">
                @if($ys_lab_info_data)
                    <div class="mt-10 dataTables_wrapper">
                        <table id="table_list" class="table table-border table-bordered table-sort">
                            <thead>
                            <tr class="text-c">
                                <th width="100">工程名称</th>
                                <th width="100">{{$ys_lab_info_data->gcmc}}</th>
                                <th width="100">工程部位/用途</th>
                                <th width="100">{{$ys_lab_info_data->gcbw}}</th>
                                <th width="100">试验粒径</th>
                                <th width="100">{{$ys_lab_info_data->sjgg}}</th>
                                <th width="100">进场单号</th>
                                <th width="150">{{$ys_lab_info_data->syzh}}</th>

                            </tr>
                            <tr class="text-c">
                                <th width="100">样品名称</th>
                                <th width="100">{{$ys_lab_info_data->sypz}}</th>
                                <th width="100">试验人员</th>
                                <th width="100">{{$ys_lab_info_data->syry}}</th>
                                <th width="100">试验类型</th>
                                <th width="100">粗集料试验</th>
                                <th width="100">试验日期</th>
                                <th width="150">{{date('Y-m-d H:i:s',$ys_lab_info_data->time)}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    @if($ys_data)
                        <div class=" dataTables_wrapper">
                            <table id="table_list" class="table table-border table-bordered table-sort">
                                <thead>
                                <tr class="text-c">
                                    <th width="100">粒级</th>
                                    <th width="100">{{$ys_data->lj}}</th>
                                    <th width="100">金属桶的质量(g)</th>
                                    <th width="50">{{$ys_data->tzl}}</th>
                                    <th width="100">金属桶+干燥石的质量(g)</th>
                                    <th width="50">{{$ys_data->stzl}}</th>
                                    <th width="100">碎料过筛孔径(mm)</th>
                                    <th width="50">{{$ys_data->gskj}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <span class="c-red">未匹配到对应的压碎值详情数据</span>
                    @endif
                    @if($ys_data)
                        <div class=" dataTables_wrapper">
                            <table id="table_list" class="table table-border table-bordered table-sort">
                                <thead>
                                <tr class="text-c">
                                    <th width="100">试样次数</th>
                                    <th width="100">干燥石料的质量(g)</th>
                                    <th width="100">通过2.36mm筛孔的细料质量(g)</th>
                                    <th width="50">石料压碎值(%)</th>
                                    <th width="100">石料压碎平均(%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-c">
                                    <td>1</td>
                                    <td rowspan="3">{{$ys_data->szl}}</td>
                                    <td>{{$ys_data->tgzl1}}</td>
                                    <td>{{$ys_data->ysz1}}</td>
                                    <td rowspan="3">{{$ys_data->yszcd}}</td>
                                </tr>
                                <tr class="text-c">
                                    <td>2</td>
                                    <td>{{$ys_data->tgzl2}}</td>
                                    <td>{{$ys_data->ysz2}}</td>
                                </tr>
                                <tr class="text-c">
                                    <td>3</td>
                                    <td>{{$ys_data->tgzl3}}</td>
                                    <td>{{$ys_data->ysz3}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <span class="c-red">暂时还没有压碎值数据</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 pl-15 pr-15">
        <div class="panel panel-secondary ">
            <div class="panel-header" style="padding: 0;"><span class="f-18">筛分试验最新数据</span></div>
            <div class="panel-body" style="padding: 0;">
                @if($sf_lab_info_data)
                    @if($sf_data)
                        @if($sf_lab_info_data->sylx==14)
                            {{--粗集料--干筛法--}}
                            @if($sf_type=='gsf')
                                <div class="mt-10 dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="100">集料类型</th>
                                            <th width="100">粗集料</th>
                                            <th width="100">筛分方法</th>
                                            <th width="100">干筛法</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th width="100">试验组号</th>
                                            <th width="100">{{$sf_lab_info_data->syzh}}</th>
                                            <th width="100">试验时间</th>
                                            <th width="100">{{date('Y-m-d H:i:s',$sf_lab_info_data->time)}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40">组数</th>
                                            <th width="100">烘干后试样重量(g)</th>
                                            <th width="150">0.075mm通过百分率(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>第一组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                            <td>{{$sf_data->tgl_1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>第二组</td>
                                            <td>{{$sf_data->weight2}}</td>
                                            <td>{{$sf_data->tgl_2}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40" rowspan="2">筛孔尺寸(mm)</th>
                                            <th width="100" colspan="2">第一组</th>
                                            <th width="100" colspan="2">第二组</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc1}}</td>
                                            <td>{{$sf_data->d1sszl1}}</td>
                                            <td>{{$sf_data->d1fjsy1}}</td>
                                            <td>{{$sf_data->d2sszl1}}</td>
                                            <td>{{$sf_data->d2fjsy1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc2}}</td>
                                            <td>{{$sf_data->d1sszl2}}</td>
                                            <td>{{$sf_data->d1fjsy2}}</td>
                                            <td>{{$sf_data->d2sszl2}}</td>
                                            <td>{{$sf_data->d2fjsy2}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc3}}</td>
                                            <td>{{$sf_data->d1sszl3}}</td>
                                            <td>{{$sf_data->d1fjsy3}}</td>
                                            <td>{{$sf_data->d2sszl3}}</td>
                                            <td>{{$sf_data->d2fjsy3}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc4}}</td>
                                            <td>{{$sf_data->d1sszl4}}</td>
                                            <td>{{$sf_data->d1fjsy4}}</td>
                                            <td>{{$sf_data->d2sszl4}}</td>
                                            <td>{{$sf_data->d2fjsy4}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc5}}</td>
                                            <td>{{$sf_data->d1sszl5}}</td>
                                            <td>{{$sf_data->d1fjsy5}}</td>
                                            <td>{{$sf_data->d2sszl5}}</td>
                                            <td>{{$sf_data->d2fjsy5}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc6}}</td>
                                            <td>{{$sf_data->d1sszl6}}</td>
                                            <td>{{$sf_data->d1fjsy6}}</td>
                                            <td>{{$sf_data->d2sszl6}}</td>
                                            <td>{{$sf_data->d2fjsy6}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc7}}</td>
                                            <td>{{$sf_data->d1sszl7}}</td>
                                            <td>{{$sf_data->d1fjsy7}}</td>
                                            <td>{{$sf_data->d2sszl7}}</td>
                                            <td>{{$sf_data->d2fjsy7}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc8}}</td>
                                            <td>{{$sf_data->d1sszl8}}</td>
                                            <td>{{$sf_data->d1fjsy8}}</td>
                                            <td>{{$sf_data->d2sszl8}}</td>
                                            <td>{{$sf_data->d2fjsy8}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc9}}</td>
                                            <td>{{$sf_data->d1sszl9}}</td>
                                            <td>{{$sf_data->d1fjsy9}}</td>
                                            <td>{{$sf_data->d2sszl9}}</td>
                                            <td>{{$sf_data->d2fjsy9}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc10}}</td>
                                            <td>{{$sf_data->d1sszl10}}</td>
                                            <td>{{$sf_data->d1fjsy10}}</td>
                                            <td>{{$sf_data->d2sszl10}}</td>
                                            <td>{{$sf_data->d2fjsy10}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc11}}</td>
                                            <td>{{$sf_data->d1sszl11}}</td>
                                            <td>{{$sf_data->d1fjsy11}}</td>
                                            <td>{{$sf_data->d2sszl11}}</td>
                                            <td>{{$sf_data->d2fjsy11}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc12}}</td>
                                            <td>{{$sf_data->d1sszl12}}</td>
                                            <td>{{$sf_data->d1fjsy12}}</td>
                                            <td>{{$sf_data->d2sszl12}}</td>
                                            <td>{{$sf_data->d2fjsy12}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc13}}</td>
                                            <td>{{$sf_data->d1sszl13}}</td>
                                            <td>{{$sf_data->d1fjsy13}}</td>
                                            <td>{{$sf_data->d2sszl13}}</td>
                                            <td>{{$sf_data->d2fjsy13}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc14}}</td>
                                            <td>{{$sf_data->d1sszl14}}</td>
                                            <td>{{$sf_data->d1fjsy14}}</td>
                                            <td>{{$sf_data->d2sszl14}}</td>
                                            <td>{{$sf_data->d2fjsy14}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc15}}</td>
                                            <td>{{$sf_data->d1sszl15}}</td>
                                            <td>{{$sf_data->d1fjsy15}}</td>
                                            <td>{{$sf_data->d2sszl15}}</td>
                                            <td>{{$sf_data->d2fjsy15}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc16}}</td>
                                            <td>{{$sf_data->d1sszl16}}</td>
                                            <td>{{$sf_data->d1fjsy16}}</td>
                                            <td>{{$sf_data->d2sszl16}}</td>
                                            <td>{{$sf_data->d2fjsy16}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc17}}</td>
                                            <td>{{$sf_data->d1sszl17}}</td>
                                            <td>{{$sf_data->d1fjsy17}}</td>
                                            <td>{{$sf_data->d2sszl17}}</td>
                                            <td>{{$sf_data->d2fjsy17}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc18}}</td>
                                            <td>{{$sf_data->d1sszl18}}</td>
                                            <td>{{$sf_data->d1fjsy18}}</td>
                                            <td>{{$sf_data->d2sszl18}}</td>
                                            <td>{{$sf_data->d2fjsy18}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($sf_type=='ssf')
                                {{--粗集料————水筛法--}}
                                <div class="mt-10 dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="100">集料类型</th>
                                            <th width="100">粗集料</th>
                                            <th width="100">筛分方法</th>
                                            <th width="100">水筛法</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th width="100">试验组号</th>
                                            <th width="100">{{$sf_lab_info_data->syzh}}</th>
                                            <th width="100">试验时间</th>
                                            <th width="100">{{date('Y-m-d H:i:s',$sf_lab_info_data->time)}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40">组数</th>
                                            <th width="100">试样总质量(g)</th>
                                            <th width="100">水洗后筛上总重(g)</th>
                                            <th width="150">水洗0.075mm筛下量(g)</th>
                                            <th width="150">0.075mm通过率(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>第一组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                            <td>{{$sf_data->sxweight1}}</td>
                                            <td>{{$sf_data->sxsxl1}}</td>
                                            <td>{{$sf_data->tgl1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>第二组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                            <td>{{$sf_data->sxweight1}}</td>
                                            <td>{{$sf_data->sxsxl1}}</td>
                                            <td>{{$sf_data->tgl1}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40" rowspan="2">筛孔尺寸(mm)</th>
                                            <th width="100" colspan="2">第一组</th>
                                            <th width="100" colspan="2">第二组</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc1}}</td>
                                            <td>{{$sf_data->d1sszl1}}</td>
                                            <td>{{$sf_data->d1fjsy1}}</td>
                                            <td>{{$sf_data->d2sszl1}}</td>
                                            <td>{{$sf_data->d2fjsy1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc2}}</td>
                                            <td>{{$sf_data->d1sszl2}}</td>
                                            <td>{{$sf_data->d1fjsy2}}</td>
                                            <td>{{$sf_data->d2sszl2}}</td>
                                            <td>{{$sf_data->d2fjsy2}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc3}}</td>
                                            <td>{{$sf_data->d1sszl3}}</td>
                                            <td>{{$sf_data->d1fjsy3}}</td>
                                            <td>{{$sf_data->d2sszl3}}</td>
                                            <td>{{$sf_data->d2fjsy3}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc4}}</td>
                                            <td>{{$sf_data->d1sszl4}}</td>
                                            <td>{{$sf_data->d1fjsy4}}</td>
                                            <td>{{$sf_data->d2sszl4}}</td>
                                            <td>{{$sf_data->d2fjsy4}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc5}}</td>
                                            <td>{{$sf_data->d1sszl5}}</td>
                                            <td>{{$sf_data->d1fjsy5}}</td>
                                            <td>{{$sf_data->d2sszl5}}</td>
                                            <td>{{$sf_data->d2fjsy5}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc6}}</td>
                                            <td>{{$sf_data->d1sszl6}}</td>
                                            <td>{{$sf_data->d1fjsy6}}</td>
                                            <td>{{$sf_data->d2sszl6}}</td>
                                            <td>{{$sf_data->d2fjsy6}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc7}}</td>
                                            <td>{{$sf_data->d1sszl7}}</td>
                                            <td>{{$sf_data->d1fjsy7}}</td>
                                            <td>{{$sf_data->d2sszl7}}</td>
                                            <td>{{$sf_data->d2fjsy7}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc8}}</td>
                                            <td>{{$sf_data->d1sszl8}}</td>
                                            <td>{{$sf_data->d1fjsy8}}</td>
                                            <td>{{$sf_data->d2sszl8}}</td>
                                            <td>{{$sf_data->d2fjsy8}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc9}}</td>
                                            <td>{{$sf_data->d1sszl9}}</td>
                                            <td>{{$sf_data->d1fjsy9}}</td>
                                            <td>{{$sf_data->d2sszl9}}</td>
                                            <td>{{$sf_data->d2fjsy9}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc10}}</td>
                                            <td>{{$sf_data->d1sszl10}}</td>
                                            <td>{{$sf_data->d1fjsy10}}</td>
                                            <td>{{$sf_data->d2sszl10}}</td>
                                            <td>{{$sf_data->d2fjsy10}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc11}}</td>
                                            <td>{{$sf_data->d1sszl11}}</td>
                                            <td>{{$sf_data->d1fjsy11}}</td>
                                            <td>{{$sf_data->d2sszl11}}</td>
                                            <td>{{$sf_data->d2fjsy11}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc12}}</td>
                                            <td>{{$sf_data->d1sszl12}}</td>
                                            <td>{{$sf_data->d1fjsy12}}</td>
                                            <td>{{$sf_data->d2sszl12}}</td>
                                            <td>{{$sf_data->d2fjsy12}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc13}}</td>
                                            <td>{{$sf_data->d1sszl13}}</td>
                                            <td>{{$sf_data->d1fjsy13}}</td>
                                            <td>{{$sf_data->d2sszl13}}</td>
                                            <td>{{$sf_data->d2fjsy13}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc14}}</td>
                                            <td>{{$sf_data->d1sszl14}}</td>
                                            <td>{{$sf_data->d1fjsy14}}</td>
                                            <td>{{$sf_data->d2sszl14}}</td>
                                            <td>{{$sf_data->d2fjsy14}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc15}}</td>
                                            <td>{{$sf_data->d1sszl15}}</td>
                                            <td>{{$sf_data->d1fjsy15}}</td>
                                            <td>{{$sf_data->d2sszl15}}</td>
                                            <td>{{$sf_data->d2fjsy15}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc16}}</td>
                                            <td>{{$sf_data->d1sszl16}}</td>
                                            <td>{{$sf_data->d1fjsy16}}</td>
                                            <td>{{$sf_data->d2sszl16}}</td>
                                            <td>{{$sf_data->d2fjsy16}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>{{$sf_data->cc17}}</td>
                                            <td>{{$sf_data->d1sszl17}}</td>
                                            <td>{{$sf_data->d1fjsy17}}</td>
                                            <td>{{$sf_data->d2sszl17}}</td>
                                            <td>{{$sf_data->d2fjsy17}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @elseif($sf_lab_info_data->sylx==15)
                            @if($sf_type=='gsf')
                                {{--细集料--干筛法--}}
                                <div class="mt-10 dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="100">集料类型</th>
                                            <th width="100">细集料</th>
                                            <th width="100">筛分方法</th>
                                            <th width="100">干筛法</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th width="100">试验组号</th>
                                            <th width="100">{{$sf_lab_info_data->syzh}}</th>
                                            <th width="100">试验时间</th>
                                            <th width="100">{{date('Y-m-d H:i:s',$sf_lab_info_data->time)}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="50">组数</th>
                                            <th width="100">筛前试样总质量(g)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>第一组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>第二组</td>
                                            <td>{{$sf_data->weight2}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40" rowspan="2">筛孔尺寸(mm)</th>
                                            <th width="100" colspan="2">第一组</th>
                                            <th width="100" colspan="2">第二组</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>9.5</td>
                                            <td>{{$sf_data->d1sszl1}}</td>
                                            <td>{{$sf_data->d1fjsy1}}</td>
                                            <td>{{$sf_data->d2sszl1}}</td>
                                            <td>{{$sf_data->d2fjsy1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>4.75</td>
                                            <td>{{$sf_data->d1sszl2}}</td>
                                            <td>{{$sf_data->d1fjsy2}}</td>
                                            <td>{{$sf_data->d2sszl2}}</td>
                                            <td>{{$sf_data->d2fjsy2}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>2.36</td>
                                            <td>{{$sf_data->d1sszl3}}</td>
                                            <td>{{$sf_data->d1fjsy3}}</td>
                                            <td>{{$sf_data->d2sszl3}}</td>
                                            <td>{{$sf_data->d2fjsy3}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>1.18</td>
                                            <td>{{$sf_data->d1sszl4}}</td>
                                            <td>{{$sf_data->d1fjsy4}}</td>
                                            <td>{{$sf_data->d2sszl4}}</td>
                                            <td>{{$sf_data->d2fjsy4}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.6</td>
                                            <td>{{$sf_data->d1sszl5}}</td>
                                            <td>{{$sf_data->d1fjsy5}}</td>
                                            <td>{{$sf_data->d2sszl5}}</td>
                                            <td>{{$sf_data->d2fjsy5}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.3</td>
                                            <td>{{$sf_data->d1sszl6}}</td>
                                            <td>{{$sf_data->d1fjsy6}}</td>
                                            <td>{{$sf_data->d2sszl6}}</td>
                                            <td>{{$sf_data->d2fjsy6}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.15</td>
                                            <td>{{$sf_data->d1sszl7}}</td>
                                            <td>{{$sf_data->d1fjsy7}}</td>
                                            <td>{{$sf_data->d2sszl7}}</td>
                                            <td>{{$sf_data->d2fjsy7}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.075</td>
                                            <td>{{$sf_data->d1sszl8}}</td>
                                            <td>{{$sf_data->d1fjsy8}}</td>
                                            <td>{{$sf_data->d2sszl8}}</td>
                                            <td>{{$sf_data->d2fjsy8}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>筛底</td>
                                            <td>{{$sf_data->d1sszl9}}</td>
                                            <td>{{$sf_data->d1fjsy9}}</td>
                                            <td>{{$sf_data->d2sszl9}}</td>
                                            <td>{{$sf_data->d2fjsy9}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($sf_type=='ssf')
                                {{--细集料--水筛法--}}
                                <div class="mt-10 dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="100">集料类型</th>
                                            <th width="100">细集料</th>
                                            <th width="100">筛分方法</th>
                                            <th width="100">水筛法</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th width="100">试验组号</th>
                                            <th width="100">{{$sf_lab_info_data->syzh}}</th>
                                            <th width="100">试验时间</th>
                                            <th width="100">{{date('Y-m-d H:i:s',$sf_lab_info_data->time)}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40">组数</th>
                                            <th width="100">试样总质量(g)</th>
                                            <th width="100">水洗后筛上总重(g)</th>
                                            <th width="150">水洗0.075mm筛下量(g)</th>
                                            <th width="150">0.075mm通过率(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>第一组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                            <td>{{$sf_data->sxweight1}}</td>
                                            <td>{{$sf_data->sxsxl1}}</td>
                                            <td>{{$sf_data->tgl1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>第二组</td>
                                            <td>{{$sf_data->weight1}}</td>
                                            <td>{{$sf_data->sxweight1}}</td>
                                            <td>{{$sf_data->sxsxl1}}</td>
                                            <td>{{$sf_data->tgl1}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dataTables_wrapper">
                                    <table id="table_list" class="table table-border table-bordered table-sort">
                                        <thead>
                                        <tr class="text-c">
                                            <th width="40" rowspan="2">筛孔尺寸(mm)</th>
                                            <th width="100" colspan="2">第一组</th>
                                            <th width="100" colspan="2">第二组</th>
                                        </tr>
                                        <tr class="text-c">
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                            <th>筛上质量(g)</th>
                                            <th>分计筛余(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-c">
                                            <td>9.5</td>
                                            <td>{{$sf_data->d1sszl1}}</td>
                                            <td>{{$sf_data->d1fjsy1}}</td>
                                            <td>{{$sf_data->d2sszl1}}</td>
                                            <td>{{$sf_data->d2fjsy1}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>4.75</td>
                                            <td>{{$sf_data->d1sszl2}}</td>
                                            <td>{{$sf_data->d1fjsy2}}</td>
                                            <td>{{$sf_data->d2sszl2}}</td>
                                            <td>{{$sf_data->d2fjsy2}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>2.36</td>
                                            <td>{{$sf_data->d1sszl3}}</td>
                                            <td>{{$sf_data->d1fjsy3}}</td>
                                            <td>{{$sf_data->d2sszl3}}</td>
                                            <td>{{$sf_data->d2fjsy3}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>1.18</td>
                                            <td>{{$sf_data->d1sszl4}}</td>
                                            <td>{{$sf_data->d1fjsy4}}</td>
                                            <td>{{$sf_data->d2sszl4}}</td>
                                            <td>{{$sf_data->d2fjsy4}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.6</td>
                                            <td>{{$sf_data->d1sszl5}}</td>
                                            <td>{{$sf_data->d1fjsy5}}</td>
                                            <td>{{$sf_data->d2sszl5}}</td>
                                            <td>{{$sf_data->d2fjsy5}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.3</td>
                                            <td>{{$sf_data->d1sszl6}}</td>
                                            <td>{{$sf_data->d1fjsy6}}</td>
                                            <td>{{$sf_data->d2sszl6}}</td>
                                            <td>{{$sf_data->d2fjsy6}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.15</td>
                                            <td>{{$sf_data->d1sszl7}}</td>
                                            <td>{{$sf_data->d1fjsy7}}</td>
                                            <td>{{$sf_data->d2sszl7}}</td>
                                            <td>{{$sf_data->d2fjsy7}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>0.075</td>
                                            <td>{{$sf_data->d1sszl8}}</td>
                                            <td>{{$sf_data->d1fjsy8}}</td>
                                            <td>{{$sf_data->d2sszl8}}</td>
                                            <td>{{$sf_data->d2fjsy8}}</td>
                                        </tr>
                                        <tr class="text-c">
                                            <td>筛底</td>
                                            <td>{{$sf_data->d1sszl9}}</td>
                                            <td>{{$sf_data->d1fjsy9}}</td>
                                            <td>{{$sf_data->d2sszl9}}</td>
                                            <td>{{$sf_data->d2fjsy9}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    @else
                        <span class="c-red">未匹配到筛分详情数据</span>
                    @endif
                @else
                    <span class="c-red">暂时还没有筛分数据</span>
                @endif
            </div>
        </div>
    </div>

@stop

@section('layer')



@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/echarts/echarts.min.js"></script>
    <script type="text/javascript">
        list.init();
        $(function() {

            //试验数据表格弹出层
            $('.open_new_layer').on('click',function(){
                var url=$(this).attr('data-url');
                var title=$(this).attr('data-title');
                window.parent.getlayer(title,url);
            });
            $('.show-report').on('click',function(event){
                event.stopPropagation();
//                alert(0);
            });
            $('.show-none').on('click',function(event){
                event.stopPropagation();
            });


        })
    </script>
@stop