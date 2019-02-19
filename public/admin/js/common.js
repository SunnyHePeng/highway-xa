$(function(){
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
	//菜单点击伸缩
	$(".menu").on('click',function(){
		$('.sub_nav').slideUp();
		$(this).next('.sub_nav').slideToggle();
	});
  //提交
	$('#formsubmit').on('click',function(){
      is_soft = 0;
      //判断必填字段是否填写  $msg[i].value==0 || 
      var $msg = $('#form').find('.msg_must');
      for(var i=0;i<$msg.length;i++){
        if($msg[i].value=='' || $msg[i].value==undefined || $msg[i].value==$msg[i].getAttribute('placeholder').toString()){
          show_alert('信息提示',$msg[i].getAttribute('placeholder'));
          return false;
        }   
      }
      //判断提交类型
      if($('#id').val()){
        var type = 'PUT';
        var url = $(this).attr('data-store-url')+'/'+$('#id').val();
      }else{
        var type = 'POST';
        var url = $(this).attr('data-store-url');
      }
      
      if(url.indexOf('manage/game')>0 && $('#apk').length > 0 ){
        //判断是否有上传软件
        var is_soft = 1;
        //显示正在保存 请稍后
        $('div[data-reveal-id]').trigger('click');
      }
      var data = $('#form').serialize();
      $('#formsubmit').attr('disabled',true);
      $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        success:function(msg){
          show_alert('信息提示',msg.info);
          if(msg.status){
            if(is_soft == 1 || url.indexOf('manage/admin_fee')>0){
              $('.close-reveal-modal').trigger('click');
            }
            if(msg.url){
              setTimeout("window.location.href='"+msg.url+"'",1000);
            }else{
              setTimeout("window.location.reload()",1000);
            }
          }
        }
      });
  });
  //编辑赋值
  $('.list_edit').on('click',function(){
    var url = $(this).attr('data-url');
    if(url.indexOf('manage/admin')>0){
      var type = $(this).attr('data-val');
      url += '?type='+type;
      if(type == 'role'){
        $('#pass_div').hide();
        $('#passstr_div').hide();
        $('#role_div').show();  
        $('#password').removeClass('msg_must'); 
        $('#role').addClass('msg_must'); 
      } 
      if(type == 'pass'){
        $('#role_div').hide();
        $('#pass_div').show();
        $('#passstr_div').show();
        $('#password').addClass('msg_must');
        $('#role').removeClass('msg_must');     
      }
      if(type == 'status'){
        if($(this).attr('data-status') == 1){
          var data = 'status=0';
        }else{
          var data = 'status=1';
        }
        easyDialog.open({
          container : {
              header : '信息提示',
              content : '确定要执行吗？',
              yesFn : function(){
                $.ajax({
                  url: url,
                  type: 'PUT',
                  data: data,
                  dataType: 'json',
                  success:function(msg){
                    show_alert('信息提示',msg.info);
                    if(msg.status){
                      setTimeout("window.location.href='"+msg.url+"'",1000);
                    }
                  }
                }); 
              },
              noFn : true
          }
        });
        return false;   
      } 
    }
          
    $.ajax({
      url: url,
      type: 'GET',
      data: '',
      dataType: 'json',
      success:function(msg){
        if(msg.status){
          //赋值 并显示弹出层
          var data = msg.data;
          for(var key in msg.data){
            if($.inArray(key, edit_data) >= 0){
              if(key == 'pay_channel'){
                var obj = $("input[name='"+key+"'][value='"+data[key]+"']");
                obj.prop("checked",true);
                obj.parent().addClass('is-checked').siblings().removeClass('is-checked');
              }else{
                $('#'+key).val(data[key]);
              }
              if(url.indexOf('manage/admin')>0){
                if(data['role'] == 3){
                  $('#cp_div').show();
                  if(data['cp']){
                    var cp = data['cp'].split(',');
                    for(var i=0; i<cp.length; i++){
                      $('input[name="cp[]"][value="'+cp[i]+'"]').prop('checked',true);
                    }
                  }
                }
              }
              if(url.indexOf('manage/user_phone')>0){
                if(key == 'cpu'){
                  setCpu(data['cpu']);
                }
                if(key == 'sensors'){
                  setSensors(data['sensors']);
                }
              }
              if(url.indexOf('manage/system_vpn')>0){
                if(key == 'province_id'){
                  $('#province').val(data['province_id']);
                }
                if(key == 'city_id'){
                  var leng = data['city_list'].length;
                  var str = '';
                  for(var j=0; j<leng; j++){
                    str += '<option value="'+data['city_list'][j]['id']+'">'+data['city_list'][j]['name']+'</option>';
                  }
                  $('#city').append(str).val(data['city_id']);
                }
              }
            };
          }
          if(url.indexOf('recharge')>0){
            $('.recomment-img').html('<img src="/uploads/'+data['image']+'"><i class="material-icons cancel" onClick="onCancel();" data-id="tj">cancel</i>');
          }
          $('div[data-reveal-id="myModal"]').trigger('click');
        }else{
          show_alert('信息提示',msg.info);
        }
      }
    });
  });
  //删除
  $('.list_del').on('click',function(){
    var url = $(this).attr('data-url');
    var yes_btn = function(){
      $.ajax({
        url: url,
        type: 'DELETE',
        data: '',
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            $('#tr_'+msg.data).remove();
            show_alert('信息提示',msg.info ? msg.info : '删除成功');
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
    }
    
    easyDialog.open({
      container : {
          header : '信息提示',
          content : '确定要删除吗？',
          yesFn : yes_btn,
          noFn : true
      }
    });
    
  });
  //渠道删除
  $('.list_del_channel').on('click',function(){
    var url = $(this).attr('data-url');
    var cid = $(this).attr('data-id');
    var cp = 0;
    if($(this).attr('data-key') == 'cp'){
      cp = 1;
    }
    var force_del = function(){
      //点击强制删除 显示正在删除 
      $.ajax({
        url: url+'?force=1',
        type: 'DELETE',
        data: '',
        dataType: 'json',
        beforeSend: function(){
          easyDialog.open({
            container : 'msgBox',
            fixed : false
          });
        },
        success:function(msg){
          if(msg.status){
            $('#tr_'+msg.data).remove();
            show_alert('信息提示','删除成功');
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
    }

    var list_soft = function(){
      //显示该渠道下的应用
      if(cp){
        window.location.href = '/manage/game?cpid='+cid;
      }else{
        window.location.href = '/manage/game?cid='+cid;
      }
      
    }

    var yes_btn = function(){
      $.ajax({
        url: url,
        type: 'DELETE',
        data: '',
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            $('#tr_'+msg.data).remove();
            show_alert('信息提示','删除成功');
          }else{
            easyDialog.open({
              container : {
                  header : '信息提示',
                  content : msg.info,
                  yesFn : list_soft,
                  noFn : force_del,
                  yesText : '查看应用',
                  noText : '强制删除'
              }
            });
          }
        }
      });
    }
    
    easyDialog.open({
      container : {
          header : '信息提示',
          content : '确定要删除吗？',
          yesFn : yes_btn,
          noFn : true
      }
    });
    
  });
  //暂停
  $('.list_zt').on('click',function(){
    var url = $(this).attr('data-url');
    $.ajax({
      url: url,
      type: 'DELETE',
      data: '',
      dataType: 'json',
      success:function(msg){
        if(msg.status){
          show_alert('信息提示','执行成功');
          if(msg.url){
            setTimeout("window.location.href='"+msg.url+"'",1000);
          }
        }else{
          show_alert('信息提示',msg.info);
        }
      }
    });
  });
  //表格排序
  $('.table_sort').on('click',function(){
    var tableId = $(this).attr('data-id');
    var tableIdx = $(this).attr('data-tr');
    var is_str = $(this).attr('data-str');
    $.sortTable.sort(tableId,tableIdx,is_str);
  });
  //生成key salt
  $('#makekey').on('click',function(){
    var url = $(this).attr('data-url');
    var name = $('#name').val();
    var end_time = $('#end_time').val();
    var data = 'name='+name+'&end_time='+end_time;
    if(name && end_time){
      $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            $('#name').attr('readonly');
            $('#end_time').attr('readonly');
            $('#show_name').html($('#name').val());
            $('#show_key').html(msg.key);
            $('#show_salt').html(msg.salt);
            $('#show_time').html($('#end_time').val());
            $('#name_val').val($('#name').val());
            $('#key_val').val(msg.key);
            $('#salt_val').val(msg.salt);
            $('#time_val').val($('#end_time').val());
            $('.show_key').show();
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
    }else{
      show_alert('信息提示','请先输入名称和截止时间');
    }    
  });
  //延期
  $('.list_yq').on('click',function(){
    $('#form').hide();
    $('#show_yq').show();
    $('div[data-reveal-id]').trigger('click');
    var url = $(this).attr('data-url'); 
    $('#formsubmit_yq').attr('data-url', url);
  });

  $('#formsubmit_yq').on('click', function(){
    var url = $(this).attr('data-url');
    var data = 'end_time='+$('#end_time_yq').val();
    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            show_alert('信息提示','执行成功');
            if(msg.url){
              setTimeout("window.location.href='"+msg.url+"'",1000);
            }
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
  });

  $('#search_form').on('submit', function(){
    var url = $(this).attr('data-url');
    var data_arr = $(this).attr('data').split(',');
    var is_true = false;
    var data = '';
    for(var i=0; i<data_arr.length; i++){
      if($('#'+data_arr[i]).val()){
        is_true = true;
        if(data){
          data += '&';
        }
        data += $('#'+data_arr[i]).attr('name')+'='+$('#'+data_arr[i]).val();
      }
    }
    if(is_true){
      window.location.href= url +'?'+data;
    }else{
      show_alert('信息提示','请输入搜索的内容');
      return false;
    }
  });

  $('.list_status').on('click', function(){
    var obj = $(this);
    var url = obj.attr('data-url');
    var data = 'id='+obj.attr('data-id')+'&status='+obj.attr('data-val')+'&c_status=1';
    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            show_alert('信息提示','执行成功');
            if(msg.url){
              setTimeout("window.location.href='"+msg.url+"'",1000);
            }
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
  });

  $('.list_trashed_edit').on('click', function(){
    var obj = $(this);
    var url = obj.attr('data-url');
    var data = 'act='+obj.attr('data-type');
    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            show_alert('信息提示','执行成功');
            setTimeout("window.location.reload()",1000);
          }else{
            show_alert('信息提示',msg.info);
          }
        }
      });
  });

  $('.list_trashed_del').on('click', function(){
    var obj = $(this);
    var url = obj.attr('data-url');
    var data = 'act='+obj.attr('data-type');
    $.ajax({
      url: url,
      type: 'DELETE',
      data: data,
      dataType: 'json',
      success:function(msg){
        if(msg.status){
          $('#tr_'+msg.data).remove();
          show_alert('信息提示','删除成功');
        }else{
          show_alert('信息提示',msg.info);
        }
      }
    });
  });
});

