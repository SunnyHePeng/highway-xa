@extends('admin.layouts.iframe')

@section('container')
<style type="text/css">
    .blue-line, .blue-line td, .blue-line th{color: blue;}
    .clearfix {
        zoom: 1;
    }
    .clearfix:after {
        display: block;
        visibility: hidden;
        clear: both;
        height: 0;
        content: ".";
    }
    .day_total {
        float: left;
        /*margin-left: 20px;*/
        /*border: 1px solid #000;*/
        /*padding: 5px;*/
        color: blue;
        /*width: 40%;*/
    }
</style>
<meta http-equiv="refresh" content="60">
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div class="clearfix">
    <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('snbhz/product_data_at_video/'.$d_id)}}" style="float: left">
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
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>

    <div class="cl pd-5 bg-1 bk-gray ml-10 day_total">
      <span>当日拌和总量(方): &nbsp;&nbsp;@foreach($list['total'] as $v){{$v['pbbh']}}--{{sprintf("%.2f", $v['pfl'])}}&nbsp;&nbsp;@endforeach</span>
    </div>
</div>

<div class="cl pd-5 bg-1 bk-gray mt-20">
    <!-- <span class="l">
      @if($user['role'] ==3 || $user['role'] ==4 || $user['role'] ==5)
      <a class="btn btn-primary radius edit-r-more hidden-xs" data-for="module" data-title="处理拌合数据" data-url="{{url('snbhz/deal/0')}}" href="javascript:;">批处理</a>
      @endif
    </span> -->
    <span class="l ml-10 pt-5 c-error">
    点击表格行显示对应物料和处理信息
  </span>
</div>

<div class="mt-10 dataTables_wrapper">
    <table id="table_list" data-url="{{url('snbhz/detail_info')}}" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="20">序号</th>
            <th width="60">生产时间</th>
            <th width="60">强度等级</th>
            <th width="60">盘方量(方)</th>
            <th width="60" class="hidden-xs hidden-sm">浇筑部位</th>
            <th width="100">报警信息</th>

            <th width="70">操作</th>

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
        <tr class="text-c open_new_layer @if($value['is_warn']) @if(!$is_deal) red-line @else blue-line @endif @endif" data-warn="{{$value['is_warn']}}" data-id="{{$value['id']}}" id="list-{{$value['id']}}" data-title="拌和详细信息" data-url="{{url('snbhz/detail_info?id='.$value['id'])}}">
            <td>{{$page_num++}}</td>
            <td>{{date('Y-m-d H:i:s', $value['time'])}}</td>
            <td>{{$value['pbbh']}}</td>
            <td>{{$value['pfl']}}</td>
            <td class="hidden-xs hidden-sm">{{$value['jzbw']}}</td>
            <td>
                {{$value['warn_info']}}
                @if($value['is_warn']==1)
                @if($value['is_sec_deal']==0 && $value['is_sup_deal']==0)
                    合同段未处理
                @elseif($value['is_sec_deal']==1 && $value['is_sup_deal']==0)
                    合同段已处理，驻地办未处理
                @elseif($value['is_sec_deal']==1&&$value['is_sup_deal']==1)
                    处理已闭合
                @endif
                @endif
            </td>
            <td class="f-14 product-brand-manage td-manage">
                @if(!$is_deal)
                <a style="text-decoration:none" data-for='module' class="ml-5 open-iframe btn btn-secondary radius size-MINI " href="javascript:;" data-is-reload="1" data-id="{{$value['id']}}" data-url="{{url('snbhz/deal/'.$value['id'].'?d_id='.$value['device_id'])}}" data-title="报警处理" title="处理">报警处理</a>
                @endif
                @if($value['is_warn']==1)
                        <a style="text-decoration:none" data-for='module' class="ml-5 open-iframe btn btn-secondary radius size-MINI " href="javascript:;" data-is-reload="1" data-id="{{$value['id']}}" data-url="{{url('snbhz/clbg/?id='.$value['id'])}}" data-title="处理报告" title="处理">处理报告</a>
                    @endif
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

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
    list.init();

    $('.td-manage').on('click',function(event){
        event.stopPropagation();
    });

    $(function() {
        $('.open_new_layer').on('click',function(){
            var url=$(this).attr('data-url');
            var title=$(this).attr('data-title');
            window.parent.getlayer(title,url);
        });
    });
</script>
@stop