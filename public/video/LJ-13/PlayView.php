﻿<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
	<script src="jquery.js"></script>
</head>
<body>
<table>
<tr>
<td>
<object classid="clsid:AC036352-03EB-4399-9DD0-602AB1D8B6B9" id="PreviewOcx" width="650px" height="500" name="ocx" >
</td>
<td>
<table width="100%" style="border: thin solid #C0C0C0;">
					<tbody><tr>
						<td width="40%" align="right">
							抓图格式
						</td>
						<td width="55%" align="center">
							<select style="width: 130px" id="SelectPicType">
								<option value="0">
									JPEG
								</option>
								<option value="1">
									BMP
								</option>
							</select>
						</td>
					</tr>
					<tr>
					     <td align="right">连续抓图方式</td>
					     <td align="center">
					     	<select style="width: 130px" id="SelectCapMode">
								<option value="0">
									按时间
								</option>
								<option value="1">
									按帧
								</option>
							</select>
					     </td>
					</tr>
					<tr>
					     <td align="right">抓图时间间隔,单位(ms)</td>
					     <td align="center"><input type="text" id="TextTimeSpan" style="width: 130px" value="1"></td>
					</tr>
					<tr>
					     <td align="right">抓图张数</td>
					     <td align="center"><input type="text" id="TextCapCounts" style="width: 130px" value="1"></td>
					</tr>
					<tr>
						<td align="right">
							抓图路径
						</td>
						<td align="center">
							<input id="TextPicPath" type="text" style="width: 130px" value="C:\CapPic">
						</td>
					</tr>
					<tr>
						<td align="right">
							最小抓图磁盘空间
						</td>
						<td align="center">
							<select style="width: 130px" id="MinDiskSpaceForCap">
								<option value="256">
									256MB
								</option>
								<option value="512">
									512MB
								</option>
								<option value="1024">
									1GB
								</option>
								<option value="2048">
									2GB
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">
							紧急录像路径
						</td>
						<td align="center">
							<input id="TextRecordPath" type="text" style="width: 130px" value="C:\Record">
						</td>
					</tr>
					<tr>
					    <td align="right">录像分包方式</td>
					    <td align="center">
					        <select style="width: 130px" id="RecordMode">
					        <option value="0">
					           按时间分包
					        </option>
					        <option value="1">
					           按大小分包
					        </option>
					        </select>
					    </td>
					</tr>
					<tr>
					    <td align="right">
					    录像文件按秒
					    </td>
					    <td align="center">
					    <input type="text" id="TextRecordIime" style="width: 130px" value="10">
					    </td>
					</tr>
					<tr>
						<td align="right">
							录像文件包大小
						</td>
						<td align="center">
							<select style="width: 130px" id="PreviewPkgSize">
								<option value="256000000">
									256MB
								</option>
								<option selected="selected" value="512000000">
									512MB
								</option>
								<option value="1024000000">
									1GB
								</option>
								<option value="2048000000">
									2GB
								</option>
							</select>
						</td>
					</tr>
<!--					<tr>
					     <td align="right">最长录像时间,取0不限制</td>
					     <td align="center"><input type="text" id="TextMLongTime"></td>
					</tr>-->
					<tr>
						<td align="right">
							最小录像磁盘空间
						</td>
						<td align="center">
							<select style="width: 130px" id="MinDiskSpaceForRec">
								<option value="256">
									256MB
								</option>
								<option value="512">
									512MB
								</option>
								<option value="1024">
									1GB
								</option>
								<option value="2048">
									2GB
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
							<input type="button" value="设置本地参数" onclick="SetlocalParam()">
						</td>
					</tr>
				</tbody></table>
				</td>
</tr>
</table>				
</body>
</html>
<script>
function loginCMS(){
	var userName="admin";
	var pw="Pkpm@123321";
	var ipAdd="175.6.228.235";
	var port="81";	
	var OCXobj = document.getElementById("PreviewOcx");	
	var result = OCXobj.SetLoginType(0);    // 设置同步登入模式
	var ret=OCXobj.Login(ipAdd,port,userName,pw);
	switch(ret){
	    case 0:	        
	        alert("同步登录成功！");
	        showMethodInvokedInfo("同步Login,GetResourceInfo 接口调用成功！");
	        break;
		case -1:
			alert("同步登录失败！");
			showMethodInvokedInfo("同步Login接口调用失败！错误码："+ OCXobj.GetLastError());
			break;
		default:
			break;
	}
	
}

function startPreview(cameraId) {
	var OCXobj = document.getElementById("PreviewOcx");	
	var ret=OCXobj.StartTask_Preview_FreeWnd(cameraId);
	switch(ret)
	{
	    case 0:
			showMethodInvokedInfo("StartTask_Preview_FreeWnd接口调用成功！");
			break;
		case -1:
			showMethodInvokedInfo("StartTask_Preview_FreeWnd接口调用失败！错误码："+ OCXobj.GetLastError());
			break;
		default:
			break;
	}
}

