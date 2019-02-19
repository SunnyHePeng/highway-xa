var config = {
	host: 'http://'+window.location.host,
	url: {
		'getCorpus': '/corpus',
		'save': '/manage/agent_info',
		'upload':'/manage/file/upload',
	},
	editor: '<form class="note-form rich-text">'
			//+'	<input class="title mousetrap" name="title" id="title" type="text" placeholder="请输入标题" value="">'
			+'	<div id="editor" class="editor-box">'
			+'		<ul class="toolbar">'
			+'			<li><button type="button" class="iconfont-18 icon-B1" data-title="粗体" data-type="bold"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-xieti4" data-title="斜体" data-type="italic"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-xiahuaxian-copy" data-title="文字下划线" data-type="underline"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-shanchuxian" data-title="删除线" data-type="strikeThrough"></button></li>'
			+'			<li class="menu">'
			+'				<button type="button" class="iconfont-14 icon-h" data-title="标题一" data-type="H1"></button>'
			+'				<ul class="submenu">'
			+'					<li><button type="button" class="iconfont-14 icon-h" data-toggle="tooltip" data-title="标题一" data-type="H1"></button></li>'
			+'					<li><button type="button" class="iconfont-14 icon-h2" data-toggle="tooltip" data-title="标题二" data-type="H2"></button></li>'
			+'					<li><button type="button" class="iconfont-14 icon-h3" data-toggle="tooltip" data-title="标题三" data-type="H3"></button></li>'
			+'					<li><button type="button" class="iconfont-14 icon-h4" data-toggle="tooltip" data-title="标题四" data-type="H4"></button></li>'
			+'				</ul>'
			+'			</li>'
			+'			<li class="menu">'
			+'				<button type="button" class="iconfont-18 icon-icxiangmufuhaodaishuzi24px" data-title="有序列表" data-type="insertOrderedList"></button>'
			+'				<ul class="submenu">'
			+'					<li><button type="button" class="iconfont-18 icon-icxiangmufuhaodaishuzi24px" data-title="有序列表" data-type="insertOrderedList"></button></li>'
			+'					<li><button type="button" class="iconfont-18 icon-wuxuliebiao" data-title="无序列表" data-type="insertUnorderedList"></button></li>'
			+'				</ul>'
			+'			</li>'
			+'			<li class="menu">'
			+'				<button type="button" class="iconfont-18 icon-juzuo1" data-title="居左"></button>'
			+'				<ul class="submenu">'
			+'					<li><button type="button" class="iconfont-18 icon-juzuo1" data-title="居左" data-type="justifyLeft"></button></li>'
			+'					<li><button type="button" class="iconfont-18 icon-juzhong4" data-title="居中" data-type="justifyCenter"></button></li>'
			+'					<li><button type="button" class="iconfont-18 icon-juyou1" data-title="居右" data-type="justifyRight"></button></li>'
			+'				</ul>'
			+'			</li>'
			+'			<li><button type="button" class="iconfont-18 icon-yinyong" data-title="引用" data-type="blockquote"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-fengexian" data-title="分割线" data-type="insertHorizontalRule"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-module-link" data-title="插入链接" data-type="createLink"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-image" data-title="插入图片" data-type="insertImage"></button></li>'
			+'			<li><button type="button" class="iconfont-18 icon-video" data-title="插入视频" data-type="insertVideo"></button></li>'
			+'			<li class="menu">'
			+'				<button type="button" class="iconfont-18 icon-chexiao" data-title="撤销" data-type="undo"></button>'
			+'				<ul class="submenu">'
			+'					<li><button type="button" class="iconfont-18 icon-chexiao" data-title="撤销" data-type="undo"></button></li>'
			+'					<li><button type="button" class="iconfont-18 icon-iconchexiao" data-title="重做" data-type="redo"></button></li>'
			+'				</ul>'
			+'			</li>'
			+'			<li class="right"><button type="button" class="iconfont-18 icon-fabu2" data-title="预览" data-type="publish" id="publish"></button></li> ' 
			+'			<!-- <li class="right"><button type="button" class="iconfont-18 icon-fangda" data-title="切换到写作模式"></button></li> --> '
			+'			<li class="right"><button type="button" class="iconfont-18 icon-baocun" data-title="保存" data-type="save" id="save"></button> </li>' 
			//+'			<span class="saving-notice"></span>'
			+'		</ul>'
			+'		<article id="editor-area" class="editor-area mousetrap" contenteditable="true">'
			+'		</article>'
			+'	    <div id="show-img-upload"></div>'
			+'	</div>'
			+'</form>'
			+'<div class="modal-div">'
			+'	<div id="link-modal" class="modal link-modal hide"> '
			+'		<div class="modal-header"> <h3>插入链接</h3> </div> '
			+'		<div class="modal-body">' 
			+'			<div class="input-div"> '
			+'				<span class="icon"><i class="iconfont-14 icon-module-link"></i></span> '
			+'				<input class="input" name="href" placeholder="链接地址" type="text">' 
			+'			</div> '
			+'			<div class="input-div">' 
			+'				<span class="icon"><i class="iconfont-14 icon-a"></i></span>' 
			+'				<input class="input" name="text" placeholder="链接文本" type="text"> '
			+'			</div> '
			+'			<span class="message"></span> '
			+'			<button type="button" data-id="link-modal" class="btn ok">确认</button>' 
			+'			<button type="button" data-id="link-modal" class="btn cancel">取消</button>' 
			+'		</div>'
			+'	</div>'
			+'	<div id="video-modal" class="modal video-modal hide">' 
			+'		<div class="modal-header"> '
			+'			<h3>插入视频</h3>' 
			+'			<span>目前只支持<a href="http://www.youku.com/" target="_blank">优酷</a>、<a href="http://www.tudou.com/" target="_blank">土豆</a>、<a href="http://v.qq.com/" target="_blank">腾讯视频的flash地址</a>。</span>' 
			+'		</div> '
			+'		<div class="modal-body">' 
			+'			<div class="input-div">' 
			+'				<span class="icon"><i class="iconfont-14 icon-module-link"></i></span>' 
			+'				<input class="input" name="url" placeholder="请输入视频链接" type="text">' 
			+'			</div>' 
			+'			<span class="message"></span>' 
			+'			<button type="button" data-id="video-modal" class="btn ok">确认</button>' 
			+'			<button type="button" data-id="video-modal" class="btn cancel">取消</button>' 
			+'		</div>'
			+'	</div>'
			+'	<div id="image-modal" class="modal image-modal hide">' 
			+'		<div class="modal-header">' 
			+'			<h3>插入图片</h3>' 
			+'		</div>' 
			+'		<div class="modal-body">' 
			+'			<div class="tab-content">' 
			+'				<div class="clearfix hide active" id="image-upload">' 
			+'					<div class="upload-picture">' 
			+'						<label class="upload-image"><i class="fa picture-o"></i> 点击上传（可多张）</label>' 
			+'						<input id="upload-image" class="btn-upload" type="file" name="file" multiple="multiple">' 
			+'					</div>' 
			+'					<a class="switch" data-for="image-external">或选择网络图片</a>' 
			+'					<p>'
			+'						<a href="###" target="_blank"><i class="iconfont-13 icon-wen"></i> 图片私密性</a>'
			+'					</p>' 
			+'					<button type="button" data-id="image-modal" class="btn cancel">取消</button>' 
			+'				</div>' 
			+'				<div class="clearfix hide" id="image-external">' 
			+'					<div class="input-div">'
			+'						<span class="message"></span>'  
			+'					</div>'
			+'					<div class="input-div">' 
			+'						<span class="icon"><i class="iconfont-14 icon-module-link"></i></span>' 
			+'						<input class="input" name="image" placeholder="输入网络图片链接" type="text">'
			+'					</div>' 
			+'					<a class="switch" data-for="image-upload">或上传本地图片</a>'
			+'					<p>'
			+'						<a href="###" target="_blank"><i class="iconfont-13 icon-wen"></i> 图片私密性</a>'
			+'					</p>' 
			+'					<button type="button" data-id="image-modal" class="btn ok">确认</button>' 
			+'					<button type="button" data-id="image-modal" class="btn cancel">取消</button>' 
			+'				</div>' 
			+'				<span class="message-text" style="display: none">上传中...</span>' 
			+'				<img src="/lib/editor/loading.gif" class="lodaer loader-tiny hide">' 
			+'			</div>'
			+'		</div>'
			+'	</div>'
			+'</div>',
	empty_note: '<div class="empty-note"><span>空文章</span></div>',
	doAjax: function (type, url, data, success, error){
		$.ajax({
			url: url,
	        type: type,
	        data: data,
	        dataType: 'json',
	        success: function(msg){
	        	if(typeof success == "function"){
	        		success(msg);
	        	}
	        },
	        error: function(){
	        	if(typeof error == "function"){
	        		error();
	        	}
	        }
		});
	}
};
/******************************************页面其他操作JS代码************************************************/
var page = {
	init: function(){
		/**slide操作**/
		$('.container').on('click', '.slide-toggle', function(){
			$(this).parent().find('.slide-div').slideToggle();
		});

		/**文集操作**/
		$('.new-corpus-form .cancel').on('click', function(){
			$(this).parent().parent().slideToggle();
			$('.new-corpus-form .corpus-input').val('');
		});

		$('.new-corpus-form .submit').on('click', function(){
			var name = $('.new-corpus-form .corpus-input').val().replace(/(\r\n|\n|\r)/gm,"");
			var str = name.replace(/ /ig,'');
			if(!str){
				page.showDialog('请填写文集名称');
				return false;
			}

			var success = function(msg){
		    	if(msg.status){
		    		//隐藏添加表单
		    		$('.new-corpus-form .cancel').trigger('click');
		    		//添加成功 显示文集并选中
		    		page.addCorpus(name, msg.data);
		    	}else{
		    		page.showDialog(msg.info);
		    	}
		    	$('.new-corpus-form .corpus-input').val('');
		    }

		    var error = function(){
		        page.showDialog('执行失败，请重新操作');
		    }
			config.doAjax('POST', $('.create-corpus-form').attr('data-url'), {'title': name}, success, error);
		});

		$('.corpus').on('click', '.corpus-name', function(){
			$('.corpus').find('li').removeClass('active');
			$(this).parent().addClass('active');
			page.setArticleHtml($(this).parent().attr('data-key'));
		});

		$('.corpus').on('click', '.rename-corpus', function(){
			var $obj = $('.modal-div.rename');
			var name = $(this).attr('data-name');
			var cid = $(this).attr('data-id');
			$obj.find('#rename').val(name);
			$obj.find('#cid').val(cid);
			$(this).parent().parent().slideToggle();

			var ok = function(new_name){
				var success = function(msg){
					if(msg.status){
			    		$('.corpus #corpus_'+cid).find('.corpus-name span').html(new_name);
			    		page.RenameOrMoveClose($obj);
			    	}else{
			    		editor.showTopMessage(msg.info);
			    	}
				};
				var error = function(){
					editor.showTopMessage('修改失败，请重新操作');
				}
				config.doAjax('PUT', config.url.getCorpus+'/'+cid, {'title': new_name}, success, error);
		    }

			page.showRenameOrMove($obj, ok);
		})

		$('.corpus').on('click', '.delete-corpus', function(){
			var cid = $(this).attr('data-id');
			var ok = function(){
				var success = function(msg){
					if(msg.status){
			    		$('.corpus #corpus_'+cid).remove();
			    		//修改文集信息 并 显示下一个文集的第一篇文章
			    		//var corpus = JSON.parse(localStorage.corpus);
			    		//console.info(typeof corpus);
			    		var $obj = $('.corpus').find('li.one-corpus').first();
			    		$obj.addClass('active');
			    		page.setArticleHtml($obj.attr('data-key'));
			    	}else{
			    		editor.showTopMessage(msg.info);
			    	}
				};
				var error = function(){
					editor.showTopMessage('删除失败，请重新操作');
				}
				config.doAjax('DELETE', config.url.getCorpus+'/'+cid, '', success, error);
		    }
		    $(this).parent().parent().slideToggle();
		    page.showDialog('确定删除吗？如果该文集下有文章将一起删除！！！', ok);
		})

		/*是否隐藏修改引起的对话框*/
		$('.aside').on('click', '.modal-div', function(event){
			if ( event.target.className === "modal-div" ) {
				page.RenameOrMoveClose($('.modal-div.rename'));
			}
		});

		/*文章列表操作*/
		$('#new-note').on('click', function(){
			var data = {
					'id': '',
					'title': '无标题文章',
					'content': '',
				}
			//删除之前编辑文章的active
			$('.notes').find('li.one-note').removeClass('active');
			var html = page.makeArticleHtml(data, 1);
			$('#notes-list .notes').prepend(html);

			//显示编辑器内容
			page.setEditorData('无标题文章', '');
		});
	},
	/*文集添加成功 显示新增文集并选中*/
	addCorpus: function(title, id){
		$('.corpus').find('li').removeClass('active');
		$('.corpus').prepend(page.makeCorpusHtml(id, title, 1));
		$('#notes-list .notes').html('');
		$('.main').html('').html(config.empty_note);
		//修改本地corpus
		var data = {'id': id, 'title': title, 'articles': []};
		page.changeCorpus('add', id, data);
	},
	/*显示对话框*/
	showDialog: function(info, ok, cancel){
		var time = (new Date).getTime();
		var html = '<div id="'+time+'">'
				 +		'<div class="message">'
				 + 			info
				 + 		'</div>'
				 + 		'<div class="button">'
				 +	 		'<button class="btn cancel">取消</button>'
				 +			'<button class="btn ok">确认</button>'
				 + 		'</div>'
				 + '</div>';
		$('.show-dialog').html(html).slideToggle();

	    var close = function(){
	    	$('.show-dialog').slideUp().find('#'+time).remove();
	    }

	    $('.show-dialog').find('.ok').unbind('click').on('click', function(){
	    	if(typeof ok == "function"){
		        ok();
		    }
		    close();
	    });

		$('.show-dialog').find('.cancel').unbind('click').on('click', function(){
			if(typeof cancel == "function"){
		        cancel();
		    }
		    close();
		});	
	},
	showRenameOrMove: function($obj, ok, cancel){
		$('.layer-shade').css('display','block');
		
		$obj.slideToggle();

		$obj.find('.ok').unbind('click').on('click' , function(){
	    	if(typeof ok == "function"){
		        ok($obj.find('#rename').val());
		    }
	    });

		$obj.find('.cancel').unbind('click').on('click', function(){
			if(typeof cancel == "function"){
		        cancel();
		    }
		    page.RenameOrMoveClose($obj);
		});
	},
	RenameOrMoveClose: function($obj){
		$obj.css('display','none');
		$obj.find('input').val('');
	    $('.layer-shade').css('display','none');
	},
	/*获取文集 并存储在本地*/
	getCorpus: function (){
		var url = config.host + config.url.getCorpus;
		var success = function(msg){
			if(msg.status){
				localStorage.corpus = JSON.stringify(msg.info);
				//给编辑页面赋值
				page.setDataForEditer();
			}
		};
		config.doAjax('GET', url, '', success);
	},
	/*给编辑页面赋值*/
	setDataForEditer: function (){
		var corpus_html = '', new_corpus = {}, key = '', j = 0;
		var data = JSON.parse(localStorage.corpus);
		for(var i in data){
			new_corpus[data[i]['id']] = data[i];
			if(j == 0){
				key = data[i]['id'];
				corpus_html += page.makeCorpusHtml(data[i]['id'], data[i]['title'], 1);
			}else{
				corpus_html += page.makeCorpusHtml(data[i]['id'], data[i]['title']);
			}
			j++;
		}
		localStorage.corpus = JSON.stringify(new_corpus);
		$('.corpus').prepend(corpus_html);
		//文章列表赋值
		page.setArticleHtml(key);	
	},
	/*文章列表赋值*/
	setArticleHtml: function (key){
		var data = JSON.parse(localStorage.corpus);
		var article_html = '', title='', content='';
		//生成文章列表html
		if(data[key]['articles'].length > 0){
			var j=0;
			for(var i in data[key]['articles']){
				if(j == 0){
					article_html += page.makeArticleHtml(data[key]['articles'][i], 1);
				}else{
					article_html += page.makeArticleHtml(data[key]['articles'][i]);
				}
				j++;
			}
			title = data[key]['articles'][0]['title'];
			content = data[key]['articles'][0]['content'];
		}

		$('#notes-list .notes').html(article_html);
		page.setEditorData(title, content);
	},
	showEditer: function (){
		$('.pre-show').remove();
	},
	makeCorpusHtml: function(id, title, is_active){
		var html = '<li class="one-corpus ';
		if(is_active){
			html += 'active';
		} 
		html +='" data-key="'+ id +'" id="corpus_'+ id +'">' 
		 	 + '	<a href="javascript:;" class="corpus-name" data-id="'+ id +'">'
			 + '		<span>'+ title +'</span>'
			 + '	</a>' 
			 + '	<a href="javascript:;" class="edit-corpus slide-toggle"><i class="iconfont-14 icon-shezhi"></i></a>' 
			 + '	<ul class="dropdown-menu hide slide-div edit-corpus-item arrow-top">' 
			 + '		<li>' 
			 + '			<a href="javascript:;" class="rename-corpus" data-name="'+ title +'" data-id="'+ id +'"><i class="iconfont-13 icon-xiugai"></i>修改文集名</a>' 
			 + '		</li>'
			 + '		<li>' 
			 + '			<a href="javascript:;" class="delete-corpus" data-id="'+ id +'"><i class="iconfont-13 icon-shanchu"></i>删除文集</a>'
			 + '		</li>'
			 + '	</ul>'
			 + '</li>';
		return html;
	},
	makeArticleHtml: function(data, is_active){
		var html = '<li class="one-note ';
		if(is_active){
			html += 'active';
		}
		html += '" data-id="'+ data['id'] +'">' 
			  +	'	<i class="icon-note iconfont-20 icon-article"></i>' 
			  +	'	<p class="description">'+data['content']+'</p>' 
			  +	'	<!-- <p class="words-count">字数: 142</p> -->' 
			  +	'	<a href="javascript:;" class="note-link title">'+data['title']+'</a>'
			  +	'	<a href="javascript:;" class="share-note slide-toggle"><i class="iconfont-14 icon-shezhi"></i></a>' 
			  +	'	<ul class="dropdown-menu arrow-top slide-div">'  
			  +	'		<li><a href="javascript:;"><i class="fa fa-lock"></i>删除文章</a></li>'
			  +	'		<li><a href="javascript:;"><i class="fa fa-lock"></i>移动文章</a></li>'
			  +	'	</ul>' 
			  +'</li>';
		return html;
	},
	/*修改本地文集信息*/
	changeCorpus: function(type, key, data){
		var corpus = JSON.parse(localStorage.corpus);
		if(type == 'add'){
			corpus[key] = data;
		}else{

		}
		localStorage.corpus = JSON.stringify(corpus);
	},
	/*编辑器赋值*/
	setEditorData: function(title, content){
		if(title || content){
			$('.main').html(config.editor);
			$('.main .note-form #title').val(title);
			$('.main .note-form #editor-area').html(content);
		}else{
			$('.main').html('').html(config.empty_note);
		}
	}
};
/******************************************上传图片************************************************/
var upload = {
	fileInput: document.getElementById("upload-image"),//$('.main #upload-image').get(0),				//html file控件.get(0)
	url: config.host + config.url.upload,						//ajax地址
	fileFilter: [],					//过滤后的文件数组
	filter: function(files) {		//选择文件组的过滤方法
		//检测文件类型  文件大小
		var arrFiles = [];
		var length = files.length;
		var file;
		for (var i = 0; i < length; i++) {
			file = files[i];
			if (file.type.indexOf("image") == 0) {
				if (file.size >= 512000) {
					page.showDialog('您这张"'+ file.name +'"图片大小过大，应小于500k');	
				} else {
					arrFiles.push(file);	
				}			
			} else {
				page.showDialog('文件"' + file.name + '"不是图片。');	
			}
		}
		return arrFiles;	
	},
	onSelect: function(files) {			//文件选择后
		var html = '', i = 0;
		var showUploadImage = function(){
			if(files[i]){
				var reader = new FileReader()
				reader.onload = function(e) {
					console.info(e);
					html +='<div class="upload_loading" id="upload_list_'+ i +'">'
						  +'	<div class="img-show"><img src="'+e.target.result+'"></div>'
						  +'	<div class="progress"><span class="unfill"><span class="fill-stripes"></span></span></div>'
						  +'	<p class="ok">正在上传...</p><p data-index="'+i+'" class="cancel">取消</p>'
						  +'</div>';
					i++;
					showUploadImage();
				}
				reader.readAsDataURL(files[i]);
			}else{
				if(html){
					$("#show-img-upload").html(html);
					//删除方法
					$(".cancel").on('click', '#show-img-upload', function() {
						upload.deleteFile(files[parseInt($(this).attr("data-index"))]);
						return false;	
					});
					$('.main .modal-div').trigger('click');
				}
			}
		}
		
		showUploadImage();
	},
	onDelete: function(file) {						//文件删除后
		$('#upload_list_'+file.index).fadeOut().remove();
	},
	onProgress: function(file, loaded, total) {		//文件上传进度

	},
	onSuccess: function(file, response) {			//文件上传成功时
		console.info(response);
		response = eval('('+response+')');
		var html = '<div class="image">'
			 	 + '<img src="/uploads/'+response.info+'">'
			 	 + '</div>';
		window.getSelection().addRange(editor.lastSelection);
		document.execCommand('insertHTML', false, html);
	},
	onFailure: function(file) {						//文件上传失败时,
		$('#upload_list_'+file.index+ ' .ok').html("上传失败");
	},
	onComplete: function() {},						//文件全部上传完毕时
	
	/**************内置方法**************/
	//获取选择文件，file控件或拖放
	getFiles: function(e) {
		// 获取文件列表对象
		var files = e.target.files || e.dataTransfer.files;
		//继续添加文件
		this.fileFilter = this.fileFilter.concat(this.filter(files));
		this.dealFiles();
		return this;
	},
	//选中文件的处理与回调
	dealFiles: function() {
		for (var i = 0, file; file = this.fileFilter[i]; i++) {
			//增加唯一索引值
			file.index = i;
		}
		//执行选择回调
		this.onSelect(this.fileFilter);
		//上传文件
		this.uploadFile();
		return this;
	},
	//删除对应的文件
	deleteFile: function(fileDelete) {
		var arrFile = [];
		var length = this.fileFilter.length;
		for (var i = 0; i<length; i++) {
			if (this.fileFilter[i] != fileDelete) {
				arrFile.push(this.fileFilter[i]);
			} else {
				this.onDelete(fileDelete);	
			}
		}
		this.fileFilter = arrFile;
		return this;
	},
	//文件上传
	uploadFile: function() {
		var self = this;	
		if (location.host.indexOf("sitepointstatic") >= 0) {
			//非站点服务器上运行
			return;	
		}
		var length = this.fileFilter.length;
		for (var i = 0; i<length; i++) {
			file = this.fileFilter[i];
			console.info(file);
			var xhr = new XMLHttpRequest();
			if (xhr.upload) {
				var data = new FormData();
				// 上传中
				xhr.upload.addEventListener("progress", function(e) {
					self.onProgress(file, e.loaded, e.total);
				}, false);
	
				// 文件上传成功或是失败
				xhr.onreadystatechange = function(e) {
					if (xhr.readyState == 4) {
						if (xhr.status == 200) {
							self.onSuccess(file, xhr.responseText);
							self.deleteFile(file);
							if (!self.fileFilter.length) {
								//全部完毕
								self.onComplete();	
							}
						} else {
							self.onFailure(file, xhr.responseText);		
						}
					}
				};
	
				// 开始上传
				xhr.open("POST", self.url, true);
				xhr.setRequestHeader("X_FILENAME", encodeURIComponent(file.name));
				data.append('file', file);
				data.append('type', 'images');
				xhr.send(data);
			}
		}	
	},
	init: function() {
		var self = this;
		//文件选择控件选择
		$('.main').find('#upload-image').unbind('change').on('change', function(e){
			self.getFiles(e);
		});
		/*
		if (this.fileInput) {
			this.fileInput.addEventListener("change", function(e) { self.getFiles(e); }, false);	
		}*/
	}
};
/******************************************编辑器JS代码************************************************/
//var composing, nodeNames, lastSelection, imageType;
var editor = {
	composing: false,
	nodeNames: {},
	lastSelection: {},
	imageType: 'image-external',
	init: function(){
		/*监听事件*/
		editor.listenEvent();
		
		$('.main').on('click', '.toolbar li button', function(){
			var type = $(this).attr('data-type');
			editor.formatText(type);
		})

		/*工具栏名称提示*/
		$('.main').on('mouseover', '.toolbar li button', function(){
			var html = '<div class="tooltip">'
					 + '	<div class="tooltip-arrow"></div>'
					 + '	<div class="tooltip-inner">'+ $(this).attr('data-title') +'</div>'
					 + '</div>';
			$(this).parent().append(html);
		}).on('mouseout', function(){
			$(this).parent().find('.tooltip').remove();
		});

		/*点击工具栏后执行格式*/
		$('.main').on('click', '.modal button', function(){
			var id = $(this).attr('data-id');
			var res = true;
			if($(this).hasClass('ok')){
				res = editor.onLinkClick(id, 1);
			}
			if(res){
				$('#'+ id +' input').val('');
				$('#'+ id +' .message').html('').css('display','none');
				$('#'+ id).toggle();
				$('#'+ id).parent().toggle();
			}
		});

		/*是否隐藏工具栏引起的提示*/
		$('.main').on('click', '.modal-div', function(event){
			if ( event.target.className === "modal-div" ) {
				$(this).css('display', 'none');
				$('.modal').css('display', 'none');
				$('.modal input').val('');
				$('.modal .message').html('').css('display','none');
				window.getSelection().removeAllRanges()
				window.getSelection().addRange(editor.lastSelection);
			}
		});
	},
	/*监听鼠标事件*/
	listenEvent: function (){
		document.onmouseup = editor.checkText;

		document.addEventListener('compositionstart', editor.onCompositionStart);
		document.addEventListener('compositionend', editor.onCompositionEnd);
	},
	/*检测是否选择文档*/
	checkText: function (){
		var selection = window.getSelection();
		console.info(selection);
		//起始点不在一个位置 文字被选择
		if(selection.isCollapsed === false){
			var currentNodeList = editor.findNodes( selection.focusNode );
			if (editor.hasNode(currentNodeList, "ARTICLE")){
				editor.composing = selection.isCollapsed;
			}
		}
	},
	/*查找节点*/
	findNodes: function (element){
		editor.nodeNames = {};
		while (element.parentNode) {
			editor.nodeNames[element.nodeName] = true;
			element = element.parentNode;

			if(element.nodeName === 'A'){
				editor.nodeNames.url = element.href;
			}
		}
		return editor.nodeNames;
	},
	/*判断是否含有某个节点*/
	hasNode: function (nodeList, name){
		return nodeList[name];
	},
	/*根据选择的工具格式化文档*/
	formatText: function (type){
		var formatblock = ['H1','H2','H3','H4','blockquote'];
		switch(type){
			case 'createLink':
			  	editor.onLinkClick('link-modal');
			  	break;
			case 'insertImage':
			  	editor.onLinkClick('image-modal');
			  	break;
			case 'insertVideo':
			  	editor.onLinkClick('video-modal');
			  	break;
			case 'save': 
				editor.doSave(0);
				break;
			case 'publish': 
				editor.doSave(1);
				break;	
			default:
				if(editor.composing === false){
					if($.inArray(type, formatblock) >= 0){
						editor.onBlockClick(type);
					}else{
						document.execCommand(type, false);
					}
				}
		}
	},
	/*格式化块状文档*/
	onBlockClick: function (type){
		if(editor.hasNode(editor.nodeNames, type.toUpperCase())){
			document.execCommand('formatblock', false, 'p');
			document.execCommand('outdent');
		}else{
			document.execCommand('formatblock', false, type);
		}
	},
	/*点击链接 视频 图片操作*/
	onLinkClick: function (id, is_ok){
		/*var linkURL = prompt('Enter a URL:', 'http://');
	    document.execCommand('createlink', false, linkURL);*/
		if(id == 'link-modal'){
			var res = editor.doLink(is_ok);
		}else if(id == 'video-modal'){
			var res = editor.doVideo(is_ok);
		}else{
			var res = editor.doImage(is_ok);
		}
		if(!is_ok && res){
			$('#'+id).toggle();
			$('#'+ id).parent().toggle();
		}else{
			return res;
		}
	},
	/*链接操作*/
	doLink: function (is_ok){
		if(is_ok){
			document.execCommand('unlink', false);
			var href = $('#link-modal input[name="href"]').val();
			var text = $('#link-modal input[name="text"]').val();
			if(href == '' && text == ''){
				editor.showModalMessage('link-modal', '请输入链接地址');
				return false;
			}
			if(href !== ''){
				if(!href.match("^(http|https)://")){
					href = "http://" + href;	
				}
				//创建最后选取的区域
				window.getSelection().addRange(editor.lastSelection);
				document.execCommand('createlink', false, href);
				var selection = document.getSelection();
				selection.anchorNode.parentElement.target = '_blank';
			}
		}else{
			var selection = window.getSelection();
			if(selection.anchorNode == null){
				editor.showTopMessage('请选择插入链接的区域');   
				return false;
			}
			if(selection.isCollapsed == false){
				var text = selection.toString();
				editor.nodeNames = editor.findNodes(selection.focusNode);
				if(editor.hasNode(editor.nodeNames, "A")) {
					$('#link-modal input[name="href"]').val(editor.nodeNames.url);
				}
				$('#link-modal input[name="text"]').val(text);
			}
			editor.lastSelection = selection.getRangeAt(0);
		}
		return true;
	},
	/*图片操作*/
	doImage: function (is_ok){
		editor.imageType = 'image-upload';
		$('#image-modal a.switch').on('click', function(){
			$(this).parent().removeClass('active');
			editor.imageType = $(this).attr('data-for');
			$('#image-modal #'+editor.imageType).addClass('active');
			if(editor.imageType == 'image-upload'){
				upload.init();
			}
		});
		if(is_ok){
			if(editor.imageType == 'image-external'){
				var href = $('#image-modal input[name="image"]').val();
				if(href == ''){
					editor.showModalMessage('image-modal', '请输入图片地址');
					return false;
				}
				if(!href.match("^(http|https)://")){
					editor.showModalMessage('image-modal', '图片地址不支持，请检查地址是否正确');
					return false;	
				}

				var html = '<div class="image">'
					 + '<img src="'+href+'">'
					 + '</div>';
				window.getSelection().addRange(editor.lastSelection);
				document.execCommand('insertHTML', false, html);
			}
		}else{
			var selection = window.getSelection();
			if(selection.anchorNode == null){
				editor.showTopMessage('请选择插入图片的区域');   
				return false;
			}
			editor.nodeNames = editor.findNodes(selection.focusNode);
			if(editor.hasNode(editor.nodeNames, "#text")) {
				editor.showTopMessage('不能在行内插入图片，请换行选择新区域');      
				return false;
			}
			editor.lastSelection = window.getSelection().getRangeAt(0);
			if(editor.imageType == 'image-upload'){
				upload.init();
			}
		}
		return true;
	},
	/*视频操作*/
	doVideo: function (is_ok){
		if(is_ok){
			var href = $('#video-modal input[name="url"]').val();
			if(href == ''){
				editor.showModalMessage('video-modal', '请输入视频地址');
				return false;
			}
			if(!href.match("^(http|https)://")){
				editor.showModalMessage('video-modal', '视频来源不支持，请输入支持的视频地址！');
				return false;	
			}
			var html = '<div class="video">'
					 + '<object id="youku-player" width="100%" height="100%" data="'+href+'" type="application/x-shockwave-flash">'
					 + '	<param value="true" name="allowFullScreen">'
					 + '	<param value="always" name="allowScriptAccess">'
					 + '	<param value="'+href+'" name="movie">'
					 + '	<param value="imglogo=&paid=0&partnerId=cc8e4cd18b184718&isAutoPlay=false" name="flashvars">'
					 + '</object>'
					 + '</div>';
			//创建最后选取的区域https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=l0395d6c7e0&auto=0
			window.getSelection().addRange(editor.lastSelection);
			document.execCommand('insertHTML', false, html);
		}else{
			var selection = window.getSelection();
			if(selection.anchorNode == null){
				editor.showTopMessage('请选择插入视频的区域');
				return false;
			}
			editor.nodeNames = editor.findNodes(selection.focusNode);
			if(editor.hasNode(editor.nodeNames, "#text")) {
				editor.showTopMessage('不能在行内插入视频，请换行选择新区域');
				return false;
			}
			editor.lastSelection = window.getSelection().getRangeAt(0);
		}
		return true;
	},
	/*保存文档内容*/
	doSave: function (is_publish){
		/*var title = $('.note-form #title').val().replace(/(\r\n|\n|\r)/gm,"");
		if(!title){
			editor.showTopMessage('请输入标题')
			return false;
		}	*/
		var content = $('.note-form article#editor-area').html();
		//去除HTML tag,行尾空白,多余空行,去掉空格 
		str = content.replace(/[ | ]*\n/g,'\n')
					.replace(/\n[\s| | ]*\r/g,'\n')
					.replace(/ /ig,''); 
	    if(str.length <= 0){
	    	editor.showTopMessage('您的文章没有任何内容，写点什么吧...')
			return false;
	    }
	    //调用后台代码保存文章
	    var data = {'content': content};//title='+title+'&content='+content;
	    if(is_publish){
	    	data.publish = 1 ;
	    	var id = 'publish';
	    }else{
	    	var id = 'save';
	    }

	    var success = function(msg){
	    	if(msg.status){
	    		editor.showTopMessage(msg.info);
	    		//$('.saving-notice').html(msg.info);
	    		if(is_publish){
	    			window.location.href = config.host+config.url.save+'?view=1';
	    		}
	    	}else{
	    		editor.showTopMessage(msg.info);
	    	}
	    }

	    var error = function(){
	    	editor.showTopMessage('执行失败，请重新操作');
	    }
	    config.doAjax('POST', config.url.save, data, success, error);  
	},
	onCompositionStart: function (event){
		editor.composing = true;
	},
	onCompositionEnd: function (event){
		editor.composing = false;
	},
	/*显示链接 视频 图片提示信息*/
	showModalMessage: function (id, info){
		$('#'+id+' .message').html(info).css('display','inline-block');
	},
	/*显示顶部提示信息*/
	showTopMessage: function (info){
		var time = new Date().getTime();
		var html = '<li id="'+time+'">'+ info +'<i class="iconfont-14 icon-guanbi"></i></li>';
		$('.show-message ul').append(html).find('li#'+time).fadeTo(5000,0);//html('').html(info).fadeTo(500,0.8).fadeTo(3500,0);
		function hide(){
			$('.show-message').find('li#'+time).remove();
		}
		$('.show-message ul li i').on('click', function(){
			$(this).parent().remove();
		});
		setTimeout(hide,5000);
	}
};

(function(){
	var content = $('.main').html();
	$('.main').html(config.editor);
	$('.main #editor-area').html(content);
	//设置栏目高度 对话框left值
	$('.show-dialog').css('left', ($(window).width()-300)/2 + 'px');

	$('#editor-area').css('height', $('.main').attr('data-height'));
	window.onresize = function(){
		$('.show-dialog').css('left', ($(window).width()-300)/2 + 'px');
	}

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	//page.init();
	editor.init();
})();


