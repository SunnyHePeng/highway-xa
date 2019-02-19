@extends('admin.layouts.tree')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <div>
        <form id="search_form" data="section_id,start_date,end_date" method="get" data-url="{{url('stretch/stat_warn')}}">
            <span class="col-sm-2 text-r" style="padding: 3px 5px;">选择标段</span>
            <span class="select-box" style="width:auto; padding: 3px 5px;">
		  		<select name="section_id" id="section_id" class="select select2">
                    <option value="0">请选择</option>
                    @if($section_data)
                        @foreach($section_data as $section)
                            <option value="{{$section->id}}">{{$section->name}}</option>
                        @endforeach
                    @endif
	            </select>
	        </span>&nbsp;&nbsp;&nbsp;
            时间：
            <input name="start_date" placeholder="请输入开始时间" value="@if($search){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="@if($search){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>
    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="40">序号</th>
                <th width="100" class="hidden-xs">监理单位</th>
                <th width="100">标段名称</th>
                <th width="100">梁场名称</th>
                <th width="100">设备名称</th>
                <th width="100">设备编码</th>
                <th width="100">报警次数</th>
                <th width="150">报警时间</th>
            </tr>
            </thead>
            <tbody>
              @if($data)
                 @foreach($data as $v)
                     <tr class="text-c">
                         <td>{{$from++}}</td>
                         <td class="hidden-xs">{{$v['supervision']['name']}}</td>
                         <td>{{$v['section']['name']}}</td>
                         <td>{{$v['beam_site']['name']}}</td>
                         <td>{{$v['device']['name']}}</td>
                         <td>{{$v['device']['dcode']}}</td>
                         <td>{{$v['warn_number']}}</td>
                         <td>{{date('Y-m-d',$v['created_at'])}}</td>
                     </tr>
                 @endforeach
              @endif
            </tbody>
        </table>
        @if($last_page>1)
            @include('admin.layouts.page')
        @endif
    </div>

@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript">

        list.init();
    </script>
@stop