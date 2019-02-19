var img_show = '/uploads/';
//平台、设备和操作系统
var os = function() {
    var ua = navigator.userAgent,
        isWindowsPhone = /(?:Windows Phone)/.test(ua),
        isSymbian = /(?:SymbianOS)/.test(ua) || isWindowsPhone,
        isAndroid = /(?:Android)/.test(ua),
        isFireFox = /(?:Firefox)/.test(ua),
        isChrome = /(?:Chrome|CriOS)/.test(ua),
        isTablet = /(?:iPad|PlayBook)/.test(ua) || (isAndroid && !/(?:Mobile)/.test(ua)) || (isFireFox && /(?:Tablet)/.test(ua)),
        isPhone = /(?:iPhone)/.test(ua) && !isTablet,
        isPc = !isPhone && !isAndroid && !isSymbian;
    var plat = navigator.platform;
    return {
        isTablet: isTablet,
        isPhone: isPhone,
        isAndroid : isAndroid,
        isPc : isPc,
		isWin: plat.indexOf("Win") == 0,
		isMac: plat.indexOf("Mac") == 0,
		isX11: plat == "X11" || plat.indexOf("Linux") == 0
    };
}();

$(function(){
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  common.init();
});
var common = {
	init: function(){
		if (!os.isWin) {
            $('.change-module').each(function () {
            	if ($(this).attr('data-id') == 21) $(this).addClass("hidden-xs").addClass("hidden-sm");
            });
        }
		$('.change-module').on('click', function(){
			var id = $(this).attr('data-id');
			var is_new = $(this).attr('data-is-new');
			var url = $(this).parent().attr('data-url');
			$.ajax({
		      url: url,
		      type: 'POST',
		      data: {'id': id},
		      dataType: 'json',
		      success:function(msg){
		          if(msg.status){
		          	if(is_new == 1){
		          		if (os.isPc) window.open(msg.url); else window.location.href = msg.url;
		          	}else{
		          		window.location.href = msg.url;
		          	}
		          }else{
		            common.alert('您没有权限，请先联系管理员');
		          }
		      },
		      // error: function(){
		      //   common.alert('您没有权限，请先联系管理员');
		      // }
		    });
		});
		//this.showImage();
	},
	layer: {
		form: 'form_container',
		skin: 'add-layer',
		module: {'area':['500px','400px']},
		role: {'area':['650px','600px']},
		permission: {'area':['500px','500px'],'select2': {'key':'m_id', 'set_key':'m'}},
		project: {'area':['500px','400px'],'select2': false},
		section: {'area':['500px','500px'],'select2': false/*,'select2': {'key':'pro_id', 'set_key':'project_'}*/},
		mixplant: {'area':['500px','500px'],'select2': false},
		factory: {'area':['500px','500px'],'select2': {'key':'fac_id', 'set_key':'factory_'}},
		device_type: {'area':['500px','300px'],'select2': {'key':'cat_id', 'set_key':'category_'}},
		agent: {'area':['500px','400px']},
		admin: {'area':['500px','500px'],'select2': false},
	},
	doAjax: function(obj, type, data, index){
		var url = obj.attr('data-url');
		var is_reload = 1;
		if(data){		//修改排序，
			is_reload = 0;
		}else{
			var data = obj.serialize();
			var id = obj.find('#id').val();
			if(id){
				if(!type){
					type = 'PUT';
				}
				url += '/' + id;
			}
		}
		//判断必填字段是否填写  $msg[i].value==0 ||
	    var $msg = obj.find('.msg_must');
	    for(var i=0;i<$msg.length;i++){
	    	console.info($($msg[i]).parent().parent().css('display'));
	    	if($($msg[i]).parent().parent().css('display') == 'block'){
	    		if($msg[i].value==0 ||$msg[i].value=='' || $msg[i].value==undefined || $msg[i].value==$msg[i].getAttribute('placeholder').toString()){
		          	common.alert($msg[i].getAttribute('placeholder'));
		          	list.is_click = true;
		          	return false;
		        }
	    	}
	    }
	    //如果是admin  再次验证
	    if(url.indexOf('manage/admin')>0 || url.indexOf('manage/register')>0){
	    	var role = obj.find('#role').val();
	    	if(role){
	    		if(!admin.checkData(role, obj)){
	    			list.is_click = true;
		    		return false;
		    	}
	    	}
	    }
		$.ajax({
			url: url,
	        type: type,
	        data: data,
	        dataType: 'json',
	        success:function(msg){
	          	if(msg.status){
	          		if(is_reload){
	          			if(index){
	          				layer.close(index);
	          				window.location.href=msg.url;
	          			}else{
	          				common.alert(msg.info, is_reload, msg.url);
	          			}
	          		}else{
	          			common.message(msg.info);
	          		}
	          	}else{
	          		common.alert(msg.info);
	          		list.is_click = true;
	          	}
	        },
	        error: function(){
	        	common.alert('提交出错...');
	        	list.is_click = true;
	        }
		})
	},
	delAjax: function(url, data, id){
		$.ajax({
			url: url,
	        type: 'DELETE',
	        data: data,
	        dataType: 'json',
	        success:function(msg){
	          	if(msg.status){
	          		if(msg.is_reload){
	          			window.location.reload();
	          		}
	          		if(id){
	          			$('#list-'+id).remove();
	          		}
	          		if(data){
	          			console.info(data);
	          			for(var i in data.data){
	          				console.info(data.data[i]);
	          				$('#list-'+data.data[i]).remove();
	          			}
	          		}
	          	}else{
	          		common.alert(msg.info);
	          	}
	        },
	        error: function(){
	        	common.alert('删除出错...');
	        }
		});
	},
	alert: function(info, is_reload, url){
		if(is_reload){
			layer.alert(info, function(){
				if(url){
	              	window.location.href=url;
	            }else{
	              	window.location.reload();
	            }
			});
		}else{
			layer.alert(info);
		}
	},
	message: function(info){
		layer.msg(info);
	},
	confirm: function(info, yes, no){
		layer.confirm(info, {
		  	btn: ['确定','取消'] //按钮
		}, function(index){
			if(typeof yes == "function"){
				yes(index);
			}
		}, function(index){
			if(typeof no == "function"){
				no(index);
			}
		});
	},
	openLayer: function(title, url, page, obj, data){
		area = common.layer[page].area || ['500px','400px'];
		obj = obj || common.layer.form;
		obj = $('form#'+obj);
		layer.open({
		  	type: 1,
		  	title: title,
			closeBtn: 1,
			shadeClose: true,
			skin: common.layer.skin,
			area: area,
			maxmin: true,
			content: $('#layer-edit'),
			btn: ['提交', '取消'],
			success: function(layero, index){
				if(data){
					common.setLayerData(obj, data, common.layer[page].select2);
				}else{
					//初始化标签选择
					if(page == 'article'){
						common.initTag(obj, 'js-multiple');
					}
				}
				if(page == 'code'){
					list.checkPrice(obj);
				}
				if(common.layer[page].select2){
					common.setSelect2Info(obj, common.layer[page].select2);
				}
			},
			yes: function(index, layero){
				if(list.is_click){
					list.is_click = false;
					var id = obj.find('#id').val();
					if(id){
						common.doAjax(obj, 'PUT');
					}else{
						common.doAjax(obj, 'POST');
					}
				}else{
					common.alert('正在提交...');
				}
		  	},
		  	btn2: function(){
		  		obj[0].reset();
		  		obj.find('input[name="id"]').val('');
		  	},
		  	cancel: function(){
		  		obj[0].reset();
		  		obj.find('input[name="id"]').val('');
		  	}
		});
	},
	openIframe: function(title, url, is_reload){
		layer.open({
		  	type: 2,
		  	title: title,
			area: ['90%', '90%'],
			maxmin: true,
			content: url,
		  	cancel: function(){
		  		if(is_reload){
		  			window.location.reload();
		  		}
		  	}
		});
	},
	editLayer: function(title, url, page, obj, view_url){
		$.ajax({
			url: url,
	        type: 'GET',
	        data: '',
	        dataType: 'json',
	        success: function(msg){
	          	if(msg.status){
	          		common.openLayer(title, view_url, page, obj, msg.data);
	          	}else{
	          		common.alert(msg.info);
	          	}
	        },
	        error: function(){
	        	common.alert('获取数据出错...');
	        }
		})
	},
	setLayerData: function(obj, data, select2){


        // console.log(data.department_list);

        // //填充部门select
        // var depSelectObj = $('#department_id');
        // depSelectObj.html('');
        // $.each(data.department_list, function(index, item){
        // 	var itemOption = $('<option></option>');
        //     depSelectObj.append(itemOption)
        //     itemOption.text(item.name);
        //     itemOption.val(item.id);
        //     if(item.id == data['department_id'])
        //     	itemOption.attr('selected');
        //     depSelectObj.append(itemOption)
        // });

		if(select2){  //需要选择项目 自动选取
			common.setSelect2Info(obj, select2);
		}
		for(var key in data){
			if((key == 'thumb' || key == 'avatar') && data[key]){
				src = img_show + data[key];
				var html = '<li>'
						 + '	<img src="' + src + '">'
						 + '	<input type="hidden" name="thumb" value="' + data[key] + '">'
						 + '</li>';
				obj.find('#fileShow').html(html);
			}/*else if(key == 'role'){
				obj.find('input[name="status"][value="'+ data[key] +'"]').prop('checked',true);
				obj.find('input[name="status"][value="'+ data[key] +'"]').parent().addClass('checked');
			}*/else if(key == 'tags'){
				if(data[key]){
					var id_data = [];
					for(var i in data[key]){
						id_data.push(data[key][i]['id']);
					}
					//初始化标签选择
					var $select = common.initTag(obj, 'js-multiple');
					$select.val(id_data).trigger("change");
				}
			}else if(key == 'permission'){
				if(data[key]){
					var id_data = [];
					for(var i in data[key]){
						obj.find('input[name="permission[]"][value="'+ data[key][i]['id'] +'"]').prop('checked',true);
					}
				}
			}else if(data['parame1'] && key == 'supervision_id'){
              	var leng = data['supervision'].length;
              	if(leng > 0){
              		var str = '<option value="0">请选择监理</option>';
		            for(var j=0; j<leng; j++){
		                str += '<option value="'+data['supervision'][j]['id']+'">'+data['supervision'][j]['name']+'</option>';
		            }
		            $('#supervision_id').html('').append(str).val(data['supervision_id']);
              	}

              	var leng = data['section'].length;
              	if(leng > 0){
              		var str = '<option value="0">请选择标段</option>';
		            for(var j=0; j<leng; j++){
		                str += '<option value="'+data['section'][j]['id']+'">'+data['section'][j]['name']+'</option>';
		            }
		            $('#section_id').html('').append(str).val(data['section_id']);
              	}
            }else if(key == 'collection_id'){
              	var leng = data['col'].length;
              	if(leng > 0){
              		var str = '<option value="0">请选择采集点</option>';
	              	for(var j=0; j<leng; j++){
	                	str += '<option value="'+data['col'][j]['id']+'">'+data['col'][j]['name']+'</option>';
	              	}
	              	$('#show-cjd').show();
	              	$('#collection_id').html('').append(str).val(data['collection_id']);
              	}else{
              		$('#show-cjd').hide();
              	}
            }else{
    			if(data['role'] && !data['supervision_id']){
            		obj.find('.hidden-xtgly').hide();
            	}else if(data['role'] && !data['section_id']){
            		obj.find('.hidden-zjb').hide();
            	}
            	if(data['role'] && data['section_id'] && obj.find('#section_id option').length==1){
            		var str = '<option value="0">请选择</option>';
            		var leng = data['section_list'].length;
            		for(var j=0; j<leng; j++){
	                	str += '<option value="'+data['section_list'][j]['id']+'">'+data['section_list'][j]['name']+'</option>';
	              	}
            		obj.find('#section_id').html('').append(str);
            	}
                if(data['role'] && data['position_list']){
                    var str = '<option value="0">请选择</option>';
                    var leng = data['position_list'].length;
                    for(var j=0; j<leng; j++){
                        str += '<option value="'+data['position_list'][j]['id']+'">'+data['position_list'][j]['name']+'</option>';
                    }
                    obj.find('#position_id').html('').append(str);
                    obj.find('#position_id').val(data['position_id']);
                }

                if(data['role'] && data['department_list']){
                    var str = '<option value="0">请选择</option>';
                    var leng = data['department_list'].length;
                    for(var j=0; j<leng; j++){
                        str += '<option value="'+data['department_list'][j]['id']+'">'+data['department_list'][j]['name']+'</option>';
                    }
                    obj.find('#department_id').html('').append(str);
                    obj.find('#department_id').val(data['department_id']);
                }
                if(data['role'] && data['company_list']){
                    var str = '<option value="0">请选择</option>';
                    var leng = data['company_list'].length;
                    for(var j=0; j<leng; j++){
                        str += '<option value="'+data['company_list'][j]['id']+'">'+data['company_list'][j]['name']+'</option>';
                    }
                    obj.find('#company_id').html('').append(str);
                    obj.find('#company_id').val(data['company_id']);
                }

				obj.find('#'+key).val(data[key]);
			}
		}
		//$obj = layer.getChildFrame('article', index).find('form#'+obj);
	},
	showImage: function() {
		$('.show-image').fancybox({
		  	'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'titlePosition' : 'inside'
		});
	},
	infoLayer: function(title, content, area){
		var cur_index;
		layer.open({
		  	type: 1,
		  	title: title,
			skin: common.layer.skin,
			area: area,
			content: content,
			success: function(layero, index){
				cur_index = index;
			}
		});
		return cur_index;
	},
	setDiv: function(obj, type, page){        //admin根据 编辑的type类型显示隐藏div
		if(page == 'admin' || page == 'pda'){
			obj = obj || common.layer.form;
			obj = $('#'+obj);
			obj.find('.show-all').show();
			obj.find('#name').attr('disabled', false);
			obj.find('#username').attr('disabled', false);
			if(type == 'pass'){
				obj.find('.hidden-pass').hide();
				obj.find('#name').attr('disabled', true);
				obj.find('#username').attr('disabled', true);
			}
			if(type == 'edit'){
				obj.find('#username').attr('disabled', true);
				obj.find('#status').css('display','none');
				obj.find('#pass').css('display','none');
			}
			obj.find('#passstr_div').css('display','none');
			obj.find('#act_type').val(type);
		}
	},
	initTag: function(obj, key){
		var $select = obj.find("."+key).select2({
			placeholder: '添加标签',
			tags: true,
			tokenSeparators: [',', ' ']
		});
		return $select;
	},
	setSelect2Info: function(obj, select2){
		obj.find('#'+select2.set_key+'id').val($('#'+select2.key).select2("data")[0]['id']);
		obj.find('#'+select2.set_key+'name').val($('#'+select2.key).select2("data")[0]['text']).attr('disabled', true);
	}
}


