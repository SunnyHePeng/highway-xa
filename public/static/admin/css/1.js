<script type="text/javascript">
        $(document).bind("contextmenu", function (e) {
            return false;
        });
        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        $(function () {
            data_load(getUrlParam("SectId"), getUrlParam("startTime"), getUrlParam("endTime"), getUrlParam("Page"), getUrlParam("PageSize"));
        });
        function data_load(SectId, startTime, endTime, Page, PageSize) {
            layer.load(2, {
                shade: [0.1, '#fff']
            });
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "../../DataSource/Cement_Bhz/PrintOrExport/DevlopeData.aspx",
                data: { "SectId": SectId, "startTime": startTime, "endTime": endTime, "Page": Page, "PageSize": PageSize },
                success: function (json) {
                    var $tb = $("#tbDatas tbody");
                    $tb.html("");
                    $.each(json, function (n, value) {
                        var tr = "<tr><td>" + value.number + "</td>";
                        tr += "<td>" + value.CollDate + "</td><td>" + value.bhcs + "</td><td>" + value.warnCnt + "</td>";
                        tr += "<td>" + value.item1 + "</td><td>" + value.item2 + "</td><td>" + value.item3 + "</td>";
                        tr += "<td>" + value.item4 + "</td><td>" + value.item5 + "</td><td>" + value.item6 + "</td>";
                        tr += "<td>" + value.item7 + "</td><td>" + value.item8 + "</td><td>" + value.minDate + "</td>";
                        tr += "<td>" + value.maxDate + "</td></tr>";
                        $tb.append(tr);
                    });
                    if (json.length != 0) {
                        $("#tbDatas caption").html("管理单位：" + json[0].SuperName + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;标段：" + json[0].SectName + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;设备名称：" + json[0].DevName);
                    }
                    layer.closeAll();
                },
                error: function (err) {
                    layer.closeAll();
                    showLayer(2, '网络异常,请重试', 2000);
                }
            });
        }

        function showLayer(img, txt, time) {
            layer.alert(txt, {
                icon: img,
                skin: 'layui-layer-molv',
                time: time
            });
        }
        function print_menu_click() {
            $("#div_tb").jqprint();
        }
        function export_menu_click() {
            toExcel('tbDatas', '')
        }
    </script>



    <script type="text/javascript">
    function toExcel(inTblId, inWindow) {
        if ($.browser.msie) { //如果是IE浏览器  
            try {
                var allStr = ""; var curStr = "";
                if (inTblId != null && inTblId != "" && inTblId != "null") {
                    curStr = getTblData(inTblId, inWindow);
                }
                if (curStr != null) {
                    allStr += curStr;
                }
                else {
                    alert("你要导出的表不存在！");
                    return;
                }
                var fileName = getExcelFileName();
                doFileExport(fileName, allStr);
            }
            catch (e) {
                alert("导出发生异常:" + e.name + "->" + e.description + "!");
            }
        } else {
            $('#' + inTblId + '').tableExport({ type: 'excel', escape: 'false' });
        }
    } function getTblData(inTbl, inWindow) {
        var rows = 0; var tblDocument = document;
        if (!!inWindow && inWindow != "") {
            if (!document.all(inWindow)) {
                return null;
            } else {
                tblDocument = eval(inWindow).document;
            }
        }
        var curTbl = tblDocument.getElementById(inTbl);
        if (curTbl.rows.length > 65000) {
            alert('源行数不能大于65000行');
            return false;
        }
        if (curTbl.rows.length <= 1) {
            alert('数据源没有数据');
            return false;
        }
        var outStr = "";
        if (curTbl != null) {
            for (var j = 0; j < curTbl.rows.length; j++) {
                for (var i = 0; i < curTbl.rows[j].cells.length; i++) {
                    if (i == 0 && rows > 0) {
                        outStr += " \t"; rows -= 1;
                    }
                    var tc = curTbl.rows[j].cells[i];
                    if (j > 0 && tc.hasChildNodes() && tc.firstChild.nodeName.toLowerCase() == "input") {
                        if (tc.firstChild.type.toLowerCase() == "checkbox") {
                            if (tc.firstChild.checked == true) {
                                outStr += "是" + "\t";
                            } else {
                                outStr += "否" + "\t";
                            }
                        }
                    } else {
                        outStr += " " + curTbl.rows[j].cells[i].innerText + "\t";
                    }
                    if (curTbl.rows[j].cells[i].colSpan > 1) {
                        for (var k = 0; k < curTbl.rows[j].cells[i].colSpan - 1; k++) {
                            outStr += " \t";
                        }
                    }
                    if (i == 0) {
                        if (rows == 0 && curTbl.rows[j].cells[i].rowSpan > 1) {
                            rows = curTbl.rows[j].cells[i].rowSpan - 1;
                        }
                    }
                }
                outStr += "\r\n";
            }
        } else {
            outStr = null; alert(inTbl + "不存在!");
        }
        return outStr;
    }
    function getExcelFileName() {
        var d = new Date(); var curYear = d.getYear(); var curMonth = "" + (d.getMonth() + 1);
        var curDate = "" + d.getDate(); var curHour = "" + d.getHours(); var curMinute = "" +
             d.getMinutes(); var curSecond = "" + d.getSeconds();
        if (curMonth.length == 1) {
            curMonth = "0" + curMonth;
        }
        if (curDate.length == 1) {
            curDate = "0" + curDate;
        }
        if (curHour.length == 1) {
            curHour = "0" + curHour;
        }
        if (curMinute.length == 1) {
            curMinute = "0" + curMinute;
        }
        if (curSecond.length == 1) {
            curSecond = "0" + curSecond;
        }
        var fileName = curYear + curMonth + curDate + curHour + curMinute + curSecond + ".xls";
        return fileName;
    }
    function doFileExport(inName, inStr) {
        var xlsWin = null;
        if (!!document.all("glbHideFrm")) {
            xlsWin = glbHideFrm;
        } else {
            var width = 1; var height = 1;
            var openPara = "left=" + (window.screen.width / 2 + width / 2) + ",top=" + (window.screen.height + height / 2) +
                 ",scrollbars=no,width=" + width + ",height=" + height;
            xlsWin = window.open("", "_blank", openPara);
        }
        xlsWin.document.write(inStr);
        xlsWin.document.close();
        xlsWin.document.execCommand('Saveas', true, inName);
        xlsWin.close();
    }  
</script>