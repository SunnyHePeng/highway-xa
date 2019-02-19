@extends('admin.layouts.tree')

@section('container')

@if($user_is_act)
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div class="cl mt-10"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加设备" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加设备</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">项目名称</th>
				<th width="80">监理名称</th>
				<th width="80">标段名称</th>
				<th width="80">设备名称</th>
				<th width="80">设备型号</th>
				<th width="80">设备编号</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">生产厂家</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">生产日期</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">摄像头1</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">摄像头1对应编码设备uuid</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">摄像头2</th>
				<th width="80" class="hidden-xs hidden-sm hidden-md">摄像头对应编码设备uuid</th>

			@foreach($para as $v)
				<th width="80" class="hidden-xs hidden-sm hidden-md">{{$v}}</th>
				@endforeach
				<th width="80" class="hidden-xs hidden-sm">负责人</th>
				<th width="80" class="hidden-xs hidden-sm">联系方式</th>
				@if($user_is_act)
				<th width="80">操作</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['project']['name']}}</td>
				<td class="text-l">{{$value['sup']['name']}}</td>
				<td class="text-l">{{$value['section']['name']}}</td>
				<td>{{$value['name']}}</td>
				<td>{{$value['model']}}</td>
				<td>{{$value['dcode']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory_name']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['factory_date']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['camera1']}}</td>
				<td>{{$value['camera1_encoderuuid']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['camera2']}}</td>
				<td>{{$value['camera2_encoderuuid']}}</td>
				@foreach($para as $k=>$v)
				<td class="hidden-xs hidden-sm hidden-md">{{$value['parame'.($k+1)]}}</td>
				@endforeach
				<td class="hidden-xs hidden-sm">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="section" data-title="编辑设备" data-url="{{url('manage/device/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/device/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="device" id="tree_page">
@stop

@section('layer')
@include('admin.device.device_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();

$('#project_id').on('change', function(){
  if($('#project_id').val() && $('#project_id').val() !=0 ){
    $('#form_container #section_id', '#form_container #supervision_id').html('').append('<option value="0">请选择</option>');
    var data = {
        'pro_id': $('#project_id').val()
    };
      
    if(data.pro_id){
        setOption($(this).attr('data-url'), data, 'supervision_id');
    }
  }
});

$('#supervision_id').on('change', function(){
  if($('#supervision_id').val() && $('#supervision_id').val() !=0 ){
    $('#form_container #section_id').html('').append('<option value="0">请选择</option>');
    
    var data = {
        'sup_id': $(this).val(),
        'pro_id': $('#project_id').val()
    };
      
    if(data.sup_id && data.pro_id){
        setOption($(this).attr('data-url'), data, 'section_id');
    }
  }
});

function setOption(url, data, id){
  $.ajax({
    url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success:function(msg){
          var str = '';
          if(msg.status){
            str = '<option value="0">请选择</option>';
            data = msg.data;
            if(data.length > 0){
              for(var i in data){
                str += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
              }
            }
            $('#'+id).html('').append(str);
          }
        },
        error: function(){
          common.alert('获取信息出错...');
        }
  })
}
</script>
@stop