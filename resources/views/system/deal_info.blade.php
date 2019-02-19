@extends('admin.layouts.iframe')

@section('container')
<article class="page-container">
	<form class="form form-horizontal" id="form_container" data-url="{{$url}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>意见：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<textarea class="textarea" placeholder="输入处理意见" id="info" name="info"></textarea>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">处理图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div class="uploader-thum-container">
					<button class="btn btn-primary upload-btn radius"><i class="Hui-iconfont">&#xe642;</i> 浏览文件</button>
					<input type="file" name="file_upload" id="imgPicker" accept="image/jpeg,image/png,image/gif"/>
					<button id="uploadimg" class="btn btn-default btn-uploadstar radius ml-10">开始上传</button>
				</div>
				<div class="c-red">支持jpg,gif,png,jpeg，最大1M</div>
				<div id="imgList" class="uploader-list"></div>
				<div id="imgShow" class="uploader-show"></div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">处理文件：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div class="uploader-thum-container">
					<button class="btn btn-primary upload-btn radius"><i class="Hui-iconfont">&#xe642;</i> 浏览文件</button>
					<input type="file" name="file_upload" id="filePicker" />
					<button id="uploadfiles" class="btn btn-default btn-uploadstar radius ml-10">开始上传</button>
				</div>
				<div class="c-red">支持doc,docx,txt，最大1M</div>
				<div id="fileList" class="uploader-list"></div>
				<div id="fileShow" class="uploader-show"></div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"></label>
			<div class="formControls col-xs-8 col-sm-9">
				<button class="btn btn-primary radius" type="submit">确 定</button>
			</div>	
		</div>
		<input type="hidden" value="{{$id}}" id="id" name="id">
		<input type="hidden" value="{{$d_id}}" id="id" name="d_id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>
@stop

@section('script')
<script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/plupload-2.1.9/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/static/admin/js/upload.js"></script> 
<script type="text/javascript">
list.init();

uploadFile('imgPicker', '{{url('snbhz/file/upload')}}', 'uploadimg', 'imgList', 'imgShow', {name:'thumb',type:'images'}, 'thumb')
uploadFile('filePicker', '{{url('snbhz/file/upload')}}', 'uploadfiles', 'fileList', 'fileShow', {name:'file',type:'file'}, 'file')

$("#form_container").validate({
	submitHandler: function(form) {
	    common.doAjax($('#form_container'), 'POST');
	},

	errorPlacement: function(error, element) {
	    error.insertAfter(element);
	}
});
</script>
@stop