@extends('admin.layouts.default')

@section('container')
    <style>
        #table tr th {min-width: 50px;}
        #table tr th.time {min-width: 100px;}
    </style>
    <div>
        <form id="search_form" data="sec_id" method="get" data-url="">
            <div class="row">
                <span class="col-sm-2 text-r" style="padding: 3px 5px;">选择标段</span>
                <span class="select-box" style="width:auto; padding: 3px 5px;">
		  		<select name="sec_id" id="sec_id" class="select select2">
                     @if($section_data)
                       @foreach($section_data as $section)
                            <option value="{{$section->id}}" @if($section->id == $search['search_sec_id']) selected="selected"@endif>{{$section->name}}</option>
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
    <a class="btn open-data btn-primary radius" data-is-reload="1" data-title="添加报警通知人员" data-url="{{url('stretch/warn_user_add'.'?sec_id='.$search['search_sec_id'])}}" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加报警通知人员</a>
  </span>
    </div>
    <div class="mt-10 dataTables_wrapper">
        <table id="table" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th>序号</th>
                <th>用户登陆账号</th>
                <th>用户姓名</th>
                <th>所属角色</th>
                <th>职位</th>
                <th>用户电话</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
              @if($warn_users)
                @foreach($warn_users as $k=>$user)
                    <tr class="text-c" id="{{'list-'.$user->id}}">
                        <td>{{$k+1}}</td>
                        <td>{{$user->user->username}}</td>
                        <td>{{$user->user->name}}</td>
                        <td>{{$user->user->roled->display_name}}</td>
                        <td>{{$user->user->posi->name}}</td>
                        <td>{{$user->user->phone}}</td>
                        <td>
                            <a style="text-decoration:none" class="ml-5 del-r btn btn-danger radius size-MINI" data-id="{{$user->id}}" href="javascript:;" data-url="{{url('stretch/warn_user_del'.'/'.$user->id)}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                        </td>
                    </tr>
                @endforeach
              @endif
            </tbody>
        </table>
    </div>

@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();
        $(".open-data").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                area: ['70%', '70%'],
                content: url,
            });
        });
    </script>
@stop