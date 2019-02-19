//顶部栏上的最新公告，每隔1分钟请求一次，获取最新的公告信息
function getNewNotice(){
    var url=$(".top_notice").attr("data-url");
    $.get(url,function(data){
        if(data.status==0){

            var title_str=data.title;
            var is_read=data.is_read;
            var notice_hint_str='';
            if(is_read==0){
              //已阅读
               notice_hint_str='<span><i class="Hui-tags-icon Hui-iconfont f-16 c-fff trumpet">&#xe62f;</i></span>';
            }else{
                //未阅读
                notice_hint_str='<span><i class="Hui-tags-icon Hui-iconfont f-16 c-red trumpet">&#xe62f;</i></span>\n' +
                    '<span class="notice_hint c-red">新公告：</span>';
            }

            $(".notice_box").empty().text(title_str);
            $(".notice_hint").empty().html(notice_hint_str);
        }
    });
}
window.setInterval(function(){
    getNewNotice();
},60000);

