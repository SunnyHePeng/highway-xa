@extends('admin.layouts.iframe')

@section('container')
    <style>
        .sub{
            width:60px;
            height: 30px;
            margin-left: 20%;
        }
    </style>
    <link rel="stylesheet" href="/lib/select2/css/select2.min.css">
    <form class="form form-horizontal " id="form_container" data-url="{{url('stat/sd_inform_add')}}">
        <div class="row cl show-all text-c">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>添加的人员信息：</label>
            <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="user_id" name="user_id" placeholder="请选择">
                    <option value="0">请选择</option>
                     @if($user)
                         @foreach($user as $v)
                             <option value="{{$v['id']}}">{{$v['name'].'/'.$v['posi']['name'].'/'.$v['company']['name']}}</option>
                         @endforeach
                     @endif
                </select>
            </span>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <br><br>
        <input class="btn btn-primary radius login-button sub" readonly="readonly" value="提交">
    </form>
@stop

@section('script')
    <script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="/static/common/js/H-ui.js"></script>
    <script type="text/javascript" src="/static/admin/js/H-ui.admin.page.js"></script>
    <script type="text/javascript" src="/lib/select2/js/select2.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/static/admin/js/password.js"></script>
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript">
        $('#project_id').select2();
        list.init();
        $(function(){
            $('.sub').on('click',function(){
                $("#form_container").submit(function(e){
                    e.preventDefault();
                });
                var data=$("#form_container").serialize();

                var url=$("#form_container").attr('data-url');
                $.post(url,data,function(data){
                    if(data.status==0){
                        layer.alert(data.mess);
                    }
                    if(data.status==1){
                        layer.msg(data.mess, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            //do something
                            parent.parent.location.reload();
                        });
                    }
                });
            });


        });

    </script>
@stop