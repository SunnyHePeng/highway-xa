@extends('admin.layouts.iframe')

@section('container')
    <link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/style.css?v=1" />
    <link rel="stylesheet" type="text/css" href="/lib/fancybox/fancybox.css" />


    <form class="form form-horizontal" id="form_container" data-url="{{url('notice/publish_new_notice')}}">

        <div class="formControls col-xs-12 col-sm-10">
            <label class="form-label col-xs-2 col-sm-2"><span class="c-red">*</span>标题：</label>
            <div class="formControls col-xs-10 col-sm-8">
                <input type="text" placeholder="请输入标题，不能超过30个字符" id="title" name="title" class="textarea radius size-S" style="font-size: 20px;">
            </div>
        </div>

        <div class="formControls col-xs-12 col-sm-10 mt-30">
            <label class="form-label col-xs-2 col-sm-2">内容：</label>
            <div class="formControls col-xs-10 col-sm-8">
                <textarea name="content" id="content" placeholder="请输入内容" cols="50" rows="50" class="textarea radius size-XL" style="font-size: 20px; height: 300px;" ></textarea>
            </div>
        </div><br><br>

        <div class="formControls col-xs-12 col-sm-10 mt-30">
            <label class="form-label col-xs-2 col-sm-2">附件文件：</label>
            <div class="formControls col-xs-10 col-sm-8">
                <div class="uploader-thum-container">
                    <button class="btn btn-secondary radius upload-btn radius"><i class="Hui-iconfont">&#xe642;</i> 浏览文件</button>
                    <input type="file" name="file_upload" id="filePicker" />
                    <button id="uploadfiles" class="btn btn-default btn-uploadstar radius ml-10">上传附件</button>
                </div>
                <div class="c-red">支持doc,docx,txt,xls,xlsx最大1M</div>
                <div id="fileList" class="uploader-list"></div>
                <div id="fileShow" class="uploader-show"></div>
            </div>
        </div>
        <input type="hidden" name="publish_user_id" value="{{$user_id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <br><br>
        <div class="formControls col-xs-12 col-sm-10 mt-50">
            <label class="form-label col-xs-2 col-sm-2"></label>
            <div class="formControls col-xs-10 col-sm-8">
                <button class="btn  sub btn-primary radius" type="submit">发布公告</button>
            </div>
        </div>
    </form>
@stop

@section('script')
    <script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="/static/common/js/H-ui.js"></script>
    <script type="text/javascript" src="/static/admin/js/H-ui.admin.page.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/plupload-2.1.9/js/plupload.full.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/notice_upload.js"></script>
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript">
        list.init();
        uploadFile('filePicker', '{{url('notice/file/upload')}}', 'uploadfiles', 'fileList', 'fileShow', {name:'file',type:'file'}, 'file')
        $(".sub").on('click',function(event){
            event.preventDefault();
            var data=$("#form_container").serialize();
            var url=$("#form_container").attr("data-url");

            $.post(url,data,function(data){
                if(data.status==1){
                    layer.alert(data.info);
                }
                if(data.status==0){
                    layer.msg(data.info, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){

                        parent.parent.location.reload();
                    });

                }

            });


        });

    </script>

@stop