$('.check_login_abnormal').on('click',function(){
  var id = $(this).attr('data-id');
  var url = $('#abnormal').attr('data-url');
  if(id && url){
    $.ajax({
      type:'post',
      data:'',
      url: url+'/'+id,
      dataType: 'json',
      success:function(msg){
        show_alert('信息提示',msg.info);
        if(msg.status){
          $('li#abnormal_'+msg.data).remove();
        }
      }
    });
  }else{
    show_alert('信息提示','参数错误，请重新操作');
  }
});

$('.get_fresh').on('click',function(){
  var key = $(this).attr('data-id');
  var url = $('#model').attr('data-url')+'?'+key+'=1';
  $.ajax({
      type:'post',
      data:'',
      url: url,
      dataType: 'json',
      success:function(msg){
        if(msg.status){
          $('#'+key).val(msg.info);
        }else{
          show_alert('信息提示',msg.info);
        }
      }
  });
});

$('select#brand').on('change',function(){
  getModel();
});

$('select#model').on('change',function(){
    var obj = $(this);
    var url = obj.attr('data-url')+'?model='+obj.val()+'&brand='+$('#brand').val();
    $.ajax({
        url: url,
        type: 'post',
        data: '',
        dataType: 'json',
        success:function(msg){
          if(msg.status){
            info = msg.info;
            for(var i in info){
              $('#'+i).val(info[i]);
              if(i == 'cpu'){
                setCpu(info['cpu']);
              }
              if(i == 'sensors'){
                setSensors(info['sensors']);
              }
            }
          }else{
            show_alert('信息提示',msg.info);
          }
        }
    });
});

