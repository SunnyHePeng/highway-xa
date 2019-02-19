<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>获取农民工工资系统ID信息</title>
    <link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/style.css?v=1" />
    <link rel="stylesheet" type="text/css" href="/lib/fancybox/fancybox.css" />
</head>
<link rel="stylesheet" href="">
<body>
<h3 class="text-c  col-xs-8 col-xs-offset-2">农民工工资系统ID信息</h3>
<div class=" col-xs-8 col-xs-offset-2 mt-20">
    <table class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th>序号</th>
            <th>职务</th>
            <th>ID</th>
        </tr>
        </thead>
        <tbody class="data_list">

        </tbody>
    </table>
</div>

</body>
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" src="/lib/MD5/md5.js"></script>
<script type="text/javascript">
    $(function() {
        $.post("http://gz.xawhgs.com/Services/GetSystemUnits.ashx", {
            userName: "FTPUser",
            pwd: "FTP88341268"
        }, function(data) {
            data=JSON.parse(data);
            if(data.result){
                var content=data.content;
//                console.log(content);
                var htmlstr='';
                for(var i=0;i<content.length;i++){
                    htmlstr+='<tr class="text-c">'+
                        '<td>'+i+'</td>'+
                        '<td>'+content[i].Name+'</td>'+
                        '<td>'+content[i].Id+'</td>'+
                        '</tr>';
                }
                $(".data_list").empty().append(htmlstr);
            }else{
                alert('获取失败');
            }
        })
    });

</script>
</html>