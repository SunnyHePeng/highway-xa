@extends('admin.layouts.login')

@section('content')
	<div class="login-form error">
		{{ $error }}<br><br>
		<?php 
			$url = isset($url) ? $url : 'javascript:history.back();';
			$btn = isset($btn) ? $btn : '';
		?>
		
		<a href="{{$url}}">返回</a>
		@if($btn)
		&emsp;&emsp;&emsp;&emsp;&emsp;<a href="{{$btn['url']}}">{{$btn['name']}}</a>
		@endif
	</div>
@stop