$('#makeuser').on('click',function(){
    var url = $(this).attr('data-url');
    var data = $('#make_user_form').serialize();
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success:function(msg){
            show_alert('信息提示',msg.info);
            if(msg.status){
              $('#make_user .close-reveal-modal').trigger('click');
              setTimeout("window.location.reload()",1000);
            } 
        }
    });
});

/*$('.list_task').on('click',function(){
    $('#machine_num').html($(this).attr('data-machine-num'));
    $('#machine_info').html($(this).attr('data-machine-info'));
    $('#task_info').html($(this).attr('data-task'));
    $('#plan_date').html($(this).attr('data-plan'));
    $('#end_date').html($(this).attr('data-end'));
    $('div[data-reveal-id="task"]').trigger('click');
});*/

$('#exportuser').on('click',function(){
  //判断是否选择渠道
  if(!ischeck('channel[]')){
    show_alert('信息提示','请选择渠道');
    return false;
  }
  var status = $('input[name="status"]:checked').val();
  if(status == undefined || status == null){
    show_alert('信息提示','请选择状态');
    return false;
  }
  $('#export_user_form').submit();
});

$('.phonemodel').on('click',function(){
  var url = $(this).attr('data-url');
  var number = $(this).attr('data-number');
  $.ajax({
      url: url,
      type: 'GET',
      data: '',
      dataType: 'json',
      success:function(msg){
          if(msg.status){
            var data = msg.data;
            var str = '';
            for(i in data){
              if(i == 'cpu'){
                str += '<li>'+i+'：<span>';
                for(j in data['cpu']){
                  str += j+':'+data['cpu'][j]+'&emsp;';
                }
                str +='</span></li>';
              }else if(i == 'sensors'){
                str += '<li>'+i+'：<span></span></li>';
                for(j in data['sensors']){
                  str += '<li>&emsp;&emsp;&emsp;&emsp;type:'+data['sensors'][j]['type']+'&emsp;name:'+data['sensors'][j]['name']+'</li>';
                }
              }else{
                str += '<li>'+i+'：<span>'+data[i]+'</span></li>';
              }
            }
            $('#phone_model ul').html(str);
            $('#show_phone').trigger('click');
          }else{
            show_alert('信息提示',msg.info);
          }
      }
  });
});

