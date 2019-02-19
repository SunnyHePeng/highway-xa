@extends('admin.layouts.iframe')

@section('container')
    <meta http-equiv="refresh" content="60">
    <style>
        .clearfix:after{

            　　　　　　content:"";//设置内容为空

        　　　　　　height:0;//高度为0

        　　　　　　line-height:0;//行高为0

        　　　　　　display:block;//将文本转为块级元素

        　　　　　　visibility:hidden;//将元素隐藏

        　　　　　　clear:both;

        　　　　　}

        　　　　.clearfix {

            　　　　　　zoom:1;

                　　　　}

             .bgc-g {
               background-color: #f0f0f0;
           }

    </style>
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="clearfix" >
        <form id="search_form" style="float: left;" data="data_type,start_date,end_date" method="get" url="{{url('lab/getDataAtVideo/'.$search['device_id'])}}">
            <input type="hidden" value="{{$decode}}" name="decode">
            试验类型
            <span class="select-box" style="width:auto; padding: 3px 5px;">
	  		<select name="sylx" id="sylx" class="select select2">
                <option value="0">请选择</option>
                @if(isset($search['sylx_all']))
                    @foreach($search['sylx_all'] as $k=>$v)
                        @if(isset($search['sylx']))
                            <option value="{{$k}}"
                                    @if($k==$search['sylx'])
                                    selected
                                    @endif >{{$v}}</option>
                        @else
                            <option value="{{$k}}" >{{$v}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </span>
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>

        <div style="height: 30px;color:blue;float: left;" class="ml-10">
            <span>当前时间范围内试验总次数：{{$total_num}}(次)</span>&nbsp;&nbsp;
            @if($sylx_num)
               @foreach($sylx_num as$k=>$v)
                   @if($v>0)
                       <span>{{$k}}:{{$v}}(次)</span>&nbsp;
                   @endif
               @endforeach
            @endif
        </div>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class=" table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="40">序号</th>
                <th width="100">组号</th>
                <th width="100">试验类型</th>
                <th width="130">试验时间</th>
                <th width="100">报警状态</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @if($data)
                @foreach($data as $k=>$v)
                    <tr class="text-c @if($v['is_warn']==1) red-line @endif @if($v['id'] == $report_data['id']) bgc-g  @endif open_new_layer" data-title="试验详细信息" data-url="{{url('lab/lab_data_detail?id='.$v['id'])}}" id="list-{{$v['id']}}">
                        <td>{{$search['num']++}}</td>
                        <td>{{$v['syzh']}}</td>
                        <td>
                            @if(isset($v['sylx']))
                                @if(array_key_exists($v['sylx'],$search['sylx_all']))
                                    {{$search['sylx_all'][(int)$v['sylx']]}}
                                @endif
                            @endif
                        </td>
                        <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                        <td>
                            @if($v['is_warn']==1)
                                有
                            @else
                                无
                            @endif
                        </td>
                        <td>
                            @if(!empty($v) && $v['reportFile'] == '')
                                <a style="text-decoration:none" class="mt-5 ml-5 btn btn-default radius size-MINI show-none" href="javascript:;">试验报告</a>
                            @else
                            <a style="text-decoration:none" class="mt-5 ml-5 btn btn-secondary radius size-MINI show-report" href="{{$v['reportFile']}}"  target="_blank" data-title="试验详细信息">试验报告</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        @if($last_page>1)
            @include('admin.layouts.page')
        @endif
    </div>

    @if(!empty($report_data))
        @if($report_data['reportFile'] == '')
         <div class="mt-50 c-red">最新一条试验数据：试验组号{{$report_data['syzh']}},尚未上传试验报告</div>
        @else
        <iframe src="{{$report_data['reportFile']}}" frameborder="0" style="width:100%; height: 600px; scrolling: no; margin-top: 10px"  frameborder="0"></iframe>
        @endif
    @endif
@stop

@section('layer')



@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/echarts/echarts.min.js"></script>
    <script type="text/javascript">
        list.init();
        $(function() {

            //试验数据表格弹出层
            $('.open_new_layer').on('click',function(){
                var url=$(this).attr('data-url');
                var title=$(this).attr('data-title');
                window.parent.getlayer(title,url);
            });
            $('.show-report').on('click',function(event){
                event.stopPropagation();
            });
            $('.show-none').on('click',function(event){
                event.stopPropagation();
            });


        })
    </script>
@stop