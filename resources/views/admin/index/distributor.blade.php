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
		<div class="panel-header">我的卡</div>
		<div class="panel-body row cl">
			@if($card)
			@foreach($card as $value)
			<div class="col-xs-12 col-sm-3">
				<div class="panel panel-default mb-20 ta-c radius">
					<div class="panel-header">{{$value['name']}}</div>
					<div class="panel-body">@if($value['count']) {{$value['count']}} @else 0 @endif</div>
				</div>
			</div>
			@endforeach
			@endif
		</div>
	</div>
</article>
@stop