<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>获取摄像头信息</title>
    <link rel="stylesheet" type="text/css" href="/static/common/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/css/style.css?v=1" />
    <link rel="stylesheet" type="text/css" href="/lib/fancybox/fancybox.css" />
</head>
<link rel="stylesheet" href="">
<body>
<h3 class="tex-c">试验室系统设备对应摄像头信息</h3>
<div class=" col-xs-12 col-sm-12 mt-20">
    <table class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th>序号</th>
            <th>摄像头名称</th>
            <th>摄像头编号</th>
            <th>摄像头对应编码设备uuid</th>
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
    //ocx元素
    var ocx_id = "spv";
    var userName='ChangAnDaXue';
    var pw='ChangAnDaXue123';
    var ip='175.6.228.72';
    var port='80';
    var host=ip+':'+port;
    var appkey="dc50e4f1";
    var secret="19ae479c81d14e42b7812fc3d5df02fa";
    var defaultUserUUid='da2139d154784554bc05973db4567dad';
    var time=new Date().getTime();
    var report='';
    //调用预览接口
    function preview(ocx_id){

        var ocxObj=document.getElementById(ocx_id);

        var userName='ChangAnDaXue';
        var pw='ChangAnDaXue123';
        var ip='175.6.228.72';
        var port='80';
        var appkey="dc50e4f1";
        var secret="19ae479c81d14e42b7812fc3d5df02fa";
        var defaultUserUUid='da2139d154784554bc05973db4567dad';
        var time=new Date().getTime();
        getlist(appkey,time,secret);
//        console.log(report);
//        ocxObj.MPV_StartPreview(report);

//        getuuid(appkey,secret,time);
//        getlist(appkey,time,secret);


    }
    //获取默认用户uuid
    function getuuid(appkey,secret,time,ip,port){
        var url_str="/openapi/service/base/user/getDefaultUserUuid";
//         组装生成token
        var json_str={
            "appkey":appkey,
            "time":time
        };
        json_str=JSON.stringify(json_str);
//        console.log(json_str);
        var str=url_str+json_str+secret;

        var token=hex_md5(str).toUpperCase();
//        console.log(token);
        //获取默认用户uuid的url
        var url="http://"+ip+":"+port+"/openapi/service/base/user/getDefaultUserUuid";
        var input_data={
            "appkey":appkey,
            "time":time,
            "token":token
        };
        //默认用户uuid
        var defaultUserUUid='da2139d154784554bc05973db4567dad';

//        console.log(input_data);
//        $.post(url,input_data,function(data){
//            console.log(data);
//        });
//        getlist();
    }

    /**
     * 获取token
     * @param url_param 请求url(去掉host域名)
     * @param request_param 请求参数 一个json对象
     * @param secret
     */
    function getToken(url_param,request_param,secret){
//        console.log(request_param);
        //1 将请求参数json对象转为json字符串
//        var request_param=JSON.stringify(request_param);
        //2 拼接字符串
        var token_str=url_param+request_param+secret;
//
        //3 将拼接好的字符串进行md532位加密并转换为大写
        var token=hex_md5(token_str).toUpperCase();
        return token;
    }


    /**
     * 获取摄像头点列表
     */
    function getlist(appkey,time,secret){

        var url_param='/openapi/service/vss/res/getCamerasEx';
        var input_data={"appkey":appkey,"time":time,"pageNo":1,"pageSize":1000,"opUserUuid":"da2139d154784554bc05973db4567dad"};
        //获取token
        var token=getToken(url_param,JSON.stringify(input_data),secret);
        $.ajax({
            url: "http://" + '175.6.228.72:80' + "/openapi/service/vss/res/getCamerasEx?token=" + token,
            type:"POST",
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify({
                appkey: appkey,
                time: time,
                "pageNo":1,
                "pageSize":1000,
                "opUserUuid":"da2139d154784554bc05973db4567dad"
            }),
            success: function (jVal) {
                if(jVal.errorCode != 0){
                    console.log(jVal);
                    alert('获取摄像头数据失败');
                }else{
                   var htmlstr='';
                   var data=jVal.data.list;
                     console.log(jVal);
                     for(var i=0;i<data.length;i++){
                         htmlstr+='<tr class="text-c">'+
                                  '<td>'+i+'</td>'+
                                  '<td>'+data[i].cameraName+'</td>'+
                                  '<td>'+data[i].cameraUuid+'</td>'+
                                  '<td>'+data[i].encoderUuid+'</td>'+
                                  '</tr>';
                     }
                     $(".data_list").empty().append(htmlstr);
                }
            }
        })

    }




    //    videoInit(ocx_id);
    preview(ocx_id);

</script>
</html>