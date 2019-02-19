@extends('admin.layouts.iframe')

@section('container')

    <article class="cl pd-20">
        <form class="form form-horizontal" id="form_container" data-url="{{ url('snbhz/add_device_failure_push_user') }}">
            <div class="row cl show-all hidden-pass">
                <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>人员信息：</label>
                <div class="formControls col-xs-3 col-sm-3">
            <span class="select-box">
                <select class="select msg_must" id="user_id" name="user_id" placeholder="请选择用户信息">
                    @if($user_data)
                       @foreach($user_data as $v)
                            <option value="{{$v['id']}}">{{'用户姓名:'.$v['name'].'/登陆账号:'.$v['username'].'/职位：'.$v['posi']['name']}}</option>
                        @endforeach
                    @endif
                </select>
            </span>
                </div>
            </div>
            <div class=" col-xs-offset-3 col-sm-offset-3 mt-20">
                <input class="btn btn-primary radius sub" type="button" value="提交">
            </div>


            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript">
        list.init();
        $(".sub").on('click',function(event){

            layer.confirm('确定数据填写无误并提交吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                event.preventDefault();
                var data=$("#form_container").serialize();
                var url=$("#form_container").attr('data-url');
                console.log(data);
                $.post(url,data,function(data){
                    if(data.status==1){
                        layer.alert(data.mess);
                    }
                    if(data.status==0){
                        layer.msg(data.mess, {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            parent.parent.location.reload();
                        });
                    }
                })
            }, function(){

            });
        });
    </script>
@stop