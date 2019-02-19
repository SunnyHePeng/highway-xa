@extends('admin.layouts.default')

@section('container')
    <div class="col-xs-12 col-sm-12 mt-10">
	<span class="l mr-20">
		<a class="btn btn-primary radius add-a" data-for="project" data-title="添加初始高程" href="javascript:;" data-url="{{url('stat/tunnel_house_init_add')}}"><i class="Hui-iconfont">&#xe600;</i>添加初始高程信息</a>
	</span>
        <span class="l">
		<a class="btn btn-success radius edit-a" data-for="project" data-title="编辑初始高程数据" href="javascript:;" data-url="{{url('stat/tunnel_house_init_edit')}}"><i class="Hui-iconfont">&#xe60c;</i>编辑初始高程数据</a>
	    </span>
    </div>

        <div class=" col-xs-12 col-sm-12 mt-10" class="padding-left: 0; ">
            <div class="panel panel-info">
                <div class="panel-body" class="padding: 0;">
                    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
                        <thead>
                        <tr class="text-c">
                            <th width="40">序号</th>
                            <th width="100">测点</th>
                            <th width="80">初始高程</th>
                        </tr>
                        </thead>
                        <tbody>
                      @if($data)
                         <tr class="text-c">
                            <td>1</td>
                            <td>1#测点</td>
                             <td>{{$data->station_init_1}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>2</td>
                             <td>2#测点</td>
                             <td>{{$data->station_init_2}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>3</td>
                             <td>3#测点</td>
                             <td>{{$data->station_init_3}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>4</td>
                             <td>4#测点</td>
                             <td>{{$data->station_init_4}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>5</td>
                             <td>5#测点</td>
                             <td>{{$data->station_init_5}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>6</td>
                             <td>6#测点</td>
                             <td>{{$data->station_init_6}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>7</td>
                             <td>7#测点</td>
                             <td>{{$data->station_init_7}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>8</td>
                             <td>8#测点</td>
                             <td>{{$data->station_init_8}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>9</td>
                             <td>9#测点</td>
                             <td>{{$data->station_init_9}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>10</td>
                             <td>10#测点</td>
                             <td>{{$data->station_init_10}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>11</td>
                             <td>11#测点</td>
                             <td>{{$data->station_init_11}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>12</td>
                             <td>12#测点</td>
                             <td>{{$data->station_init_12}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>13</td>
                             <td>13#测点</td>
                             <td>{{$data->station_init_13}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>14</td>
                             <td>14#测点</td>
                             <td>{{$data->station_init_14}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>15</td>
                             <td>15#测点</td>
                             <td>{{$data->station_init_15}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>16</td>
                             <td>16#测点</td>
                             <td>{{$data->station_init_16}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>17</td>
                             <td>17#测点</td>
                             <td>{{$data->station_init_17}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>18</td>
                             <td>18#测点</td>
                             <td>{{$data->station_init_18}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>19</td>
                             <td>19#测点</td>
                             <td>{{$data->station_init_19}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>20</td>
                             <td>20#测点</td>
                             <td>{{$data->station_init_20}}</td>
                         </tr>
                         <tr class="text-c">
                             <td>21</td>
                             <td>21#测点</td>
                             <td>{{$data->station_init_21}}</td>
                         </tr>
                       @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


@stop

@section('script')
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
    <script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
    <script type="text/javascript" src="/static/admin/js/chart.js"></script>
    <script type="text/javascript">
        list.init();
        $(".add-a").click(function(){
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

        $(".edit-a").click(function(){
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