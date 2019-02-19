@extends('admin.layouts.default')

@section('container')
<link rel="stylesheet" href="/lib/select2/css/select2.min.css">
<div class="text-c">
  <form id="search_form" data="pro_id" method="get" data-url="{{url('manage/permission')}}">
      模块
      <span class="select-box inline">
        <select name="m_id" id="m_id" class="select select2">
                @if(isset($module))
                @foreach($module as $k=>$v)
                <option value="{{$v['id']}}" @if(isset($search['m_id']) && $search['m_id'] == $v['id']) selected @endif>{{$v['name']}}</option>
                @endforeach
                @endif
            </select>
        </span>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="permission" data-title="添加权限" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加权限</a>
		<span class="c-red">&emsp;&emsp;按照升序排序</span>
	</span>
</div>
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="70">序号</th>
				<th width="100">模块</th>
				<th width="100">权限名称</th>
				<th width="100" class="hidden-xs">URL</th>
				<th width="100" class="hidden-xs">权限描述</th>
				<th width="100" class="hidden-xs">菜单显示</th>
				<th width="100" class="hidden-xs">排序</th>
				<th width="100" class="hidden-xs">更新时间</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			@if($data)
			@foreach($data as $value)
			<tr class="text-c" id="list-{{$value['id']}}">
				<td>{{$page_num++}}</td>
				<td>{{$value['mod']['name']}}</td>
				<td>{{$value['name']}}</td>
				<td class="hidden-xs">{{$value['url']}}</td>
				<td class="hidden-xs">{{$value['description']}}</td>
				<td class="hidden-xs"><span class="label @if($value['status'] == 1) label-success @else label-danger @endif radius">{{$status[$value['status']]}}</span></td>
				<td class="hidden-xs"><input type="text" data-url="{{url('manage/permission/'.$value['id'])}}" class="input-text text-c sort-r" value="{{$value['sort']}}"></td>
				<td class="hidden-xs">{{date('Y-m-d H:i', $value['updated_at'])}}</td>
				<td class="f-14 product-brand-manage">
					<a style="text-decoration:none" data-for="permission" data-title="编辑权限" data-url="{{url('manage/permission/'.$value['id'].'/edit')}}" class="edit-r btn btn-secondary radius size-MINI" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
					<a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/permission/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
				@if($value['child'])
				@foreach($value['child'] as $value)
				<tr class="text-c" id="list-{{$value['id']}}">
					<td>{{$page_num++}}</td>
					<td></td>
					<td>&emsp;&emsp;|--&nbsp;{{$value['name']}}</td>
					<td class="hidden-xs">{{$value['url']}}</td>
					<td class="hidden-xs">{{$value['description']}}</td>
					<td class="hidden-xs"><span class="label @if($value['status'] == 1) label-success @else label-danger @endif radius">{{$status[$value['status']]}}</span></td>
					<td class="hidden-xs"><input type="text" data-url="{{url('manage/permission/'.$value['id'])}}" class="input-text text-c sort-r" value="{{$value['sort']}}"></td>
					<td class="hidden-xs">{{date('Y-m-d H:i', $value['updated_at'])}}</td>
					<td class="f-14 product-brand-manage">
						<a style="text-decoration:none" data-for="permission" data-title="编辑权限" data-url="{{url('manage/permission/'.$value['id'].'/edit')}}" class="edit-r btn btn-secondary radius size-MINI" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
						<a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/permission/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					</td>
				</tr>
				@endforeach
				@endif
			@endforeach
			@endif
		</tbody>
	</table>
	@if($last_page > 1)
	    @include('admin.layouts.page')
	@endif
</div>
@stop

@section('layer')
@include('admin.admin.permission_edit')
@stop

@section('script')
<script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
$('#m_id').select2().val({{$search['m_id']}});

list.init();

$('.skin-minimal input').iCheck({
  checkboxClass: 'icheckbox-blue',
  radioClass: 'iradio-blue',
  increaseArea: '20%'
});
</script>
@stop