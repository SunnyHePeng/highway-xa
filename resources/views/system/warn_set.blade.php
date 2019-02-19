@extends('admin.layouts.tree')

@section('container')
<style>
#table tr th {min-width: 50px;}
#table tr th.time {min-width: 100px;}
</style>
<div>
  	<form id="search_form" data="sec_id" method="get" data-url="{{$url}}">
        <div class="row">
    		<span class="col-sm-2 text-r" style="padding: 3px 5px;">选择标段</span>
		  	<span class="select-box" style="width:auto; padding: 3px 5px;">
		  		<select name="sec_id" id="sec_id" class="select select2">
	                @if(isset($section))
	                @foreach($section as $k=>$v)
	                <option value="{{$v['id']}}" @if(isset($search['sec_id']) && $search['sec_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
	        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </div>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
  <span class="l">
    <a class="btn open-iframe btn-primary radius" data-is-reload="1" data-title="添加报警通知人员" data-url="{{$url.'?type=add&pro_id='.$pro_id.'&sec_id='.$sec_id.'&sup_id='.$sup_id}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加报警通知人员</a>
  </span>
</div>
<div class="mt-10 dataTables_wrapper">
  <table id="table" class="table table-border table-bordered table-bg table-sort">
  	<thead>
  		<tr class="text-c">
  			<th rowspan="2">序号</th>
  			<th rowspan="2">用户信息</th>
  			<th colspan="3">初级报警</th>
  			<th colspan="3">中级报警</th>
  			<th colspan="3">高级报警</th>
  			<th rowspan="2">操作</th>
  		</tr>
  		<tr class="text-c">
  			<th>立即通知</th>
  			<th>24h未处理</th>
  			<th>48h未处理</th>
  			<th>立即通知</th>
  			<th>24h未处理</th>
  			<th>48h未处理</th>
  			<th>立即通知</th>
  			<th>24h未处理</th>
  			<th>48h未处理</th>
  		</tr>
  	</thead>
    <tbody>
      @if($info)
      @foreach($info as $value)
        <tr class="text-c">
          	<td>{{$page_num++}}</td>
          	<td>{{$value['name']}}-{{$value['posi']['name']}}</td>
          	<td>
          		@if($value['cj_0'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
          	</td>
          	<td>
          	  	@if($value['cj_24'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td>
          	  	@if($value['cj_48'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td>
          	  	@if($value['zj_0'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	        <td>
          	  	@if($value['zj_24'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td>
          	  	@if($value['zj_48'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td>
          	  	@if($value['gj_0'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	        <td>
          	  	@if($value['gj_24'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td>
          	  	@if($value['gj_48'] == 1)
          		<span class="label label-success radius">是</span>
          		@else
          		<span class="label label-danger radius">否</span>
          		@endif
	      	</td>
	      	<td class="f-14 td-manage">
            	<a style="text-decoration:none" data-is-reload="1" data-url="{{$url.'?uid='.$value['user_id']}}" class="mt-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="修改报警设置" title="修改报警设置"><i class="Hui-iconfont">&#xe6df;</i></a>
			</td>
        </tr>
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
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();

$('.skin-minimal input').iCheck({
  checkboxClass: 'icheckbox-blue',
  radioClass: 'iradio-blue',
  increaseArea: '20%'
});
</script>
@stop