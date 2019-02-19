@extends('admin.layouts.default')

@section('container')
    <div class="text-c">
        <form id="search_form" data="r_id" method="get" data-url="{{url('manage/department')}}">
            角色
            <span class="select-box" style="width:auto;">
        <select name="r_id" id="r_id" class="select select2">
        	<option value="">请选择角色</option>
            @if(isset($role_list))
                @foreach($role_list as $k=>$v)
                    <option value="{{$v['id']}}" @if(isset($search['r_id']) && $search['r_id'] == $v['id']) selected @endif>{{$v['display_name']}}</option>
                @endforeach
            @endif
        </select>
        </span>
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="module" data-title="添加部门" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加部门</a>
	</span>
    </div>
    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="70">序号</th>
                <th width="100">角色名称</th>
                <th width="100">部门名称</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $value)
                    <tr class="text-c" id="list-{{$value['id']}}">
                        <td>{{$page_num++}}</td>
                        <td>{{$value['role']['display_name']}}</td>
                        <td>{{$value['name']}}</td>
                        <td class="f-14 product-brand-manage">
                            <a style="text-decoration:none" data-for="module" data-title="编辑部门" data-url="{{url('manage/department/'.$value['id'].'/edit')}}" class="edit-r btn btn-secondary radius size-MINI" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
                            <a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$value['id']}}" data-url="{{url('manage/department/'.$value['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
@stop

@section('layer')
    @include('admin.admin.department_edit')
@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();
    </script>
@stop