$('.user_game').on('click', function(){
  if($(this).find('input[type="checkbox"]').is(':checked')){
    if($(this).find('.span-name').length==0){
      var str = ' <div class="mdl-textfield mdl-js-textfield textfield-demo span-name">'
              + '  <input class="mdl-textfield__input" placeholder="级别" type="text" name="game[level][]" value=""/>'
              + '  <input type="hidden" name="game[created_at][]" value=""/>'
              + ' </div>';
      $(this).append(str);
    }
  }else{
    $(this).find('.span-name').remove();
  }
  
});

$('.list_recharge').on('click',function(){
  if($(this).attr('data-id')){
    $('#recharge').val('');
    $('#id').val($(this).attr('data-id'));
    $('div[data-reveal-id="pay"]').trigger('click');
  }else{
    var url = $(this).attr('data-url')
    $.ajax({
        url: url,
        type: 'POST',
        data: '',
        dataType: 'json',
        success:function(msg){
            if(msg.status){
              $('#pay_channel').html(msg.data.pay_channel);
              $('#account').html(msg.data.account);
              $('#pass').html(msg.data.pass);
              $('div[data-reveal-id="pay"]').trigger('click');
            }else{
              show_alert('信息提示',msg.info);
            }
        }
    });
  } 
});

$('.search_user').on('click',function(){
  if($('#user_name').val()){
    var url = $(this).attr('data-url');
    $.ajax({
        url: url,
        type: 'POST',
        data: 'name='+$('#user_name').val()+'&gid='+$('#game_id').val(),
        dataType: 'json',
        success:function(msg){
            if(msg.status){
              var data = msg.data;
              var leng = data.length;
              if(leng > 1){
                var str = i = '';
                for(i=0; i<leng; i++){
                  str += '<li class="cf" onClick="checkUser('+i+')">'+data[i].username+'<span class="softlist"><input type="checkbox" class="mdl-checkbox__input soft_list" value="'+data[i].id+'" data-name="'+data[i].username+'" name="soft_list"/></span></li>';  
                }
                $('#user_list ul').html('').append(str);
                $('#user_list').css('left',($(window).width() - 300)/2+'px').fadeIn();
                //$('div[data-reveal-id="user_list"]').trigger('click');
              }else{
                $('#user_name').val(data[0].username);
                $('#user_id').val(data[0].id);
              }
            }else{
              show_alert('信息提示',msg.info);
            }
        }
    });
  }else{
    show_alert('信息提示',$('#user_name').attr('placeholder'));
  }
});

