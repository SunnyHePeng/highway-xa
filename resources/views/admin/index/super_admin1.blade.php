@extends('admin.layouts.default')

@section('container')
<article class="cl pd-20">
	<div class="panel panel-primary mt-20">
		<div class="panel-header">今日概况</div>
		<div class="panel-body row cl">
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">代理总数</div>
					<div class="panel-body">{{$agent ? $agent : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日出卡</div>
					<div class="panel-body">{{$card}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日充值(审核通过)</div>
					<div class="panel-body">{{$pay ? $pay : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日生成码</div>
					<div class="panel-body">{{$make_code ? $make_code : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日激活码</div>
					<div class="panel-body">{{$active_code ? $active_code : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日返现</div>
					<div class="panel-body">{{$back ? $back : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日消费</div>
					<div class="panel-body">{{$consume ? $consume : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-danger mb-20 ta-c radius">
					<div class="panel-header">充值待审核</div>
					<div class="panel-body">{{$consume ? $consume : 0}}</div>
				</div>
			</div>
		</div>
	</div><!-- panel-success -->
	<div class="panel panel-secondary mt-20">
		<div class="panel-header">出卡概况</div>
		<div class="panel-body row cl">
			<input id="chart" value="{{$chart}}" type="hidden">
			<div id="container" style="width: 80%; margin: 0 auto"></div>
		</div>
	</div>
</article>
@stop

@section('script')
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript">
chart.column('container', 'chart');
</script>
@stop