function SetWndNum(WndNum)
{
   var OCXobj = document.getElementById("PreviewOcx");      
   OCXobj.SetWndNum(WndNum);
}

function showMethodInvokedInfo(s)
{
  //alert(s);
}

function initCameraList(){
	var OCXobj = document.getElementById("PreviewOcx");
	var xmlStr=OCXobj.GetResourceInfo(4);
	var htmlStr="";
	//var xmldom=getXmlDomFromStr(xmlStr);
	//$(xmldom).find("CameraInfo").each(function() {
	//htmlStr += "<li ondblclick='startPreview(" + $(this).find("CameraID").text() + ");' onMouseDown='setCameraIndexCode(\"" + $(this).find("CameraIndexCode").text() + "\");' onMouseUp='setCameraId(" + $(this).find("CameraID").text() + ");'><a href='#' style='text-decoration:none'>" + $(this).find("CameraName").text() + "</a></li>" ;
        //});
	//$("#tree").html(htmlStr); 
}

function SetlocalParam(){
	var PicType = $("#SelectPicType").val();
	var PicPath = $("#TextPicPath").val();
	var PicCapType = $("#SelectCapMode").val();
	var PicSpanTime = $("#TextTimeSpan").val();
	var PicCounts = $("#TextCapCounts").val();
	//var minSpace4Pic = $("#MinDiskSpaceForCap").val();
	var RecordType = $("#RecordMode").val();
	var MaxRecordTimes = $("#TextMLongTime").val();
	var RecordTimes = $("#TextRecordIime").val();
    var RecordPath = $("#TextRecordPath").val();
    var RecordSize = $("#PreviewPkgSize").val();
    //var minSpace4Rec = $("#MinDiskSpaceForRec").val();
    if(PicPath=="" || RecordPath==""){
		alert("保存路径不能为空！");
	    return;
    }
    
	var OCXobj = document.getElementById("PreviewOcx");

	//设置图片保存路径和格式
	if (PicCapType == 1) {
	    if (PicCounts == "") {
	        alert("设置张数不能为空！");
	        return;
	    }
	    var iRet = OCXobj.SetCaptureParam(PicType, PicPath, PicCapType, 0, PicCounts);
	    switch (iRet) {
	        case -1:
	            showMethodInvokedInfo("SetCaptureParam接口调用失败！错误码：" + OCXobj.GetLastError());
	            break;
	        case 0:
	            showMethodInvokedInfo("SetCaptureParam接口调用成功！");
	            break;
	        default:
	            break;
	    }
	}
	else if (PicCapType == 0) {
	    if (PicCounts == "" || PicSpanTime == "") {
	        alert("设置张数和抓拍时间间隔不能为空！");
	        return;
	    }
	    var iRet = OCXobj.SetCaptureParam(PicType, PicPath, PicCapType, PicSpanTime, PicCounts);
	    switch (iRet) {
	        case -1:
	            showMethodInvokedInfo("SetCaptureParam接口调用失败！错误码：" + OCXobj.GetLastError());
	            break;
	        case 0:
	            showMethodInvokedInfo("SetCaptureParam接口调用成功！");
	            break;
	        default:
	            break;
	    }
	}
    
    
    //设置保存图片磁盘空间最小值
    //OCXobj.SetPicDiskMinSize(minSpace4Pic);               //0813 zdy
    //showMethodInvokedInfo("SetPicDiskMinSize接口调用成功！"); //0813 zdy
   
       //设置录像保存路径和文件大小
        if (RecordType == 0) {
            if (MaxRecordTimes == "" || RecordTimes == "") {
            alert("设置分包时间和最大录像时间不能为空");
            return;
        }
        var iRet = OCXobj.SetRecordParam(RecordPath, 10, RecordTimes, RecordType);
            switch(iRet) {
                case -1:
                    showMethodInvokedInfo("SetRecordParam接口调用失败！错误码：" + OCXobj.GetLastError());
            	    break;
            	case 0:
            	    showMethodInvokedInfo("SetRecordParam接口调用成功！");
            	    break;
            	default:
            	    break;
            }
       }
       else if (RecordType == 1) {
            if (MaxRecordTimes == "") {
                alert("设置最大录像时间不能为空");
                return;
            }
            var iRet = OCXobj.SetRecordParam(RecordPath, RecordSize, 10, RecordType);
            switch (iRet) {
                case -1:
                    showMethodInvokedInfo("SetRecordParam接口调用失败！错误码：" + OCXobj.GetLastError());
                    break;
                case 0:
                    showMethodInvokedInfo("SetRecordParam接口调用成功！");
                    break;
                default:
                    break;
            }
       }
    
    //设置录像磁盘空间最小值
    //OCXobj.SetRecordDiskMinSize(minSpace4Rec);
    //showMethodInvokedInfo("SetRecordDiskMinSize接口调用成功！");
	alert("设置成功！");
}

window.onload=function()
{   
   setTimeout(function(){       
       loginCMS();
       SetWndNum("4");
       initCameraList();
       startPreview(2621);
       startPreview(2626);
   },100);
}
</script>