var list = {
	is_click: true,
	init: function(){
		this.add();
		this.edit();
		this.sort();
		this.del();
		this.delall();
		this.tableList();
		this.search();
		this.getInfo();
		this.status();
		this.changeProject();
		this.openIframe();
	},
	add: function(){
		$('.add-r').on('click',function(){
			var title = $(this).attr('data-title');
			var url = $(this).attr('data-url');
			var page = $(this).attr('data-for');
			var obj = $(this).attr('data-id');
			if(page == 'admin'){
				common.setDiv(obj, 'add', page);
			}
			if(page == 'agent'){
				common.setDiv(obj, 'add', page);
			}
			common.openLayer(title, url, page, obj);
		});
	},
	edit: function(){
		$('.edit-r').on('click',function(){
			var title = $(this).attr('data-title');
			var url = $(this).attr('data-url');
			var page = $(this).attr('data-for');
			var obj = $(this).attr('data-id');
			var view_url = $(this).attr('data-view-url');
			if(page == 'admin'){
				var type = $(this).attr('data-type');
				common.setDiv(obj, type, page);
			}
			if(page == 'agent'){
				common.setDiv(obj, 'edit', page);
			}
			common.editLayer(title, url, page, obj, view_url);
		});
	},
	sort: function(){
		$('.sort-r').on('blur', function(){
			var url = $(this).attr('data-url');
			var data = {'sort': $(this).val(), 'change_type': 'sort'};
			common.doAjax($(this), 'PUT', data);
		});
	},
	del: function(){
		$('.del-r').on('click',function(){
			var url = $(this).attr('data-url');
			var id = $(this).attr('data-id');
			var yes = function(index){
						common.delAjax(url, '', id);
						layer.close(index);
					  }
			common.confirm('确定删除吗？', yes, '');
		});
	},
	delall: function(){
		$('.del-r-more').on('click',function(){
			var url = $(this).attr('data-url');
			var chk_value = [];
			$('input[name="del"]:checked').each(function(){
				chk_value.push($(this).val());
			});
			if(chk_value.length < 1){
				common.alert('请选择删除的内容');
				return false;
			}
			var data = {'data': chk_value};
			var yes = function(index){
						common.delAjax(url, data);
						layer.close(index);
					  }
			common.confirm('确定删除吗？', yes, '');
		});
	},
	tableList: function(){
		if($('#table_list').length>0){
			$('#table_list').dataTable({
				//"aaSorting": [[ 1, "asc" ]],//默认第几个排序
				"bStateSave": true,//状态保存
				"paging": false,
				"searching": false,
				"info": false,
				"aoColumnDefs": [
					//{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
					{"orderable":false,"aTargets":[-1]}// 不参与排序的列
				]
			});
		}
	},
	search: function(){
		$('#search_form').on('submit', function(){
		    var data_arr = $(this).attr('data').split(',');
		    var is_true = false;
		    if($('#start_date').val() && $('#end_date').val()){
		    	if($('#end_date').val() < $('#start_date').val()){
		    		common.alert('结束时间不能小于开始时间');
		      		return false;
		    	}
		    }
		    for(var i=0; i<data_arr.length; i++){
		    	//console.info($('#'+data_arr[i]).val());
		      if($('#'+data_arr[i]).val()){
		        is_true = true;
		      }
		    }
		    if(is_true){
		      $('#search_form').submit();
		    }else{
		      common.alert('请输入搜索的内容');
		      return false;
		    }
		});
	},
	getInfo: function(){
		$('.get-info').on('click', function(){
			var url = $(this).attr('data-url');
			var info =  $(this).attr('data-info');
			$.ajax({
				url: url,
		        type: 'GET',
		        data: '',
		        dataType: 'json',
		        success: function(msg){
		          	if(msg.status){
		          		list.showInfo(msg.data, info);
		          	}else{
		          		common.alert(msg.info);
		          	}
		        },
		        error: function(){
		        	common.alert('获取数据出错...');
		        }
			})
		})
	},
	status: function(){
		$('.td-manage').on('click', '.status-r',function(){
			var yes = function(index){
					doStatus();
					layer.close(index);
				}

			common.confirm('确定执行吗？', yes, '');

			var that = $(this);
			var url = that.attr('data-url');
			var value_title = that.attr('data-title').split(',');
			var value_span = that.attr('data-span').split(',');
			if(that.attr('data-status')==1){
				var status =  0;
				var title = value_title[1];
				var span = value_span[0];
				var icon = '&#xe615;';
				var color = 'label-danger';
			}else{
				var status =  1;
				var title = value_title[0];
				var span = value_span[1];
				var icon = '&#xe631;';
				var color = 'label-success'
			}

			var data = {'has_sh': status, 'change_type': 'has_sh'};

			var doStatus = function(){
				$.ajax({
					url: url,
			        type: 'PUT',
			        data: data,
			        dataType: 'json',
			        success:function(msg){
			          	if(msg.status){
			          		common.message(msg.info);
			          		//修改对应行状态
			          		that.parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" data-url="'+ url +'" class="mt-5 ml-5 status-r btn btn-success radius size-MINI" href="javascript:;" data-title="禁止审核权限,开放审核权限" data-span="没有,有" data-type="status" data-status="'+ status +'" title="'+title+'"><i class="Hui-iconfont">'+icon+'</i></a>');
			          		that.parents("tr").find(".td-status").html('<span class="label '+color+' radius">'+span+'</span>');
			          		/*if(status){

			          		}else{
			          			that.parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" data-for="admin" data-url="'+ url +'" class="status-r" href="javascript:;" data-type="status" data-status="'+ status +'" title="正常"><i class="Hui-iconfont">&#xe615;</i></a>');
								that.parents("tr").find(".td-status").html('<span class="label label-danger radius">禁用</span>');
			          		}*/
			          		that.remove();
			          	}else{
			          		common.alert(msg.info);
			          	}
			        },
			        error: function(){
			        	common.alert('提交出错...');
			        }
				})
			}
		});
	},
	checkPermission: function(){
		$(".permission-list2 dt input:checkbox").click(function(){
			$(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
		});
		$(".permission-list2 dd input:checkbox").click(function(){
			var l =$(this).parent().parent().find("input:checked").length;
			var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
			if($(this).prop("checked")){
				$(this).closest("dl").find("dt input:checkbox").prop("checked",true);
			}else{
				if(l==0){
					$(this).closest("dl").find("dt input:checkbox").prop("checked",false);
				}
			}
		});
	},
	checkPrice: function(obj){
		var obj = $('#form_container');
		var num = obj.find('#num').val(1);
		var price = obj.find('#type option:first').prop('selected', 'selected');
		list.amount(obj);
		obj.find('#type').unbind('change').on('change', function(){
			list.amount(obj);
		});

		obj.find('#num').unbind('keyup').keyup(function(){
			list.amount(obj);
		});
		obj.find('#num').unbind('click').click(function(){
			list.amount(obj);
		});
	},
	amount: function(obj){
		var num = obj.find('#num').val();
		var price = obj.find('#type option:selected').attr('data-price');
		var count = num * price;
		$('.card-info .price').html(price);
		$('.card-info .pay').html(count);
	},
	searchName: function(){
		var index; //弹出层
		$('#search').unbind('keydown').on('keydown',function(){
            if(event.keyCode==13){
                doSeaarch();
            }
        });

		$('.search_p').on('click', function(){
			doSeaarch();
		});

		var doSeaarch = function(){
			$('#pid').val(0);
		    $('#show_agent').html('');
			var url = $('.search_p').attr('data-url');
			if(!$('#search').val()){
				common.alert('请输入要选择的代理');
				return false;
			}
			var data = {'keyword': $('#search').val(), 'status': 1}
			$.ajax({
				url: url,
		        type: 'GET',
		        data: data,
		        dataType: 'json',
		        success:function(msg){
		          	if(msg.status){
		          		var data = msg.data;
		                var leng = data.length;
		                if(leng > 1){
		                    var str = '';
		                    for(var i=0; i<leng; i++){
		                        str += '<li class="cf checkuser">'+data[i].username+'<span class="ulist"><input type="radio" value="'+data[i].id+'" data-name="'+data[i].username+'" name="ulist"/></span></li>';
		                    }
		                    $('.name-list ul').html(str);
		                    index = common.infoLayer('搜索列表', $('.name-list'), 'auto');
		                }else if(leng == 1){
		                    $('#pid').val(data[0].id);
		                    $('#show_agent').html(data[0].username);
		                }else{
		                    common.alert('暂无信息,请重新搜索');
		                }
		          	}else{
		          		common.alert(msg.info);
		          	}
		        },
		        error: function(){
		        	common.alert('提交出错...');
		        }
			})
		};

		$('.name-list').on('click', '.ulist', function(){
			$('#pid').val($(this).find('input').val());
			$('#show_agent').html($(this).find('input').attr('data-name'));
			layer.close(index);
		})
	},
	changeProject: function(){
		/*$('#pro_id').on('change', function(){
			window.location.href = $('#search_form').attr('data-url')+'?pro_id='+$('#pro_id').select2("data")[0]['id'];
		});*/
		$('#fac_id').on('change', function(){
			window.location.href = $('#search_form').attr('data-url')+'?fac_id='+$('#fac_id').select2("data")[0]['id'];
		});
		$('#cat_id').on('change', function(){
			window.location.href = $('#search_form').attr('data-url')+'?cat_id='+$('#cat_id').select2("data")[0]['id'];
		});
		$('#m_id').on('change', function(){
			window.location.href = $('#search_form').attr('data-url')+'?m_id='+$('#m_id').select2("data")[0]['id'];
		});
		$('#sec_id').on('change', function(){
			window.location.href = $('#search_form').attr('data-url')+'?sec_id='+$('#sec_id').select2("data")[0]['id'];
		});
	},
	openIframe: function(){
		$('.open-iframe').on('click', function(){
			var is_reload = 0;
			if($(this).attr('data-is-reload')){
				is_reload = 1;
			}
            if($(this).attr('data-title')=='视频回放'){
				// //实验室试验数据中视频回放功能
                var playback_url=$(this).attr('data-url');
                layer.open({
                    type: 2,
					title:'',
                    area:['600px','400px'],
                    content: [playback_url, 'no']//这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
                });
            }else if($(this).text()=='实时视频') {
            	var nowVideoTitle=$(this).attr('data-title');
            	var nowVideoUrl=$(this).attr('data-url');
            	layer.open({
                    type: 2,
                    title:[nowVideoTitle,'font-size:22px;color:blue;'],
					maxmin:true,
                    scrollbar:false,
                    area:['95%','95%'],
                    content: [nowVideoUrl,'no']
				});
            }else if($(this).attr('data-title')=='所有试验室视频') {
                var allVideoTitle=$(this).attr('data-title');
                var allVideoUrl=$(this).attr('data-url');
                layer.open({
                    type: 2,
                    title:allVideoTitle,
                    scrollbar:false,
                    maxmin:true,
                    area:['70%','70%'],
                    content: allVideoUrl
                });

            }else{
                common.openIframe($(this).attr('data-title'), $(this).attr('data-url'), is_reload);
			}
		});
	},
	showInfo: function(data, info){
		if(info == 'material'){
			var info = list.getMaterialInfo(data);
		}
		if(info == 'factory'){
			var info = list.getFactoryInfo(data);
		}
		if(info == 'factory_detail'){
			var info = list.getFactoryDetailInfo(data);
		}
		if(info == 'admin'){
			var info = list.getAdminDetailInfo(data);
		}
  		common.infoLayer(info.title, info.content, info.area);
	},
	getMaterialInfo: function(data){
		var info = {};
		info.title = '材料信息';
		var column = {
					'name': '材料名称',
					'type': '所属分类',
					'dasign_rate': '设计配合比',
					'warn_rate': '报警比例',
					'note': '备注',
				};
		info.content = list.getInfoContent(data, column);
		info.area = ['380px', '350px'];
		return info;
	},
	getFactoryInfo: function(data){
		var info = {};
		info.title = '厂家设置';
		var column = {
					'name': '厂家名称',
					'data_row': '数据总行数',
					'data_analyse_type': '数据分析类型',
					'date_position_row': '日期位置行',
					'date_position_col': '日期位置列',
					'date_formate_type': '日期格式',
					'time_position_row': '时间位置行',
					'time_position_col': '时间位置列',
					'time_formate_type': '时间格式',
					'pb_number_position_row': '配比编号位置行',
					'pb_number_position_col': '配比编号位置列',
					'design_ysb_position_row': '设计油石比位置行',
					'design_ysb_position_col': '设计油石比位置列',
					'design_ysb_standard': '设计油石比标准',
					'fact_ysb_position_row': '实际油石比位置行',
					'fact_ysb_position_col': '实际油石比位置列',
					'hhlwd_position_row': '混合料温度位置行',
					'hhlwd_postion_col': '混合料温度位置列',
					'hhlwd_standard': '混合料温度标准',
					'hhlwd_bj': '开启混合料温度报警',
					'hhlwd_bj_pc': '混合料温度报警偏差',
					'lcwd_position_row': '溜槽温度位置行',
					'lcwd_position_col': '溜槽温度位置列',
					'lcwd_standard': '溜槽温度标准',
					'lcwd_bj': '开启溜槽温度报警',
					'lcwd_bj_pc': '溜槽温度报警偏差',
					'lqwd_position_row': '沥青温度位置行',
					'lqwd_position_col': '沥青温度位置列',
					'lqwd_standard': '沥青温度标准',
					'lqwd_bj': '开启沥青温度报警',
					'lqwd_bj_pc': '沥青温度报警偏差',
					'cl_position_row': '产量位置行',
					'cl_position_col': '产量位置列',
				};
		info.content = list.getInfoContent(data, column);
		info.area = ['380px', '500px'];
		return info;
	},
	getFactoryDetailInfo: function(data){
		var info = {};
		info.title = '材料信息';
		var material_data = {
							'factory_id': '厂家名称',
      						'material_id': '材料名称',
      						'order_num': '顺序号',
      						'cl_position_row': '材料位置行',
      						'cl_position_col': '材料位置列',
      						'fact_z_cjjs': '实际值采集计算',
      						'fact_z_position_row': '实际值位置行',
      						'fact_z_position_col': '实际值位置列',
      						'design_z_cjjs': '设计值采集计算',
      						'design_z_position_row': '设计值位置行',
      						'design_z_position_col': '设计值位置列',
						};
		info.content = list.getInfoContent(data, material_data);
		info.area = ['380px', '400px'];
		return info;
	},
	getAdminDetailInfo: function(data){
		var info = {};
		info.title = '用户信息';
		var admin_data = {
							'project_id': '项目名称',
      						'company':'单位名称',
      						'position':'职位名称',
      						'name':'姓名',
	                        'phone':'联系方式',
	                        'username':'账号',
	                        'role':'角色',
	                        'supervision_id': '监理名称',
      						'section_id': '标段名称',
	                        'status':'状态',
	                        'created_at':'注册时间',
	                        'mod':'子系统'
						};
		info.content = list.getInfoContent(data, admin_data);
		info.area = ['380px', '400px'];
		return info;
	},
	getInfoContent: function(data, column){
		var str = '';
		for(var i in column){
			if(data[i]){
				str += '<p class="username"><strong>'+column[i]+'：</strong>'+ data[i] +'</p>';
			}else{
				str += '<p class="username"><strong>'+column[i]+'：</strong></p>';
			}

		}
		var content = '<div id="userinfo">'
					+		str
					+ '</div>';
		return content;
	}

	/*tableList: function(){
		var url = $('#table_list').attr('data-url');
		var t = $('#table_list').DataTable({
		    ajax: {
		        url: url   //指定数据源
		    },
		    pageLength: 20,   //每页显示20条数据
		    "deferRender": true,
		    columns: [{
		        "data": null //此列不绑定数据源，用来显示序号
		    },{
		        "data": null //此列不绑定数据源，用来显示序号
		    },{
		        "data": "name"
		    },{
		        "data": "thumb"
		    },{
		        "data": "sort"
		    },{
		        "data": null //此列不绑定数据源，用来显示序号
		    }],
		    "columnDefs": [
		    {
	            "targets": 0,
	            "data": function(row, type, val, meta){
	            	console.info(row);
	            	return row.id;
	            }
	        },
		    {
	            "targets": -1,
	            "data": '12',
	            "defaultContent": '<a style="text-decoration:none" data-for="tag" data-title="编辑标签" data-view-url="{{url(\'manage/article_tag/create\')}}" data-url="{{url(\'manage/article_tag/edit\')}}" class="edit-r" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a><a style="text-decoration:none" class="ml-5 del-r" href="javascript:;" data-id="" data-url="{{url(\'manage/article_tag\')}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>'
	        } ],

	        $('#example tbody').on( 'click', 'button', function () {
		        var data = table.row( $(this).parents('tr') ).data();
		        alert( data[0] +"'s salary is: "+ data[ 5 ] );
		    } );

		    {
		        "render": function(data, type, row, meta) {
		            //渲染 把数据源中的标题和url组成超链接
		            return '<a href="' + data + '" target="_blank">' + row.title + '</a>';
		        },
		        //指定是第三列
		        "targets": 2
		    }]

		});

		//前台添加序号
		t.on('order.dt', function() {
		    t.column(0, {
		        "order": 'applied'
		    }).nodes().each(function(cell, i) {
		        cell.innerHTML = i + 1;
		    });
		}).draw();
	}*/
}

var admin = {
	checkData: function(role, obj){
		if(role == 4 || role == 5) {
			if(obj.find('#supervision_id').val() == 0 || !obj.find('#supervision_id').val()){
				common.alert('请选择监理信息');
				return false;
			}
		}
		if(role == 5) {
			if(obj.find('#section_id').val() == 0 || !obj.find('#section_id').val()){
				common.alert('请选择标段信息');
				return false;
			}
		}
		return true;
	}
}

var register = {
	is_yzm: true,
	sj: 59,
	tz: '',
	init: function(){
		this.changeProject();
		this.changeRole();
		this.changeSuper();
		this.getYzm();
	},
	changeProject: function(){
		$('#project_id').on('change', function(){
			var pro = $('#project_id').val();
			var role = $('#role').val();
		    if(pro.length > 1 && role != 2){
		    	common.alert('只有集团用户可以选择多个项目');
		    	$("#project_id").val(null).trigger("change");
		    	return false;
		    }

			if(role == 2 || role == 3){
		    	return false;
		    }

		  if(pro.length = 1){
		    $('#form_container .hidden-xtgly #supervision_id').html('').append('<option value="0">请选择</option>');
		    $('#form_container .hidden-xtgly #section_id').html('').append('<option value="0">请选择</option>');

		    var data = {
			  'pro_id': pro[0]
			};

			if(data.pro_id){
			  register.setOption($(this).attr('data-url'), data, 'supervision_id');
			}
		  }
		});
	},
	changeRole: function(){
		$('#form_container #role').on('change', function(){
		  //根据操作员类型隐藏对应字段 并设置为初始值
		  var role = {
		          '2':'hidden-xtgly',
		          '3':'hidden-xtgly',
		          '4':'hidden-zjb',
		          '5':'hidden-htd',
		        }
		  var val = $(this).val();
		  if(val == 2){
		  	$('#form_container .hidden-jtyh').hide();
		  }else{
		  	$('#form_container .hidden-jtyh').show();
		  }
		  if(val != 1){
		    $('#form_container .hidden-xtgly').show();
		    if(role[val]){
		      $('#form_container .'+role[val]).hide();
		    }
		  }else{
		    $('#form_container .hidden-xtgly').hide();
		  }
		  $('#form_container .hidden-xtgly #supervision_id').val(0);
		  $('#form_container .hidden-xtgly #section_id').html('').append('<option value="0">请选择</option>');

			if(val){
				var data = {
			        'role': val,
			      };
		        register.setOption($(this).attr('data-url'), data, 'position_id');
		    }
		});
	},
	changeSuper: function(){
		$('#supervision_id').on('change', function(){
		  if($('#supervision_id').val() && $('#supervision_id').val() !=0 ){
		    var role = $('#form_container #role').val();
		    $('#form_container .hidden-xtgly #section_id').html('').append('<option value="0">请选择</option>');

		    if(role == 5){
		      var data = {
		        'sup_id': $(this).val(),
		        'pro_id': $('#project_id').val()
		      };

		      if(data.sup_id && data.pro_id){
		        register.setOption($(this).attr('data-url'), data, 'section_id');
		      }
		    }
		  }
		});
	},
	setOption: function(url, data, id){
		$.ajax({
		    url: url,
	        type: 'POST',
	        data: data,
	        dataType: 'json',
	        success:function(msg){

	          var str = '';
	          if(msg.status){
	          	if(msg.status==2){
                   layer.alert(msg.info);
				}
	            str = '<option value="0">请选择</option>';
	            data = msg.data;
	            if(msg.send_path=='get_pos'){
                    //单位信息
                    var company_str='<option value="0">请选择单位名称</option>';
                    var company_data=msg.company;
                    if(company_data.length>0){
                        for(var i=0;i<company_data.length;i++){
                            company_str += '<option value="'+company_data[i]['id']+'">'+company_data[i]['name']+'</option>';
                        }
                        $('.company_select').empty().append(company_str);
                    }
                    //部门信息
                    var department_str='<option value="0">请选择部门名称</option>';
                    var department_data=msg.department;
                    if(department_data.length>0){
                        for(var i=0;i<department_data.length;i++){
                            department_str += '<option value="'+department_data[i]['id']+'">'+department_data[i]['name']+'</option>';
                        }
                        $('.department_select').empty().append(department_str);
                    }
				}


	            //职务信息
	            if(data.length > 0){
	              for(var i in data){
	                str += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
	              }
	            }
	            $('#'+id).html('').append(str);
	          }
	        },
	        error: function(){
	          common.alert('获取信息出错...');
	        }
		});
	},
	getYzm: function(){
		$('.phone_yzm').on('click', function(){
			if(register.is_yzm){
				var phone = $('#phone').val();
				if(phone){
					//检测手机号码
					var myreg =/^0{0,1}(13[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/;
					if(!myreg.test($('#phone').val())){
						common.alert('请输入有效的联系方式');
						return false;
					}
					$.ajax({
						url:$('.phone_yzm').attr('data-url'),
						data:'mobile='+phone,
						type:'POST',
						success:function(data){
							common.alert(data.info);
							if(data.status == 1){
								register.is_yzm = false;
								$('.phone_yzm').css('background-color','#6b6868').html('60s后重新获取');
								register.sj = 59;
								register.tz = setInterval(register.setHtml,1000);
							}
						}
					});
				}else{
					common.alert('请先填写手机号码');
				}
			}
		});

	},
	setHtml: function(){
		$('.phone_yzm').attr('enabled',0).html(register.sj + '后重新获取');
		register.sj = register.sj-1;
		if(register.sj < 1){
			register.is_yzm = true;
			$('.phone_yzm').attr('enabled',1).css('background-color','#03a9f4').html('点击获取');
			clearInterval(register.tz);
		}
	}
}

var findpass = {
	is_yzm: true,
	sj: 59,
	tz: '',
	init: function(){
		this.getYzm();
	},
	getYzm: function(){
		$('.phone_yzm').on('click', function(){
			if(findpass.is_yzm){
				$.ajax({
					url:$('.phone_yzm').attr('data-url'),
					data:'',
					type:'POST',
					success:function(data){
						common.alert(data.info);
						if(data.status == 1){
							findpass.is_yzm = false;
							$('.phone_yzm').css('background-color','#6b6868').html('60s后重新获取');
							findpass.sj = 59;
							findpass.tz = setInterval(findpass.setHtml,1000);
						}
					}
				});
			}
		});
	},
	setHtml: function(){
		$('.phone_yzm').attr('enabled',0).html(findpass.sj + '后重新获取');
		findpass.sj = findpass.sj-1;
		if(findpass.sj < 1){
			findpass.is_yzm = true;
			$('.phone_yzm').attr('enabled',1).css('background-color','#03a9f4').html('点击获取');
			clearInterval(findpass.tz);
		}
	}
}