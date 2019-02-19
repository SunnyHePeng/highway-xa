
@extends('admin.layouts.iframe')

@section('container')
    <style>
        .docs-example-title {
            position:relative;
            margin: 15px 0;
            padding: 39px 19px 14px;
            *position:static;
            *padding-top: 19px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;border-radius: 4px
        }
        .docs-example-title:after{
            content: "标题";
            position: absolute;
            top: -1px;left: -1px;
            *position:static;
            padding: 3px 7px;
            font-size: 24px;
            font-weight: bold;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: #9da0a4;
            -webkit-border-radius: 4px 0 4px 0;
            -moz-border-radius: 4px 0 4px 0;
            border-radius: 4px 0 4px 0
        }
        .docs-example-content {
            position:relative;
            margin: 15px 0;
            padding: 39px 19px 14px;
            *position:static;
            *padding-top: 19px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;border-radius: 4px
        }
        .docs-example-content:after{
            content: "内容";
            position: absolute;
            top: -1px;left: -1px;
            *position:static;
            padding: 3px 7px;
            font-size: 20px;
            font-weight: bold;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: #9da0a4;
            -webkit-border-radius: 4px 0 4px 0;
            -moz-border-radius: 4px 0 4px 0;
            border-radius: 4px 0 4px 0
        }
        .docs-example-accessory {
            position:relative;
            margin: 15px 0;
            padding: 39px 19px 14px;
            *position:static;
            *padding-top: 19px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;border-radius: 4px
        }
        .docs-example-accessory:after{
            content: "附件";
            position: absolute;
            top: -1px;left: -1px;
            *position:static;
            padding: 3px 7px;
            font-size: 20px;
            font-weight: bold;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: #9da0a4;
            -webkit-border-radius: 4px 0 4px 0;
            -moz-border-radius: 4px 0 4px 0;
            border-radius: 4px 0 4px 0
        }
    </style>

    <link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/style.css?v=1" />
    <link rel="stylesheet" type="text/css" href="/lib/fancybox/fancybox.css" />

    <div class="codeView docs-example-title">
        <h3>@if($notice){{$notice->title}}@endif</h3>
    </div>
    <div class="codeView docs-example-content">
        <p class="f-18">
            @if($notice)
                @if($notice->content=='')
                    <span class="c-blue">该公告没有内容信息</span>
                @else
                    {{$notice->content}}
                @endif
            @endif
        </p>
    </div>
    <div class="codeView docs-example-accessory">
        @if($notice)
            @if($notice->accessory_addr == '')
                <span class="c-blue">该公告没有附件信息</span>
            @else
                <input class="btn btn-success radius mt-20 download-file" type="button" value="下载附件" data-url="{{url('notice/download/'.$notice->id)}}">
            @endif
        @endif
    </div>



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
        $(".download-file").on('click',function(){
            var url=$(this).attr("data-url");

            window.open(url);
        });



    </script>

@stop