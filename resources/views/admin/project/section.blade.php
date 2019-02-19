@extends('admin.layouts.tree')

@section('container')
<div>
	<form id="search_form" data="pro_id,name,lxr" method="get" data-url="{{url('manage/section?pro_id='.$search['pro_id'])}}">
	  	<!-- 建设项目：
	  	<span class="select-box inline">
	  		<select name="pro_id" id="pro_id" class="select select2">
                @if(isset($project))
                @foreach($project as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['pro_id']) && $search['pro_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span> --> 
        <input type="hidden" name="pro_id" id="pro_id" value="{{$search['pro_id']}}">
        <input type="text" name="name" id="name" placeholder="请输入标段名称" class="input-text search-input" value="@if(isset($search['name']) && $search['name']){{$search['name']}}@endif">
        <input type="text" name="lxr" id="lxr" placeholder="请输入负责人" class="input-text search-input" value="@if(isset($search['lxr']) && $search['lxr']){{$search['lxr']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
@if($user_is_act)
<div class="cl mt-10"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加标段" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加标段</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">标段名称</th>
				<th width="80">项目名称</th>
				<th width="80">监理名称</th>
				<th width="50" class="hidden-xs hidden-sm">起始桩号</th>
				<th width="50" class="hidden-xs hidden-sm">终止桩号</th>
				<th width="80">承包商名称</th>
				<!-- <th width="50" class="hidden-xs hidden-sm hidden-md">拌合站数量</th>
				<th width="50" class="hidden-xs hidden-sm hidden-md">梁场数量</th>
				<th width="50" class="hidden-xs hidden-sm hidden-md">隧道数量</th> -->
				<th width="80" class="hidden-xs hidden-sm">负责人</th>
				<th width="80" class="hidden-xs hidden-sm">联系方式</th>
				<th width="80" class="hidden-xs hidden-sm">登记时间</th>
				<th width="120">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td class="text-l">{{$value['project']['name']}}</td>
				<td class="text-l">@if(isset($value['sup'][0]['name'])){{$value['sup'][0]['name']}}@endif</td>
				<td class="hidden-xs hidden-sm">{{$value['begin_position']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['end_position']}}</td>
				<td>{{$value['cbs_name']}}</td>
				<!-- <td class="hidden-xs hidden-sm hidden-md">{{$value['bhz_num']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['lc_num']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['sd_num']}}</td> -->
				<td class="hidden-xs hidden-sm">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
				<td class="hidden-xs hidden-sm">{{date('Y-m-d', $value['created_at'])}}</td>
				<td class="f-14 td-manage">
					@if($user_is_act)
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="section" data-title="编辑标段" data-url="{{url('manage/section/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/section/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					@endif
					<!-- <a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="拌合站信息" data-url="{{url('manage/mixplant?sec_id='.$value['id'])}}">拌合站</a>
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="梁场信息" data-url="{{url('manage/beamfield?sec_id='.$value['id'])}}">梁场</a>
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="隧道信息" data-url="{{url('manage/tunnel?sec_id='.$value['id'])}}">隧道</a> -->
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

<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{url('manage/section')}}" id="tree_url">
<input type="hidden" value="section" id="tree_page">
@stop

@section('layer')
@include('admin.project.section_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();

$('#form_container #project_id').on('change', function(){
  var val = $(this).val();
  if(val){
  	var data = {
        'pro_id': val
      };
    setOption($(this).attr('data-url'), data, 'name');
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
            str = '<option value="0">请选择标段</option>';
            data = msg.data;
            if(data.length > 0){
              for(var i in data){
                str += '<option value="'+data[i]['name']+'" data-id="'+data[i]['id']+'">'+data[i]['name']+'</option>';
              }
            }
            $('#form_container #'+id).html('').append(str);
          }
        },
        error: function(){
          common.alert('获取信息出错...');
        }
  })
}

$('#form_container #name').on('change', function(){
	$('#form_container #psection_id').val($('#form_container #name option:selected').attr('data-id'));
});
</script>
@stop