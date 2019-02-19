<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!-- <link rel="Bookmark" href="favicon.ico" >
<link rel="Shortcut Icon" href="favicon.ico" /> -->
<!--[if lt IE 9]>
<script type="text/javascript" src="/lib/html5.js"></script>
<script type="text/javascript" src="/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" href="/lib/zTree/zTreeStyle.css">
<link rel="stylesheet" type="text/css" href="/static/admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>{{ Config()->get('common.app_name') }}</title>
<meta name="keywords" content="{{ Config()->get('common.keywords') }}">
<meta name="description" content="{{ Config()->get('common.description') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=A72c32e3bbe184588573f8a8cf075e7a"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/CurveLine/1.5/src/CurveLine.min.js"></script>
<script type="text/javascript">
    $(document).bind("contextmenu", function (e) {
        return false;
    });        
</script>
<style type="text/css">
        .iw_poi_title
        {
            color: #CC5522;
            font-size: 14px;
            font-weight: bold;
            overflow: hidden;
            padding-right: 13px;
            white-space: nowrap;
        }
        .iw_poi_content
        {
            font: 12px arial,sans-serif;
            overflow: visible;
            padding-top: 4px;
            white-space: -moz-pre-wrap;
        }
        .stuff
        {
            width: 210px;
            height: 240px;
            position: fixed;
            right: 1px;
            bottom: 1px;
            background: #fff;
            /*display: none;*/
            z-index: 111111;
            filter: alpha(opacity=80); /*支持 IE 浏览器*/
            -moz-opacity: 0.80; /*支持 FireFox 浏览器*/
            opacity: 0.80;
        }
        .Hui-article-box {
            left: 0;
        }
        ul.forminfo {padding-left: 15px;}
        ul.forminfo li { line-height: 45px;}
        ul.forminfo li div { width: 90px; display: inline-block;}
        ul.forminfo li div label {width: 60px; display: inline-block;}
        ul.forminfo li div img {width: 23px; height: 23px;}
    </style>
</head>
<body>
@include('admin.layouts._header')

<section class="Hui-article-box">
  <div class="tree-page row cl">
    <div class="tree col-sm-2 col-xs-12">
        <div class="left-menu"><span></span>项目列表</div>
        <ul id="treeDemo" class="ztree"></ul>
    </div>
    <div class="show-page-info col-sm-10 col-xs-12">
        <nav class="breadcrumb">
            <strong>位置：</strong> <a href="{{url($user['module_url'])}}">{{$user['module_name']}}</a> 
            <span class="c-999 en">&gt;</span><span class="c-666">@if($active){{$active['name']}}@endif</span> 
            <a class="btn btn-success radius r hidden-xs" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
        </nav>
        <div class="Hui-article">
            
                <!-- <div id="loading" style="position: absolute; left: 50%; top: 40%;">
                    <p>
                        <img src="/static/admin/images/map/1.gif" width="30xp" height="30px" style="padding-left: 45px" /><br />
                        <br />
                    </p>
                </div> -->
                <div style="width: 100%; height: 100%; margin: 2px;" id="allmap"></div>
                <div class="stuff">
                    <div class="tipright">
                        <ul class="forminfo">
                            <li>
                                <div>
                                    <label>建设单位</label>
                                    <img src="/static/admin/images/map/map-glc.png"/>
                                </div>
                                <div>
                                    <label>监理</label>
                                    <img src="/static/admin/images/map/map-gzz.png"/>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <label>合同段</label>
                                    <img src="/static/admin/images/map/map-htd.png"/>
                                </div>
                                <div>
                                    <label>拌合站</label>
                                    <img src="/static/admin/images/map/map-bhz.png"/>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <label>桩号</label>
                                    <img src="/static/admin/images/map/map-zh.png"/>
                                </div>
                                <div>
                                    <label>监控</label>
                                    <img src="/static/admin/images/map/map-jk.png"/>
                                </div>
                            </li>               
                            <li>
                                <div>
                                    <label>隧道</label>
                                    <img src="/static/admin/images/map/map-sd.png"/>
                                </div>
                                <div>
                                    <label>桥梁</label>
                                    <img src="/static/admin/images/map/map-ql.png"/>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <label>试验室</label>
                                    <img src="/static/admin/images/map/map-lab.png"/>
                                </div>
                                <div>
                                    <label></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    </div>
  </div>
