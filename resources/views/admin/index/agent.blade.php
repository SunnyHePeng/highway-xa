@extends('admin.layouts.default')

@section('container')
<article class="cl pd-20">
	<p class="f-26">欢迎您，{{$user['username']}}</p>
	<div class="panel panel-warning">
		<div class="panel-header">公告</div>
		<div class="panel-body" style="max-height:150px; overflow-y:scroll;">
			@if($notice)
			@foreach($notice as $key=>$value)
			<p>{{$key+1}}.&nbsp;&nbsp;{{$value['content']}}</p>
			@endforeach
			@endif
		</div>
	</div>
	<div class="panel panel-secondary mt-20">
		<div class="panel-header">我的</div>
		<div class="panel-body row cl">
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">代理</div>
					<div class="panel-body">{{$agent ? $agent : 0}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">返现合计</div>
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
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">今日出卡</div>
					<div class="panel-body">{{$card}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">余额</div>
					<div class="panel-body">{{$balance}}</div>
				</div>
			</div>

		</div>
	</div>
</article>
@stop
