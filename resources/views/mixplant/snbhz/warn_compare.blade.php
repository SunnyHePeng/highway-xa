@extends('admin.layouts.tree')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">
<div>
  	<form id="search_form" data="sec_id,start_date,end_date" method="get" data-url="{{url('snbhz/warn_compare')}}">
        <input type="hidden" value="{{$tree_value}}" name="{{$tree_key}}">
        <div class="row">
        	<div class="">
        		<span class="col-sm-2 text-r" style="padding: 3px 5px;">选择标段</span>
			  	<span class="col-sm-10" style="padding: 3px 5px; width:80%;">
			  		<select name="sec_id[]" id="sec_id" multiple class="select select2">
		                @if(isset($section))
		                @foreach($section as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
        	</div>
        	<div>
        		<span class="col-sm-2 text-r" style="padding: 3px 5px;">时间</span>
		        <span class="col-sm-10" style="padding: 3px 5px;">
			        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
			        -
			        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
			    </span>
        	</div>
        	<div>
        		<span class="col-sm-2 text-r" style="padding: 3px 5px;"></span>
		        <span class="col-sm-10" style="padding: 3px 5px;">
			        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button> 
        		</span>
        	</div>
        </div>
  	</form>
</div>

<div class="mt-20">
  <div id="mixline" style="min-width:380px;height:400px"></div>
</div>
<input id="mchart" value="{{ $search['sec_id'] }}" type="hidden">
<input id="chart" value="{{$chart}}" type="hidden">

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/modules/exporting.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
//console.info(eval('('+$('#mchart').val()+')'));
$('#sec_id').select2().val(eval('('+$('#mchart').val()+')')).trigger("change");

chart.column('报警对比', '次', 'mixline', 'chart');
</script>
@stop