</section>
<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$fgw}}" id="fgw">
<input type="hidden" value="{{$line}}" id="line">
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
    var data = $('#fgw').val();
    var markerArr = eval('('+data+')');
    initMap();
    /*function getMk() {
        $.ajax({
            url: "ShowMarker.aspx",
            type: "get",
            data: {},
            success: function (data) {
                $("#loading").css("display", "none");
                $(".stuff").css("display", "inline");
                markerArr = " [" + data + "]";
                markerArr = eval('(' + markerArr + ')');
                initMap();
            },
            error: function (e, a, b) {
            },
            dataType: "text"
        });
    }*/

    //创建和初始化地图函数：
    function initMap() {
        createMap(); //创建地图
        setMapEvent(); //设置地图事件
        addMapControl(); //向地图添加控件
        addMarker(); //向地图中添加marker
        addPolyline(); //向地图中添加线
    }

    //创建地图函数：
    function createMap() { 
        var map = new BMap.Map("allmap");//在百度地图容器中创建一个地图,设置卫星图为底图, { mapType: BMAP_HYBRID_MAP }
        var point = new BMap.Point(109.232199,34.736909);//定义一个中心点坐标
        map.centerAndZoom(point, 9); //设定地图的中心点和坐标并将地图显示在地图容器中
        map.addControl(new BMap.MapTypeControl({ mapType: BMAP_SATELLITE_MAP }));//将控件添加到地图，一个控件实例只能向地图中添加一次
        window.map = map; //将map变量存储在全局
    }

    //地图事件设置函数：
    function setMapEvent() {
        map.enableDragging(); //启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom(); //启用地图滚轮放大缩小
        map.enableDoubleClickZoom(); //启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard(); //启用键盘上下左右键移动地图
    }

    //地图控件添加函数：
    function addMapControl() {
        //向地图中添加缩放控件
        var ctrl_nav = new BMap.NavigationControl({ anchor: BMAP_ANCHOR_TOP_LEFT, type: BMAP_NAVIGATION_CONTROL_LARGE });
        map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
        var ctrl_ove = new BMap.OverviewMapControl({ anchor: BMAP_ANCHOR_BOTTOM_RIGHT, isOpen: 1 });
        map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
        var ctrl_sca = new BMap.ScaleControl({ anchor: BMAP_ANCHOR_BOTTOM_LEFT });
        map.addControl(ctrl_sca);
        //向地图中添加卫星图控件
        var map_type = new BMap.MapTypeControl({ mapType: BMAP_HYBRID_MAP });
        map.addControl(map_type);
        //map.setCurrentCity("陕西省"); 
    }

    function addMarker() {
        for (var i = 0; i < markerArr.length; i++) {      
            var json = markerArr[i];
            var point = new BMap.Point(json.point.split(",")[0], json.point.split(",")[1]);
            var iconImg = createIcon(json.icon);
            var marker = new BMap.Marker(point, { icon: iconImg });
            var iw = createInfoWindow(i);

            var label = new BMap.Label(json.title, { "offset": new BMap.Size(json.icon.lb - json.icon.x + 10, -20) });
            marker.setLabel(label);
            map.addOverlay(marker);
            label.setStyle({
                borderColor: "#808080",
                color: "#333",
                cursor: "pointer"
            });

            (function () {
                var index = i;
                var _marker = marker;
                var _iw = iw;

                _marker.addEventListener("click", function () {
                    this.openInfoWindow(_iw);
                });
                _iw.addEventListener("close", function () {
                    _marker.getLabel().show();
                })
                label.addEventListener("click", function () {
                    _marker.openInfoWindow(_iw);
                })
                if (json.isOpen) {
                    label.hide();
                    _marker.openInfoWindow(_iw);
                }
            })()
        }
    }
    //创建InfoWindow     
    function createInfoWindow(i) {
        var json = markerArr[i];
        var str = "";
        if (!json.title.indexOf("[")) {
            str += "</br></br><span><font color='Red'>" + json.SnararmNum + "</font></span>";
        } else if (!json.title.indexOf("K")) {
            str = "";
        } else {
            str = json.content;
        }
        var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>" + str + "</div>");
        return iw;
    }

    //创建一个Icon
    function createIcon(json) {
        var icon = new BMap.Icon("/static/admin/images/map/main.png",
        new BMap.Size(json.w, json.h), { imageOffset: new BMap.Size(-json.l, -json.t),
            infoWindowOffset: new BMap.Size(json.lb + 5, 1), offset: new BMap.Size(json.x, json.h)
        })
        return icon;
    }
    //百度地图API-开发-工具支持-地图生成器-添加标注
    //标注线数组
    
    //向地图中添加线函数
    function addPolyline() {
        var plPoints = $('#line').val();
        var plPoints = eval('('+plPoints+')');
        for(var k=0; k<plPoints.length; k++){
            var len = plPoints[k].length - 1;
            for (var i = 0; i <= len; i++) {
                var json = plPoints[k][i];
                var points = [];
                for (var j = 0; j < json.points.length; j++) {
                    var p1 = json.points[j].split(",")[0];
                    var p2 = json.points[j].split(",")[1];
                    points.push(new BMap.Point(p1, p2));
                }
                var line = new BMap.Polyline(points, { strokeStyle: json.style, strokeWeight: json.weight, strokeColor: json.color, strokeOpacity: json.opacity });
                map.addOverlay(line);
            }
        }
    }
</script>
</body>
</html>