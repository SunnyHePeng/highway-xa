function uploadFile(browse_button, url, start_button, file_list, file_show, param, name){
    var host = 'http://'+window.location.host;
    var img_show = '/uploads/';

    var uploader = new plupload.Uploader({
        runtimes: 'html5,silverlight,html4',
        browse_button: browse_button, // you can pass in id...
        url: url,
        max_file_size: '1mb',//限制为1MB
        chunk_size: '1mb',
        unique_names: true,
        auto_start: true,
        multi_selection: false,
        filters: {
            max_file_size: '1mb',
            mime_types: [
                {title: "files", extensions : "jpg,gif,png,jpeg,doc,docx,txt,xls,xlsx"}
            ]
        },
        flash_swf_url: '/lib/plupload-2.1.9/js/Moxie.swf',
        silverlight_xap_url: '/lib/plupload-2.1.9/js/Moxie.xap',
        // PreInit events, bound before the internal events
        preinit: {
            UploadFile: function(up, file) {
                up.setOption('multipart_params', param);
            }
        },
        // Post init events, bound after the internal events
        init: {
            PostInit: function() {
                document.getElementById(start_button).onclick = function() {
                    uploader.start();
                    return false;
                };
            },
            BeforeUpload: function(up, file) {
                console.info(up);
                console.info(file);
            },
            UploadProgress: function(up, file) {
                $('#file-'+file.id+' .progress').css('width',file.percent + '%');//控制进度条
            },
            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    var file_name = file.name; //文件名
                    //构造html来更新UI
                    var html = '<li id="file-' + file.id +'"><p class="file-name">' + file_name + '</p><p class="progress"></p></li>';
                    $(html).appendTo('#'+file_list);
                });
            },
            FileUploaded: function(up, file, info) {
                info = $.parseJSON(info.response);
                var src = host + img_show + info.info;
                if(file_show == 'imgShow'){
                    var html = '<li>'
                        + '	<img src="'+src+'">'
                        + '	<input type="hidden" name="'+name+'" value="'+info.info+'">'
                        + '</li>';
                }else{
                    if(info.info==''){
                        layer.alert('附件上传失败');
                    }else{
                        var html = '<li>'
                            + '	<p>'+info.info+'</p>'
                            + '	<input type="hidden" name="'+name+'" value="'+info.info+'">'
                            + '</li>';
                        layer.alert('附件上传成功');
                    }

                }

                $('#file-'+file.id).remove();
                $('#'+file_show).html('').html(html);
            },
            UploadComplete: function(up, files) {
                //$("a.fancyBox").fancybox();//图片预览-->动态渲染http://www.xuexi.com/manage/article_tag
            },
            Error: function(up, args) {
                if(args.code == '-600'){
                    common.alert('文件大小超过1M');
                    return false;
                }
                if(args.code == '-601'){
                    common.alert('文件格式不对');
                    return false;
                }
                common.alert(args.message);
            }
        }
    });
    uploader.init();
}