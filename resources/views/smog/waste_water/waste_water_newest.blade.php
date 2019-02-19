@extends('admin.layouts.default')

@section('container')

    <div class="row cl">
        {{--<div class="col-md-12 col-lg-5 hidden-xs hidden-sm hidden-md">--}}
            {{--<div class="row cl">--}}
                {{--<div class="col-12 col-md-12 mt-50">--}}
                    {{--<img src="{{ asset('static/admin/images/waste_water.jpg') }}" alt="暂无污水处理现场图片" style="width:100%;height:650px;">--}}
                    {{--<input type="hidden" value="407" id="camera1" />--}}
                    {{--<object classid="clsid:AC036352-03EB-4399-9DD0-602AB1D8B6B9" id="PreviewOcx" width="100%" height="650">--}}
                    {{--</object>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="dataTables_wrapper col-lg-12 col-md-12">
            <div class="row cl">
                <div class="col-12 col-md-12">
                    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                        <caption><h3>污水处理实时监测数据信息</h3></caption>
                        <thead class="mt-5">
                        <tr class="text-c">
                            <th width="80" rowspan="2">监理</th>
                            <th width="50" rowspan="2">标段</th>
                            <th width="80">BOD(生化需氧量)</th>
                            <th width="80">PH值(酸碱性)</th>
                            <th width="80">色度(稀释倍数)</th>
                            <th width="100" rowspan="2">监测位置</th>
                            <th width="100" rowspan="2">监测时间</th>
                            <th width="50" rowspan="2">操作</th>
                        </tr>
                        <tr class="text-c">
                            <th width="80">(10~40)mg/L</th>
                            <th width="80">6~9</th>
                            <th width="80">30~50</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($now_data)
                            @foreach($now_data as $v)
                                <tr class="text-c">
                                    <td>{{$v->supervision->name}}</td>
                                    <td>{{$v->section->name}}</td>
                                    <td>{{$v->exit_BOD}}</td>
                                    <td>{{$v->pH}}</td>
                                    <td>{{$v->chrominance}}</td>
                                    <td>{{$v->place}}</td>
                                    <td>{{date('Y-m-d H:i:s',$v->time)}}</td>
                                    <td>
                                        <input class="btn btn-primary radius open-video" data-title="{{$v->section->name.'-污水处理实时视频'}}" data-url="{{url('smog/real_time_video_by_device'.'/'.$v->device->id)}}" type="button" value="实时视频">
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">还未开始上传监测数据</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        $(".open-video").on('click',function(){
            var title=$(this).attr("data-title");
            var url=$(this).attr("data-url");
            layer.open({
                type: 2,
                title: title,
                shadeClose: true,
                area: ['90%', '90%'],
                content: url,
            });
        });
    </script>
@stop
