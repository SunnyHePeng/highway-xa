@extends('admin.layouts.iframe')

@section('container')
<link href="/static/admin/css/video-js.min.css" rel="stylesheet">
<article class="page-container" id="show_detail">
 	
	<div class="row cl mt-20">
	  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
	    <div class="panel panel-primary">
				<div class="panel-header">{{ $video->title }}</div>
	      <div class="panel-body" class="padding: 0;">
					<div class="cl pd-5 bg-1 bk-gray mb-10">
									<span class="l ml-10 pt-5">
										{{ $video->decription or '无'}}
									</span>
					</div>
					<div id="video" style="width:600px;height:400px;"></div>						
	      </div>
	    </div>
	  </div>
	</div>
</article>
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ckplayer/ckplayer.js"></script>
<script type="text/javascript">
	var videoObject = {
		container: '#video',//“#”代表容器的ID，“.”或“”代表容器的class
		variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
		flashplayer:true,//如果强制使用flashplayer则设置成true
		video:'{{ asset($video->path) }}'//视频地址
	};
	var player=new ckplayer(videoObject);
</script>
@stop