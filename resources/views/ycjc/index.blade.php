@extends('admin.layouts.default')

@section('container')
<style type="text/css">
span.tq {width: 130px;display: inline-block;}
</style>
<div class="row cl mb-20">
	<div class="col-xs-12 col-sm-12">
		<p><strong>当天天气：</strong></p>
		<div style="margin-left:20px;">
			@if($weather)
				<p><strong>获取时间:</strong> {{$weather['time']}}</p>
				<p>
					<span class="tq"><strong>温度:</strong> {{$weather['temperature']}}℃</span>
					<span class="tq"><strong>天气:</strong> {{$weather['skycon']}}</span>
					<span class="tq"><strong>PM2.5:</strong> {{$weather['pm25']}}</span>
				</p>
				<p>
					<span class="tq"><strong>相对湿度:</strong> {{$weather['humidity']}}</span>
					<span class="tq"><strong>降水强度:</strong> {{$weather['precipitation']['local']['intensity']}}</span>
					<span class="tq"><strong>云量:</strong> {{$weather['cloudrate']}}</span>
				</p>
				<p>
					<span class="tq"><strong>风向:</strong> {{$weather['wind']['direction']}}</span>
					<span class="tq"><strong>风速:</strong> {{$weather['wind']['speed']}}km/h</span>
				</p>
			@else
				暂无信息
			@endif
		</div>
	</div>
	<br><br>
	<div class="col-xs-12 col-sm-12">
		<p><strong>实时监测数据：</strong></p>
		<div style="margin-left:20px;">
			<p><strong>PM2.5:</strong> {{$pm25}}</p>
			<p><strong>PM10:</strong> {{$pm10}}</p>
			<p><strong>温度:</strong> {{$wd}}</p>
			<p><strong>湿度:</strong> {{$sd}}</p>
			<p><strong>风速:</strong> {{$fs}}</p>
			<p><strong>风向:</strong> {{$fx}}</p>
			<p><strong>噪声:</strong> {{$zs}}</p>
		</div>
	</div>
</div>		
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop
