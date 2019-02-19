@extends('admin.layouts.iframe')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

    <div class="text-c">
        <form id="search_form" data="sylx,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info')}}">
            选择试验类型
            <span class="select-box" style="width:auto; padding: 3px 5px;">
	  		<select name="sylx" id="sylx" class="select select2">
                <option value="0">全部</option>
                @if(isset($aggregate_sylx))
                    @foreach($aggregate_sylx as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                @endif
            </select>
        </span>
            日期范围：
            <input name="start_date" placeholder="请输入开始时间" value="@if($search){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="@if($search){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
            {{--<input type="hidden" value="{{$pro_id}}" name="pro_id">--}}
            {{--<input type="hidden" value="{{$sup_id}}" name="sup_id">--}}
            {{--<input type="hidden" value="{{$sec_id}}" name="sec_id">--}}
            {{--<input type="hidden" value="{{$device_id}}" name="device_id">--}}
            <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
        </form>
    </div>

    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" data-url="{{url('lab/detail_info')}}" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="70">序号</th>
                <th width="70">试验类型</th>
                <th width="70">试验组号</th>
                <th width="130">试验时间</th>
                <th width="230">操作</th>
            </tr>
            </thead>
            <tbody>
               @if($data)
                  @foreach($data as $k=>$v)
                      <tr class="text-c">
                          <td>{{$from++}}</td>
                          <td>
                              @if($v['sylx']==14)
                                  粗集料试验
                              @elseif($v['sylx']==15)
                                  细集料试验
                              @endif
                          </td>
                          <td>{{$v['syzh']}}</td>
                          <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                          <td>
                              <span class="btn btn-primary radius size-MINI"><a style="text-decoration:none;color: #fff;" class="" href="{{$v['reportFile']}}" target="_blank">试验报告</a></span>
                              {{--<input class="btn btn-secondary size-MINI radius open-playback" data-title="视频回放" data-url="{{url('lab/aggregate_video_playback').'/'.$v['id']}}" type="button" value="视频回放">--}}
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
    <input type="hidden" value="{{json_encode(Config()->get('common.lab_info_detail'))}}" id="lab_detail">


@stop

@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>

    <script type="text/javascript">
        list.init();
        $(".open-playback").on('click',function(){
             var title=$(this).attr("data-title");
             var url=$(this).attr("data-url");

            layer.open({
                type: 2,
                title:false,
                area: ['1000px','600px'],
                content: [url, 'no']
            });
        });

    </script>
@stop