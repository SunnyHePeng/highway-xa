@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/>  
<div>
  <form id="search_form" data="start_date,end_date,type,keyword" method="get" data-url="{{url('manage/log')}}">
        
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <span class="select-box" style="width:auto;">
          <select name="type" id="type" class="select">
                <option value="0">操作类型</option>
                @if(isset($type))
                @foreach($type as $k=>$v)
                <option value="{{$k}}" @if(isset($search['type']) && $search['type'] == $k) selected @endif>{{$v}}</option>
                @endforeach
                @endif
            </select>
        </span>
        <input type="text" name="keyword" id="keyword" placeholder="请输入操作人" class="input-text search-input" value="@if(isset($search['keyword']) && $search['keyword']){{$search['keyword']}}@endif">
  		<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>  	
  </form>
</div>
@if($user_is_act)
<div class="cl mt-10 hidden-xs"> 
  <span class="l mr-10">
    <a href="javascript:;" class="btn btn-danger radius del-r-more" data-url="{{url('manage/log/0')}}"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
  </span>
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="日志信息" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="日志信息" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
  <!-- <span class="r ml-10 export-file export-pdf" data-name="日志信息" data-obj="table_list" data-type="pdf" data-url="{{url('manage/log/0')}}"><a href="javascript:;" title="导为pdf文件"><img src="/static/admin/images/pdf.svg"></a></span>
  <span class="r ml-10 export-file export-word" data-name="日志信息" data-obj="table_list" data-type="doc" data-url="{{url('manage/log/0')}}"><a href="javascript:;" title="导为word文件"><img src="/static/admin/images/word.svg"></a></span>
  <span class="r ml-10 export-file export-excel" data-name="日志信息" data-obj="table_list" data-type="xls" data-url="{{url('manage/log/0')}}"><a href="javascript:;" title="导为excel文件"><img src="/static/admin/images/excel.svg"></a></span>
  <span class="r ml-10 export-file export-dy" data-name="日志信息" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span> -->
</div>
@endif
<div class="mt-10 dataTables_wrapper">
  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th data-tableexport-display="none" width="70" class="hidden-xs no-print"><input type="checkbox" name="" value=""></th>
        <th width="70" class="no-left">序号</th>
        <th width="100" class="hidden-xs">IP</th>
        <th width="100" class="hidden-xs">地址</th>
        <th width="150">操作内容</th>
        <th width="100">操作人</th>
        <th width="100">操作时间</th>
        @if($user_is_act)
        <th data-tableexport-display="none" width="60" class="no-print">操作</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
        <tr class="text-c" id="list-{{$value['id']}}">
          <td data-tableexport-display="none" class="hidden-xs no-print"><input name="del" type="checkbox" value="{{$value['id']}}"></td>
          <td class="no-left">{{$page_num++}}</td>
          <td class="hidden-xs">{{$value['ip']}}</td>
          <td class="hidden-xs">{{$value['addr']}}</td>
          <td>{{$value['act']}}</td>
          <td>{{$value['name']}}</td>
          <td>{{date('Y-m-d H:i', $value['created_at'])}}</td>
          @if($user_is_act)
          <td data-tableexport-display="none" class="f-14 product-brand-manage td-manage no-print">
            <a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/suggest/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
</script>
@stop