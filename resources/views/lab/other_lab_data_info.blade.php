@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-c">
        <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">
            选择数据类型
            <span class="select-box" style="width:auto; padding: 3px 5px;">
	  		<select name="data_type" id="data_type" class="select select2">
                @if(isset($data_type))
                    @foreach($data_type as $k=>$v)
                        <option value="{{$k}}" @if(isset($search['data_type']) && $search['data_type'] == $k) selected @endif>{{$v}}</option>
                    @endforeach
                @endif
            </select>
        </span>
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <input type="hidden" value="{{$pro_id}}" name="pro_id">
            <input type="hidden" value="{{$sup_id}}" name="sup_id">
            <input type="hidden" value="{{$sec_id}}" name="sec_id">
            <input type="hidden" value="{{$device_id}}" name="device_id">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l ml-10 pt-5 c-error">
    点击表格行显示对应试验数据信息
  </span>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="70">序号</th>
                <th width="70">组号</th>
                <th width="100" class="hidden-xs">类别牌号</th>
                <th width="100" class="hidden-xs">强度等级</th>
                <th width="100" class="hidden-xs">试验品种</th>
                <th width="100" class="hidden-xs">试件个数</th>
                <th width="100">试验类型</th>
                <th width="100" class="hidden-xs hidden-sm">试验人员</th>
                <th width="130">试验时间</th>
                <th width="230">操作</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $value)
                    <?php
                    if($user['role'] != 4 && $user['role'] != 5){
                        if($value['is_warn'] && (!$value['is_sup_deal'] || !$value['is_sec_deal'])){
                            $is_deal = false;
                        }else{
                            $is_deal = true;
                        }
                    }else{
                        if(($value['is_warn'] && !$value['is_sup_deal'] && $user['role'] == 4) || ($value['is_warn'] && !$value['is_sec_deal'] && $user['role'] == 5) || (($value['warn_level']==3 || $value['warn_sx_level']==3)  && !$value['is_pro_deal'] && $user['role'] == 3)){
                            $is_deal = false;
                        }else{
                            $is_deal = true;
                        }
                    }
                    ?>
                    <tr class="text-c open-iframe @if($value['is_warn']) @if(!$is_deal) red-line @else blue-line @endif @endif" data-title="试验详细信息" data-url="{{url('lab/lab_data_detail?id='.$value['id'])}}" id="list-{{$value['id']}}">
                        <td>{{$page_num++}}</td>
                        <td>{{$value['syzh']}}</td>
                        <td class="hidden-xs">{{$value['lbph']}}</td>
                        <td class="hidden-xs">{{$value['qddj']}}</td>
                        <td class="hidden-xs">{{$value['sypz']}}</td>
                        <td class="hidden-xs">{{$value['sjgs']}}</td>
                        <td>
                            @if(in_array($value['sylx'], [1,2,3,4,5,6,7,8,9,10]))
                                {{$symc[$value['sylx']]}}
                            @else
                                {{$value['sylx']}}
                            @endif
                        </td>
                        <td class="hidden-xs hidden-sm">{{$value['syry']}}</td>
                        <td>{{date('Y-m-d H:i:s', $value['time'])}}</td>
                        <td class="f-14 product-brand-manage td-manage">
                            <a style="text-decoration:none" class="mt-5 ml-5 btn btn-secondary radius size-MINI show-report open-iframe" data-title="试验详细信息" data-url="{{url('lab/lab_data_detail?id='.$value['id'])}}">试验详情</a>
                            @if($value['reportFile'])
                                <a style="text-decoration:none" class="mt-5 ml-5 btn btn-secondary radius size-MINI show-report" href="{{$value['reportFile']}}" target="_blank" data-title="试验报告">试验报告</a>
                            @else
                                <a style="text-decoration:none" class="mt-5 ml-5 btn btn-default radius size-MINI show-none" href="javascript:;">试验报告</a>
                            @endif
                            @if($user['role'] ==3 || $user['role'] == 4 || $user['role'] == 5)
                                @if(!$is_deal)
                                    <a style="text-decoration:none" class="ml-5 open-iframe btn btn-secondary radius size-MINI show-report" href="javascript:;" data-is-reload="1" data-id="{{$value['id']}}" data-url="{{url('lab/deal/'.$value['id'].'?d_id='.$value['device_id'])}}" data-title="处理意见" title="处理">报警处理</a>
                                @endif
                            @endif
                            <a style="text-decoration:none" class="mt-5 ml-5 btn btn-secondary radius size-MINI show-report open-iframe hidden-sm hidden-xs" data-title="视频回放" data-url="{{url('lab/getVideo?id='.$value['id'])}}">视频回放</a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        @if($last_page > 1)
            @include('admin.layouts.page')
        @endif
    </div>

@stop

@section('layer')
    <input type="hidden" value="{{json_encode(Config()->get('common.lab_info_detail'))}}" id="lab_detail">


@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>

    <script type="text/javascript">
        list.init();

        $('.show-report').on('click',function(event){
            event.stopPropagation();
        });
        $('.show-none').on('click',function(event){
            event.stopPropagation();
        });
        var lab_detail = eval('('+$('#lab_detail').val()+')');
    </script>
@stop