$('#user_list .close-modal').on('click',function(){
  $('#user_list').fadeOut();
});

$('#game_name').on('change',function(){
  $('#game_id').val($('#game_name option:selected').attr('data-id'));
});

//编辑赋值
$('.list_pay_content').on('click',function(){
  var recharge_url = $(this).attr('data-recharge-url'); 
  var url = $(this).attr('data-url');
  $.ajax({
    url: recharge_url,
    type: 'GET',
    data: '',
    dataType: 'json',
    success:function(msg){
      if(msg.status){
        //赋值 并显示弹出层
        var data = msg.data;
        for(var key in msg.data){
          if($.inArray(key, edit_data) >= 0){
            $('#recharge_'+key).val(data[key]);
          };
        }
        $('.recomment-img').html('<img src="/uploads/'+data['image']+'"><i class="material-icons cancel" onClick="onCancel();" data-id="tj">cancel</i>');
        $('div[data-reveal-id="recharge"]').trigger('click');
      }else{
        $.ajax({
            url: url,
            type: 'GET',
            data: '',
            dataType: 'json',
            success:function(msg){
              if(msg.status){
                $('#recharge_form input').val('');
                $('.recomment-img').html('');
                //赋值 并显示弹出层
                var data = msg.data;
                for(var key in msg.data){
                  if($.inArray(key, edit_data) >= 0){
                    if(key == 'id'){
                      $('#recharge_fee_'+key).val(data[key]);
                    }else{
                      $('#recharge_'+key).val(data[key]);
                    }
                  };
                }
                //$('.recomment-img').html('<img src="/uploads/'+data['image']+'"><i class="material-icons cancel" onClick="onCancel();" data-id="tj">cancel</i>');
                $('div[data-reveal-id="recharge"]').trigger('click');
              }else{
                show_alert('信息提示',msg.info);
              }
            }
        });
      }
    }
  });
});

//提交
$('#recharge_formsubmit').on('click',function(){
    is_soft = 0;
    //判断必填字段是否填写  $msg[i].value==0 || 
    var $msg = $('#recharge_form').find('.msg_must');
    for(var i=0;i<$msg.length;i++){
      if($msg[i].value=='' || $msg[i].value==undefined || $msg[i].value==$msg[i].getAttribute('placeholder').toString()){
        show_alert('信息提示',$msg[i].getAttribute('placeholder'));
        return false;
      }   
    }
    //判断提交类型
    if($('#recharge_id').val()){
      var type = 'PUT';
      var url = $(this).attr('data-store-url')+'/'+$('#recharge_id').val();
    }else{
      var type = 'POST';
      var url = $(this).attr('data-store-url');
    }
    
    var data = $('#recharge_form').serialize();
    $('#recharge_formsubmit').attr('disabled',true);
    $.ajax({
      url: url,
      type: type,
      data: data,
      dataType: 'json',
      success:function(msg){
        show_alert('信息提示',msg.info);
        if(msg.status){
          $('#recharge .close-reveal-modal').trigger('click');
        }
      }
    });
});

$('input[type="checkbox"].mgroup').on('click',function(){
  /*var obj = $(this).children('input[type="checkbox"]');*/
  console.info($(this).prop('checked'));
  if($(this).is(':checked') == true){
    $('.mgroup_'+$(this).val()).prop('checked',true);
  }else{
    $('.mgroup_'+$(this).val()).prop('checked',false);
  }
});

$('.machine_list').on('click', function(){
  var mgroup_id = $(this).attr('data-id');
  if($(this).find('input').is(':checked') == true){
    //检测该组是否全部选中  选中则自动选择该组
    var ischeck = true;
    $(".mgroup_"+mgroup_id).each(function(){
      if(this.checked != true){
        ischeck = false;
      }     
    });
    if(ischeck){
      $('#mgroup_role_'+mgroup_id).prop('checked',true);
    }else{
      $('#mgroup_role_'+mgroup_id).prop('checked',false);
    }
  }else{
    //如果该组选中，则去除选中
    if($('#mgroup_role_'+mgroup_id).is(':checked')){
      $('#mgroup_role_'+mgroup_id).prop('checked',false);
    }
  }
});

function ischeck(name){
  var ischeck = false;
  $("input[name='"+name+"']").each(function(){
    if(this.checked == true){
      ischeck = true;
    }     
  });
  return ischeck;
}

function isrepeat(arr){
  var hash = {};
  for(var i in arr){
    if(hash[arr[i]])
    return true;
    hash[arr[i]] = true;
  }
  return false;
}

function checkUser(i){
  var obj = $('#user_list div ul').find('input[name="soft_list"]').eq(i);
  var id = obj.val();
  $('#user_id').val(id);
  $('#user_name').val(obj.attr('data-name'));
  $('#user_list').fadeOut();
}

function getModel(){
  $.ajax({
      url: $('#model').attr('data-url'),
      type: 'POST',
      data: 'brand='+$('#brand').val(),
      dataType: 'json',
      success:function(msg){
          if(msg.status){
            var str = i = '';
            var leng = msg.info.length;
            str = '<option value="">请选择</option>';
            for(i=0; i<leng; i++){
              str += '<option value="'+msg.info[i]+'">'+msg.info[i]+'</option>';
            }
            $('#model').html('').append(str);
          }else{
            show_alert('信息提示',msg.info);
          }
      }
  });
}

function setCpu(cpu){
  var i = '';
  for(i in cpu){
    $('#'+i).val(cpu[i]);    
  }
}

function setSensors(sensors){
  var str='';
  for(i in sensors){
    if(i == 0){
      $('#sensors_0_type').val(sensors[i]['type']);
      $('#sensors_0_name').val(sensors[i]['name']);
    }else{
      var j = parseInt(i)+1;
      if($('#username').length == 0){
        str += getPhonePageSensors(j, i, sensors[i]['type'], sensors[i]['name']);
      }else{
        str += getPhonePageSensors(j, i, sensors[i]['type'], sensors[i]['name']);
      }
    }
  }
  $('.sensors:last').after(str);
}

