@extends('admin.layouts.tree')

@section('container')

@include('mixplant.snbhz._index_info')


<input type="hidden" value="{{$ztree_data}}" id="tree_data">
<input type="hidden" value="{{$ztree_name}}" id="tree_name">
<input type="hidden" value="{{$ztree_url}}" id="tree_url">
<input type="hidden" value="" id="tree_page">
@stop

@section('script')
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript" src="/lib/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript" src="/static/admin/js/chart.js"></script>
<script type="text/javascript">
list.init();
function getinfo()
{

    $.ajax({
        type:'get',
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
        url:'{{url('snbhz/get_today_warn')}}',
        success:function(data){
            if(data.data.length>0){

                var mess=data.data;

                var str='';
                var warn_length=mess.length;
                for(var i=0;i<mess.length;i++){

                    var url='{{url('snbhz/deal')}}'+'/'+mess[i].id+'?d_id='+mess[i].device.id;
                    str +="<span>"+"<a data-title='拌和详细信息' href='javascript:;' data-url='"+url+"' class='open-iframe' onclick='common.openIframe($(this).attr(\"data-title\"), $(this).attr(\"data-url\"), true)'>"+mess[i].section.name+'标段'+'-'+mess[i].device.name+"报警:"+mess[i].warn_info+"</a>"+"</span>";
                }
                var num=$('.warn_num').text();
                if(num < warn_length){
                    $('.warn_num').text(warn_length);
                    var audio_data="<div style=\"width:0;height:0; overflow:hidden;\">\n" +
                        "<object height=\"100\" width=\"100\" data=\"http://tts.baidu.com/text2audio?lan=zh&pid=101&ie=UTF-8&text=%E6%82%A8%E6%9C%89%E6%8B%8C%E5%90%88%E7%AB%99%E6%8A%A5%E8%AD%A6%E6%B6%88%E6%81%AF%EF%BC%8C%E8%AF%B7%E6%82%A8%E5%8F%8A%E6%97%B6%E5%A4%84%E7%90%86&qq-pf-to=pcqq.c2c\"></object>\n" +
                        "</div>";
                    $('.warn_mess').append(audio_data);
                }

                $('.news_mess').css('display','inline-block');
                $('.warn_mess').find('span').remove();
                $('.warn_mess').append(str);
                $('.warn_mess').find('span>a').css('color','red');


            }else{
                $('.warn_num').text(0);
                $('.news_mess').css('display','none');
                $('.warn_mess').empty();
            }
        }
    })
}
getinfo();
window.setInterval(function(){
    getinfo();
},60000);
</script>
@stop
