@extends('admin.layouts.iframe')

@section('container')
<style type="text/css">
body, article, #map {width:calc(100%-40px);height:100%;}
</style>
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div class="text-c">
  <form id="search_form" data="data_type,start_date,end_date" method="get" data-url="{{url('lab/lab_data_info/'.$d_id)}}">
        选择数据类型
	  	<span class="select-box" style="width:auto; padding: 3px 5px;">
	  		<select name="data_type" id="data_type" class="select select2">
                @if(isset($data_type))
                @foreach($data_type as $k=>$v)
                <option value="{{$k}}" @if(isset($search['data_type']) && $search['data_type'] == $k) selected @endif>{{$v}}</option>
                @endforeach
                @endif
            </select>
        </span>
        日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if(isset($search['start_date']) && $search['start_date']){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if(isset($search['end_date']) && $search['end_date']){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        <input type="hidden" value="{{$pro_id}}" name="pro_id">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>  	
  </form>
</div>

<div class="mt-10" style="height:100%">
  <div id="map"></div>
  <input id="map_data" value="{{$map}}" type="hidden">
</div>

@stop

@section('script')
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
<script type="text/javascript">
list.init();

var dom = document.getElementById("map");
var myChart = echarts.init(dom);
var app = {};
option = null;

/*var convertData = function () {
    var geoCoordMap = [
        {'name':'咸阳','value':[108.72,34.36,43,'a',1]},
        {'name':'阳','value':[109.11,35.09,43,'b',2]},
        {'name':'西','value':[108.95,34.27,43,'c',3]},
    ];
    console.info(geoCoordMap);
    return geoCoordMap;
};
*/
var convertData = eval('('+$('#map_data').val()+')');
console.info(convertData);
option = {
    title: {
        text: '',//桩基质量监控
        left: 'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: function (params) {
            return '桩序号: ' + params.name+"<br />"
                  +'A坐标(钻机经度坐标): ' + params.value[0]+"<br />"
                  +'B坐标(钻机纬度坐标): ' + params.value[1]+"<br />"
                  +'成桩深度: ' + params.value[5]+"<br />"
                  +'开始时间: ' + params.value[3]+"<br />"
                  +'成桩时间: ' + params.value[4]+"<br />"
                  +'持力层电流(A): '+params.value[2];
        }
    },
    legend: {
        orient: 'vertical',
        y: 'bottom',
        x:'right',
        data:['GFC桩'],
        textStyle: {
            color: '#000'
        }
    },
    visualMap: {
        min: 0,
        max: 20,
        calculable: true,
        inRange: {
            color: ['#50a3ba', '#eac736', '#d94e5d']
        },
        textStyle: {
            color: '#000'
        }
    },
    bmap: {
        center: [108.945604,34.382789],
        zoom: 12,
        roam: true,
        mapStyle: {
            styleJson: [{
                'featureType': 'water',
                'elementType': 'all',
                'stylers': {
                    'color': '#d1d1d1'
                }
            }, {
                'featureType': 'land',
                'elementType': 'all',
                'stylers': {
                    'color': '#f3f3f3'
                }
            }, {
                'featureType': 'railway',
                'elementType': 'all',
                'stylers': {
                    'visibility': 'true'
                }
            }, {
                'featureType': 'highway',
                'elementType': 'all',
                'stylers': {
                    'color': '#fdfdfd'
                }
            }, {
                'featureType': 'highway',
                'elementType': 'labels',
                'stylers': {
                    'visibility': 'true'
                }
            }, {
                'featureType': 'arterial',
                'elementType': 'geometry',
                'stylers': {
                    'color': '#fefefe'
                }
            }, {
                'featureType': 'arterial',
                'elementType': 'geometry.fill',
                'stylers': {
                    'color': '#fefefe'
                }
            }, {
                'featureType': 'poi',
                'elementType': 'all',
                'stylers': {
                    'visibility': 'off'
                }
            }, {
                'featureType': 'green',
                'elementType': 'all',
                'stylers': {
                    'visibility': 'true'
                }
            }, {
                'featureType': 'subway',
                'elementType': 'all',
                'stylers': {
                    'visibility': 'true'
                }
            }, {
                'featureType': 'manmade',
                'elementType': 'all',
                'stylers': {
                    'color': '#d1d1d1'
                }
            }, {
                'featureType': 'local',
                'elementType': 'all',
                'stylers': {
                    'color': '#d1d1d1'
                }
            }, {
                'featureType': 'arterial',
                'elementType': 'labels',
                'stylers': {
                    'visibility': 'off'
                }
            }, {
                'featureType': 'boundary',
                'elementType': 'all',
                'stylers': {
                    'color': '#fefefe'
                }
            }, {
                'featureType': 'building',
                'elementType': 'all',
                'stylers': {
                    'color': '#d1d1d1'
                }
            }, {
                'featureType': 'label',
                'elementType': 'labels.text.fill',
                'stylers': {
                    'color': '#000'
                }
            }]
        }
    },
    series : [
        {
            name: 'GFC桩',
            type: 'scatter',
            coordinateSystem: 'bmap',
            data: convertData,
            symbolSize:12,
            label: {
                normal: {
                    formatter: '{b}',
                    position: 'right',
                    show: false
                },
                emphasis: {
                    show: true
                }
            },
            itemStyle: {
                normal: {
                    color: 'purple'
                }
            }
        }
    ]
};
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
</script>
@stop