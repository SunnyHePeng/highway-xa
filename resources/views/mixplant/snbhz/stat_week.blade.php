@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div>
  <form id="search_form" data="d_id,start_date,end_date" method="get" data-url="{{url('snbhz/stat_week')}}">
        设备名称
      	<span class="select-box" style="width:auto; padding: 3px 5px;">
        	<select name="d_id" id="d_id" class="select select2">
                @if(isset($device))
                @foreach($device as $v)
                <option value="{{$v['id']}}" @if(isset($search['d_id']) && $search['d_id'] == $v['id']) selected @endif>{{$v['name']}}-{{$v['dcode']}}</option>
                @endforeach
                @endif
            </select>
        </span>
        时间：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>  	
  </form>
</div>

<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs"> 
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出/打印</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="拌和站周报表" data-url="{{$url.'&d=cur'}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="拌和站周报表" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
</div>

<div class="mt-10 dataTables_wrapper">
	@include('mixplant.snbhz._stat_week')

	@if($last_page > 1)
	    @include('admin.layouts.page')
	@endif
</div>

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop