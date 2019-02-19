@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<link href="/static/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/>  
<div>
  <form id="search_form" data="m_id,sec_id,start_date,end_date" method="get" data-url="{{url('manage/stat_login')}}">
        @if($user['role'] !=2 && $user['role'] !=1)
        选择模块
        <span class="select-box" style="width:auto;">
          <select name="m_id" id="m_id" class="select">
                <option value="0">选择模块</option>
                @if(isset($module))
                @foreach($module as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['m_id']) && $search['m_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        @endif
        @if($user['role'] ==3 || $user['role'] ==4)
        选择标段
        <span class="select-box" style="width:auto;">
          <select name="sec_id" id="sec_id" class="select">
                <option value="0">选择标段</option>
                @if(isset($section))
                @foreach($section as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['sec_id']) && $search['sec_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        @endif
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>  	
  </form>
</div>

<div class="cl mt-10 hidden-xs"> 
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="登录统计" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="登录统计" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
</div>

<div class="mt-10 dataTables_wrapper">
@if($user['role'] ==2 || $user['role'] ==1)
  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th width="70" class="no-left">序号</th>
        <th width="100">项目名称</th>
        <th width="100">项目及施工单位登录总次数</th>
      </tr>
    </thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
        <tr class="text-c">
          <td class="no-left">{{$page_num++}}</td>
          <td>{{$value['name']}}</td>
          <td>{{$value['num']}}</td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
@else
  <table id="table_list" class="table table-border table-bordered table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th width="70" class="no-left">序号</th>
        <th width="100">角色</th>
        <th width="100">监理</th>
        <th width="100">标段</th>
        <th width="100">登录次数</th>
      </tr>
    </thead>
    <tbody>
      @if($data)
      @foreach($data as $value)
        <tr class="text-c">
          <td class="no-left">{{$page_num++}}</td>
          <td>{{$value['display_name']}}</td>
          <td>{{$value['supervision']['name']}}</td>
          <td>{{$value['section']['name']}}</td>
          <td>{{$value['num']}}</td>
        </tr>
      @endforeach
      @endif
    </tbody>
  </table>
@endif
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