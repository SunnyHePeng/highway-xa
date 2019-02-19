@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<style>
#table tr th {min-width: 60px;}
#table tr th.time {min-width: 120px;}
.print {display: none;}
</style>
<style type="text/css" media="print">
.print{display : block;}
</style>
<div>
  <form id="search_form" data="sec_id,start_date,end_date" method="get" data-url="{{url('snbhz/tzlb')}}">
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>   
  </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs noprint"> 
  <span onclick="printdiv()" class="ml-10 export-file" data-name="拌和站信息化不合格处理台账"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
</div>
<div class="mt-10 dataTables_wrapper" id="page-print">
  <h4 class="print text-c">{{$search['start_date']}}~{{$search['end_date']}} {{$sec_name}}试验室用户登录统计</h4>
  <table id="table" class="table table-border table-bordered table-bg table-sort">
  	<thead>
  		<tr class="text-c">
  			<th>序号</th>
  			<th>名称</th>
  			<th>角色</th>
			<th class="hidden-xs hidden-sm">单位</th>
  			<th>职位</th>
  			<th>登录次数</th>
  		</tr>
  	</thead>
    <tbody>
      @if($info)
      @foreach($info as $value)
      @if($value['module'])
        <tr class="text-c">
          	<td>{{$page_num++}}</td>
          	<td>{{$value['name']}}</td>
          	<td>{{$value['roles'][0]['display_name']}}</td>
			<td class="hidden-xs hidden-sm">{{$value['company_name']}}</td>
          	<td>{{$value['posi']['name']}}</td>
          	<td>{{$value['num']}}</td>
        </tr>
      @endif
      @endforeach
      @endif
    </tbody>
  </table>
</div>

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();

function printdiv() { 
	var headstr = "<html><head><title></title></head><body>"; 
	var footstr = "</body>"; 
	var newstr = $('#page-print').html(); 
	var oldstr = document.body.innerHTML; 
	document.body.innerHTML = headstr+newstr+footstr; 
	window.print(); 
	document.body.innerHTML = oldstr; 
	return false; 
} 
</script>
@stop