@extends('admin.layouts.tree')

@section('container')

<div>
	<form id="search_form" data="pro_id,name,lxr" method="get" data-url="{{url('manage/supervision?pro_id='.$search['pro_id'])}}">
	  	<!-- 建设项目
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
        <input type="text" name="name" id="name" placeholder="请输入监理名称" class="input-text search-input" value="@if(isset($search['name']) && $search['name']){{$search['name']}}@endif">
        <input type="text" name="lxr" id="lxr" placeholder="请输入负责人" class="input-text search-input" value="@if(isset($search['lxr']) && $search['lxr']){{$search['lxr']}}@endif">
    	<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
@if($user_is_act)
<div class="cl mt-10"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="section" data-title="添加监理" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加监理</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="40">序号</th>
				<th width="80">监理名称</th>
				<th width="60" class="hidden-xs hidden-sm hidden-md">监理类型</th>
				<th width="60" class="hidden-xs hidden-sm hidden-md">所属单位</th>
				<!-- <th width="60" class="hidden-xs hidden-sm">职务</th> -->
				<th width="80" class="hidden-xs hidden-sm">负责人</th>
				<th width="80" class="hidden-xs hidden-sm">联系方式</th>
				@if($user_is_act)
				<th width="150">操作</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td class="text-l">{{$value['name']}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$type[$value['type']]}}</td>
				<td class="hidden-xs hidden-sm hidden-md">{{$value['company']}}</td>
				<!-- <td class="hidden-xs hidden-sm">{{$value['position']}}</td> -->
				<td class="hidden-xs hidden-sm">{{$value['fzr']}}</td>
				<td class="hidden-xs hidden-sm">{{$value['phone']}}</td>
				@if($user_is_act)
				<td class="f-14 td-manage">
					<a style="text-decoration:none" class="mt-5 edit-r btn btn-secondary radius size-MINI" data-for="section" data-title="编辑监理" data-url="{{url('manage/supervision/'.$value['id'].'/edit')}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="mt-5 ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/supervision/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					<a style="text-decoration:none" class="mt-5 ml-5 open-iframe btn btn-success radius size-MINI" href="javascript:;" data-title="设置标段" data-url="{{url('manage/sup_section?sup_id='.$value['id'].'&pro_id='.$value['project_id'])}}">设置标段</a>
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
<input type="hidden" value="{{url('manage/supervision')}}" id="tree_url">
<input type="hidden" value="section" id="tree_page">
@stop

@section('layer')
@include('admin.project.supervision_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop