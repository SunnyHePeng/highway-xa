var host = 'http://'+window.location.host;
var img_show = './uploads/';

$('input[name="del"]').on('click',function(event){
  event.stopPropagation(); 
});

$('.edit-r-more').on('click',function(){
  var title = $(this).attr('data-title');
  var url = $(this).attr('data-url');
  var obj = $('form#form_container'); 

  var chk_value = []; 
  $('input[name="del"]:checked').each(function(){ 
    chk_value.push($(this).val()); 
  }); 
  if(chk_value.length < 1){
    common.alert('请选择处理的内容');
    return false;
  }
  var yes = function(index){
        layer.close(index);
        openlayerForInfo(title, obj, chk_value, url);
      }

  common.confirm('确定批处理吗？', yes, '');
});

$('.edit-r-one').on('click',function(event){
  event.stopPropagation();

  var title = $(this).attr('data-title');
  var url = $(this).attr('data-url');
  var obj = $('form#form_container'); 
  var id = $(this).attr('data-id');
  openlayerForInfo(title, obj, id, url);
});

$('.show-line').on('click', function(){
  var id = $(this).attr('data-id');
  //var pbid = $(this).attr('data-pb');
  var is_warn = $(this).attr('data-warn');
  var url = $('#table_list').attr('data-url');
  if(!id || is_warn == 'undefind'){
    common.alert('获取信息错误...');
    return false;
  }
  //加载中
  var index = layer.load(2, {time: 30*1000}); //又换了种风格，并且设定最长等待10秒 
       
  if(id){
    $.ajax({
      url: url,
      type: 'GET',
      data: {'id': id, 'is_warn': is_warn},
      dataType: 'json',
      success:function(msg){
          if(msg.status && msg.data.detail_info){
             showDetailInfo(msg.data, index, is_warn);
          }else{
            layer.close(index);
            common.alert('获取信息错误...');
          }
      },
      error: function(){
        layer.close(index);
        common.alert('获取信息错误...');
      }
    });
  }
});

function openlayerForInfo(title, obj, id, url){
  layer.open({
    type: 1,
    title: title,
    closeBtn: 1,
    shadeClose: true,
    skin: common.layer.skin,
    area: ['600px','350px'],
    maxmin: true,
    content: $('#layer-edit'),
    btn: ['提交', '取消'],
    success: function(){
      obj.find('input[name="id"]').val(id);
    },
    yes: function(index, layero){
      if(list.is_click){
        list.is_click = false;
        var data = obj.serialize();
        commit(data, url, index, obj);
      }else{
        common.alert('正在提交...');
      } 
    },
    btn2: function(){
      obj[0].reset();
      obj.find('input[name="id"]').val('');
      obj.find('#imgList').html('');
      obj.find('#imgShow').html('');
      obj.find('#fileList').html('');
      obj.find('#fileShow').html('');
    },
    cancel: function(){
      obj[0].reset();
      obj.find('input[name="id"]').val('');
      obj.find('#imgList').html('');
      obj.find('#imgShow').html('');
      obj.find('#fileList').html('');
      obj.find('#fileShow').html('');
    }
  });
}

function commit(data, url, index, obj){
  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: 'json',
    success:function(msg){
        if(msg.status){
          if(msg.id){
            $('#list-'+msg.id).find('input[name="del"]').remove();
            $('#list-'+msg.id).find('td.td-manage').html('');
          }
          if(msg.data){
            for(var i in msg.data){
              $('#list-'+msg.data[i]).find('input[name="del"]').remove();
              $('#list-'+msg.data[i]).find('td.td-manage').html('');
            }
          }
          obj[0].reset();
          obj.find('input[name="id"]').val('');
          obj.find('#imgList').html('');
          obj.find('#imgShow').html('');
          obj.find('#fileList').html('');
          obj.find('#fileShow').html('');
          common.alert(msg.info);
          layer.close(index); 
        }else{
          common.alert(msg.info);
        }
        list.is_click = true;
    },
    error: function(){
      list.is_click = true;
      common.alert('处理出错...');
    }
  });
}

function showDetailInfo(data, index, is_warn){
  //拌和站基本信息
  var snbhz_info = '暂无信息';
  if(data.snbhz_info){
    snbhz_info = '<tr>'
               + '  <td>'+ data.snbhz_info['project']['name'] +'</td>'
               + '  <td>'+data.snbhz_info['time']+'</td>'
               + '  <td>'+data.snbhz_info['scdw']+'</td>'
               + '  <td>'+data.snbhz_info['sgdd']+'</td>'
               + '  <td>'+data.snbhz_info['jzbw']+'</td>'
               + '  <td>'+data.snbhz_info['pbbh']+'</td>'
               + '  <td>'+data.snbhz_info['pfl']+'</td>'
               + '  <td>'+data.snbhz_info['operator']+'</td>'
               + '  <td>'+data.snbhz_info['cph']+'</td>'
               + '  <td>'+data.snbhz_info['driver']+'</td>'
               + '</tr>';
  }
  //物料信息
  var detail_info = '暂无信息';
  if(data.detail_info){
    for(var i in data.detail_info){
      cl = '';
      if(Math.abs(data.detail_info[i]['pcl']) > snbhz_detail[parseInt(i)+1]['pcl']){
        cl = 'red-line';
      }
      detail_info += '<tr class="text-c '+cl+'">'
                   + '  <td>'+ data.detail_info[i]['type'] +'</td>'
                   + '  <td>'+snbhz_detail[parseInt(i)+1]['name']+'</td>'
                   + '  <td>'+data.detail_info[i]['design']+'</td>'
                   + '  <td>'+data.detail_info[i]['fact']+'</td>'
                   + '  <td>'+data.detail_info[i]['pcl']+'</td>'
                   + '</tr>';
    }
  }
  //处理信息
  var deal_info = '';
  if(data.deal_info){
    if(data.deal_info.sec_time){
      deal_info = '<p>(标段)处理人：'+data.deal_info.sec_name 
              + '</p><p>(标段)处理意见：'+data.deal_info.sec_info;
      if(data.deal_info.sec_img){
        var src = host+img_show+data.deal_info.sec_img;
        deal_info += '<div style="margin-left: 50px;"><a href="'+src+'" target="_blank"><img width="200px" src="'+src+'"></a></div>'; 
      }
      if(data.deal_info.sec_file){
        var src = host+img_show+data.deal_info.sec_file;
        deal_info += '<div style="margin-left: 50px;"><a href="'+src+'" target="_blank">查看处理文档</a></div>'; 
      }
      deal_info += '</p><p>(标段)处理时间：'+data.deal_info.sec_time
                 +'</p>';
    }else{
      deal_info = '<p>(标段)处理人：'
              + '</p><p>(标段)处理意见：'
              + '</p><p>(标段)处理时间：'
              +'</p>';
    }

    if(data.deal_info.sup_time){
      deal_info += '<p>(监理)处理人：'+data.deal_info.sup_name
                + '</p><p>(监理)处理意见：'+data.deal_info.sup_info;
      if(data.deal_info.sup_img){
        var src = host+img_show+data.deal_info.sup_img;
        deal_info += '<div style="margin-left: 50px;"><a href="'+src+'" target="_blank"><img width="200px" src="'+src+'"></a></div>'; 
      }
      if(data.deal_info.sup_file){
        var src = host+img_show+data.deal_info.sup_file;
        deal_info += '<div style="margin-left: 50px;"><a href="'+src+'" target="_blank">查看处理文档</a></div>'; 
      }
      deal_info += '</p><p>(监理)处理时间：'+data.deal_info.sup_time
                 +'</p>';
    }else{
      deal_info += '<p>(监理)处理人：'
                + '</p><p>(监理)处理意见：'
                + '</p><p>(监理)处理时间：'
                +'</p>';
    }
  }

  layer.close(index);

  layer.open({
    type: 1,
    title: '记录详细信息',
    closeBtn: 1,
    shadeClose: true,
    skin: common.layer.skin,
    area: ['80%','80%'],
    maxmin: true,
    content: $('#show_detail'),
    success: function(){
      $('#show_detail').find('#snbhz_info').html(snbhz_info);
      $('#show_detail').find('#detail_info').html(detail_info);
      $('#show_detail').find('#deal_info').html(deal_info);
    }
  });
}