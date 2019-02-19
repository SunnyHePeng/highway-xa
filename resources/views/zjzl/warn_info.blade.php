@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">
<div>
  <form id="search_form" data="pro_id,sup_id,sec_id,dev_id,type,start_date,end_date" method="get" data-url="{{url('lab/warn_data')}}">
        @if($user['role'] == 1 || $user['role'] == 2)
		<div class="row cl">
    		<span style="float:left;padding: 6px 10px;">选择项目</span>
		  	<span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="pro_id[]" id="pro_id" multiple class="select select2">
		  			<option value="0">全部</option>
	                @if(isset($project))
	                @foreach($project as $k=>$v)
	                <option value="{{$v['id']}}">{{$v['name']}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
    	</div>
    	@endif
    	@if($user['role'] <= 3)
		<div class="row cl">
    		<span style="float:left;padding: 6px 10px;">选择监理</span>
		  	<span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="sup_id[]" id="sup_id" multiple class="select select2">
		  			<option value="0">全部</option>
	                @if(isset($supervision))
	                @foreach($supervision as $k=>$v)
	                <option value="{{$v['id']}}">{{$v['name']}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
    	</div>
    	@endif
    	@if($user['role'] != 5)
        <div class="row cl">
    		<span style="float:left;padding: 6px 10px;">选择标段</span>
		  	<span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="sec_id[]" id="sec_id" multiple class="select select2">
		  			<option value="0">全部</option>
	                @if(isset($section))
	                @foreach($section as $k=>$v)
	                <option value="{{$v['id']}}">{{$v['name']}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
    	</div>
    	@endif
    	<div class="row cl">
    		<span style="float:left;padding: 6px 10px;">选择设备</span>
		  	<span class="col-sm-11 col-xs-8" style="padding: 3px 5px;">
		  		<select name="dev_id[]" id="dev_id" multiple class="select select2">
		  			<option value="0">全部</option>
	                @if(isset($device))
	                @foreach($device as $k=>$v)
	                <option value="{{$v['id']}}">{{$v['name']}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
    	</div>
    	<div class="row cl">
	        <span style="padding: 6px 10px;">处理状态</span>
		  	<span class="select-box" style="width:auto; padding: 3px 5px;">
		  		<select name="type" id="type" class="select select2">
	                @if(isset($type))
	                @foreach($type as $k=>$v)
	                <option value="{{$k}}" @if(isset($search['type']) && $search['type'] == $k) selected @endif>{{$v}}</option>
	                @endforeach
	                @endif
	            </select>
	        </span>
	        <span style="padding: 6px 10px;">试验时间：</span>
	        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
	        -
	        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
	    </div>
        <div class="row cl">
    		<span style="float:left; padding: 6px 10px;">&emsp;&emsp;&emsp;&emsp;</span>
	        <span class="col-sm-10" style="padding: 3px 5px;">
		        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button> 
    		</span>
    	</div>
  </form>
</div>

<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40" class="hidden-xs hidden-sm">序号</th>
				<th width="100" class="hidden-xs hidden-sm">监理名称</th>
				<th width="100">标段名称</th>
				<th width="100">设备名称</th>
				<th width="100">报警信息</th>
				<th width="100">报警级别</th>
				<th width="150" class="hidden-xs">时间</th>
				<th width="100" class="hidden-xs">标段处理情况</th>
				<th width="100" class="hidden-xs">监理处理情况</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c">
				<td class="hidden-xs hidden-sm">{{$page_num++}}</td>
				<td class="hidden-xs hidden-sm">{{$value['sup']['name']}}</td>
				<td>{{$value['section']['name']}}</td>
				<td>{{$value['device']['name']}}</td>
				<td>{{$value['warn_info']}}</td>
				<td>
				@if($value['warn_sx_level'] && $value['warn_sx_level'] > $value['warn_level'])
					{{$level[$value['warn_sx_level']]}}({{$value['warn_sx_info']}})
				@else
					{{$level[$value['warn_level']]}}
				@endif 
				</td>
				<td class="hidden-xs">{{date('Y-m-d H:i:s', $value['time'])}}</td>
				<td class="hidden-xs"><span class="label @if($value['is_sec_deal'] == 1) label-success @else label-danger @endif radius">{{$status[$value['is_sec_deal']]}}</span></td>
				<td class="hidden-xs"><span class="label @if($value['is_sup_deal'] == 1) label-success @else label-danger @endif radius">{{$status[$value['is_sup_deal']]}}</span></td>
				<td class="f-14 product-brand-manage td-manage"> 
				  	<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-title="详细信息" data-url="{{url('zlyj/detail_info?id='.$value['id'])}}">详细信息</a>
				  	<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;"  data-title="处理报告" data-url="{{url('zlyj/clbg?id='.$value['id'])}}">处理报告</a>
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
<input id="select_pro" value="{{$search['pro_id']}}" type="hidden">
<input id="select_sup" value="{{$search['sup_id']}}" type="hidden">
<input id="select_sec" value="{{$search['sec_id']}}" type="hidden">
<input id="select_dev" value="{{$search['dev_id']}}" type="hidden">
@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.openIframe();

$('#pro_id').select2().val(eval('('+$('#select_pro').val()+')')).trigger("change");
$('#sup_id').select2().val(eval('('+$('#select_sup').val()+')')).trigger("change");
$('#sec_id').select2().val(eval('('+$('#select_sec').val()+')')).trigger("change");
$('#dev_id').select2().val(eval('('+$('#select_dev').val()+')')).trigger("change");

var url = "{{url('zlyj/get_select_val')}}";
//项目改变 获取对应的监理 标段 设备
$('#pro_id').on('change', function () {
	var pro_id = $(this).val();
	var data = {
	        'pro_id': pro_id
	    };
	setOption(url+'/pro', data, 'pro_id');
	if(pro_id.length > 1 && pro_id[0]==0){
		//去掉全部选项
		pro_id.splice(0,1);
		$('#pro_id').select2().val(eval('('+pro_id+')')).trigger("change");
	}
});
//监理改变 获取对应的 标段 设备
$('#sup_id').on('change', function () {
	var sup_id = $(this).val();
	var data = {
	        'sup_id': sup_id
	    };
	setOption(url+'/sup', data, 'sup_id');
	if(sup_id.length > 1 && sup_id[0]==0){
		//去掉全部选项
		sup_id.splice(0,1);
		$('#sup_id').select2().val(eval('('+sup_id+')')).trigger("change");
	}
});
//标段改变 获取对应的  设备
$('#sec_id').on('change', function () {
	var sec_id = $(this).val();
	var data = {
		    'sec_id': sec_id
		};
	setOption(url+'/sec', data, 'sec_id');
	if(sec_id.length > 1 && sec_id[0]==0){
		//去掉全部选项
		sec_id.splice(0,1);
		$('#sec_id').select2().val(eval('('+sec_id+')')).trigger("change");
	}
});
$('#dev_id').on('change', function () {
	var dev_id = $(this).val();
	if(dev_id.length > 1 && dev_id[0]==0){
		//去掉全部选项
		dev_id.splice(0,1);
		$('#dev_id').select2().val(eval('('+dev_id+')')).trigger("change");
	}
});
function setOption(url, data, id){
	$.ajax({
	    url: url,
        type: 'GET',
        data: data,
        dataType: 'json',
        success:function(msg){
          var str = '';
          if(msg.status){
            data = msg.data;
          	for(var i in data){
	          	str = '<option value="0">全部</option>';
	          	if(data[i]){
	          		for(var j in data[i]){
	          			str += '<option value="'+data[i][j]['id']+'">'+data[i][j]['name']+'</option>'; 
		            }
	          	}
	          	$('#'+i).html('').append(str);
	            $('#'+i).select2().val(0).trigger("change");
	        }
          }
        },
        error: function(){
          common.alert('获取信息出错...');
        }
	});
}
</script>
@stop