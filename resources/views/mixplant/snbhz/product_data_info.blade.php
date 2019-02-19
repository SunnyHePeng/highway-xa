@extends('admin.layouts.iframe')

@section('container')
<style type="text/css">
.blue-line, .blue-line td, .blue-line th{color: blue;}
</style>
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div>
  <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('snbhz/product_data_info/'.$d_id)}}">
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
        <th width="70">序号</th>                
        <th width="130">生产时间</th>
        <th width="100" class="hidden-xs hidden-sm">监理</th>
        <th width="100">标段</th>
        <th width="100" class="hidden-xs">生产单位</th>
        <th width="100">施工地点</th>
        <th width="100" class="hidden-xs">强度等级</th>
        <th width="100" class="hidden-xs">盘方量(方)</th>
        <th width="100" class="hidden-xs">浇筑部位</th>
        <th width="100" class="hidden-xs hidden-sm">操作员</th>
        <th width="100">报警信息</th>
        @if($user['role'] ==3 || $user['role'] ==4 || $user['role'] ==5)
        <th width="70">操作</th>
        @endif
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
        <tr class="text-c open-iframe @if($value['is_warn']) @if(!$is_deal) red-line @else blue-line @endif @endif" data-warn="{{$value['is_warn']}}" data-id="{{$value['id']}}" id="list-{{$value['id']}}" data-title="拌和详细信息" data-url="{{url('snbhz/detail_info?id='.$value['id'])}}">
          <td>{{$page_num++}}</td>
          <td>{{date('Y-m-d H:i:s', $value['time'])}}</td>
          <td class="hidden-xs hidden-sm">{{$value['section']['sup'][0]['name']}}</td>
          <td>{{$value['section']['name']}}</td>
          <td class="hidden-xs">{{$value['scdw']}}</td>
          <td>{{$value['sgdd']}}</td>
          <td class="hidden-xs">{{$value['pbbh']}}</td>
           <td class="hidden-xs">{{$value['pfl']}}</td>
          <td class="hidden-xs">{{$value['jzbw']}}</td>
          <td class="hidden-xs hidden-sm">{{$value['operator']}}</td>
          <td>{{$value['warn_info']}}</td>
          @if($user['role'] ==3 || $user['role'] == 4 || $user['role'] == 5)
          <td class="f-14 product-brand-manage td-manage">
            @if(!$is_deal)
            <a style="text-decoration:none" data-for='module' class="ml-5 open-iframe btn btn-secondary radius size-MINI " href="javascript:;" data-is-reload="1" data-id="{{$value['id']}}" data-url="{{url('snbhz/deal/'.$value['id'].'?d_id='.$value['device_id'])}}" data-title="处理意见" title="处理">报警处理</a>
            @endif
          </td>
          @endif
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
</script>
@stop