@extends('admin.layouts.tree')

@section('container')

    <div class="row cl">
        <div class="col-md-12 col-lg-5 hidden-xs hidden-sm hidden-md">
            <div class="row cl">
                <div class="col-12 col-md-12">
                    <img src="{{ asset('static/admin/images/20180920111528.jpg') }}" alt="暂无环境监测仪图片" style="width:100%;height:650px;">
                </div>
            </div>
        </div>
        <div class="dataTables_wrapper col-lg-7 col-md-12" style="height:650px;">
            <div class="row cl" style="padding-top:20%;">
                <div class="col-12 col-md-12">
                    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                        <caption><h3>扬尘实时在线监测仪数据采集信息</h3></caption>
                        <thead class="mt-5">
                        <tr class="text-c">
                            <th width="35">标段</th>
                            <th width="110">PM2.5<br/>（24小时周期内平均不超过75&mu;g&frasl;m&sup3;）</th>
                            <th width="110">PM10<br/>（24小时周期内不超过150&mu;g&frasl;m&sup3;）</th>
                            <th width="50">温度(℃)</th>
                            <th width="50">相对湿度(%)</th>
                            <th width="50">噪音(db)</th>
                            <th width="70">监测位置</th>
                            <th width="100">监测时间</th>
                        </tr>
                        </thead>
                        <tbody>
                          @if($now_environment_data)
                             @foreach($now_environment_data as $v)
                                 <tr class="text-c">
                                   <td>{{$v->section->name}}</td>
                                   <td>{{$v->pm25}}</td>
                                   <td>{{$v->pm10}}</td>
                                   <td>{{$v->temperature}}</td>
                                   <td>{{$v->moisture}}</td>
                                   <td>{{$v->noise}}</td>
                                   <td>{{$v->place}}</td>
                                   <td>{{$v->datetime}}</td>
                                 </tr>
                             @endforeach
                          @else
                             <tr>
                                 <td colspan="10">还未开始上传监测数据</td>
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
        list.openIframe();
    </script>
@stop