function getUserPageSensors(j, i, type_val, name_val){
  var str = '<div class="mdl-cell mdl-cell--12-col sensors">'
          + ' <div class="mdl-cell mdl-cell--2-col span-name">sensors_'+j+'</div>'
          + ' <div class="mdl-cell mdl-cell--2-col">'
          + '  <div class="mdl-textfield mdl-js-textfield textfield-demo edit-page">'
          + '     <input class="mdl-textfield__input" placeholder="请输入type" type="text" id="sensors_'+i+'_type" name="phone[sensors]['+i+'][type]" '+type_val+'/>'
          + '        <label class="mdl-textfield__label" for="sample1"></label>'
          + '     </div>'
          + '  </div>'
          + '  <div class="mdl-cell mdl-cell--2-col">'
          + '    <div class="mdl-textfield mdl-js-textfield textfield-demo edit-page">'
          + '      <input class="mdl-textfield__input" placeholder="请输入name" type="text" id="sensors_'+i+'_name" name="phone[sensors]['+i+'][name]" '+name_val+'/>'
          + '      <label class="mdl-textfield__label" for="sample1"></label>'
          + '    </div>'
          + '  </div>'
          + '</div>';
  return str;
}

function getPhonePageSensors(j, i, type_val, name_val){
  var str = '<div class="sensors">'
          + '<span class="span-name">sensors_'+j+'</span>'
          + '<div class="mdl-textfield input-field" style="width:450px;">'
          + '  <div class="mdl-grid">'
          + '    <div class="mdl-cell mdl-cell--5-col">'
          + '      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field page-ipnut">'
          + '        <input class="mdl-textfield__input" placeholder="type" type="text" id="sensors_'+i+'_type" name="sensors['+i+'][type]" '+type_val+'/>'
          + '        <label class="mdl-textfield__label" for="sample1"></label>'
          + '      </div>'
          + '    </div>'
          + '    <div class="mdl-cell mdl-cell--5-col">'
          + '      <div class="mdl-textfield mdl-js-textfield textfield-demo input-field page-ipnut">'
          + '        <input class="mdl-textfield__input" placeholder="name" type="text" id="sensors_'+i+'_name" name="sensors['+i+'][name]" '+name_val+'/>'
          + '        <label class="mdl-textfield__label" for="sample1"></label>'
          + '      </div>'
          + '    </div>'
          + '  </div>'
          + '</div>'
          + '</div>';
  return str;
}

$('.sensors').on('click','.addsensors',function(){
  var i = $('.sensors').length;
  var j = parseInt(i) + 1;
  console.info($('#username').length);
  if($('#username').length == 0){
    var str = getPhonePageSensors(j, i, '', '');
  }else{
    var str = getUserPageSensors(j, i, '', '');
  }
  $('.sensors:last').after(str);
});

$('#modifysubmit').on('click',function(){
  var status = $('#modify_form input[name="status"]:checked').val();
  if(!$('#start_id').val() || !$('#end_id').val()){
    show_alert('信息提示','请输入起始ID');
  }else if($('#start_id').val() >= $('#end_id').val()){
    show_alert('信息提示','开始ID不得大于等于结束ID');
  }else if(status == undefined && !$('#modify_form input[name="end_time"]').val()){
    show_alert('信息提示','状态和到期时间至少选填一个');
  }else{
    var url = $(this).attr('data-url')
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#modify_form').serialize(),
        dataType: 'json',
        success:function(msg){
            show_alert('信息提示',msg.info);
            if(msg.status){
              $('#modify .close-reveal-modal').trigger('click');
              setTimeout("window.location.reload()", 1000);
            }
        }
    });
  } 
});