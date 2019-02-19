$('.edit-r-one').on('click',function(event){
  event.stopPropagation();

  var title = $(this).attr('data-title');
  var url = $(this).attr('data-url');
  var obj = $('form#form_container'); 
  var id = $(this).attr('data-id');
  openlayerForInfo(title, obj, id, url);
});

$('.show-report').on('click',function(event){
  event.stopPropagation();
});

function openlayerForInfo(title, obj, id, url){
  layer.open({
    type: 1,
    title: title,
    closeBtn: 1,
    shadeClose: true,
    skin: common.layer.skin,
    area: [500,300],
    maxmin: true,
    content: $('#layer-edit'),
    btn: ['提交', '取消'],
    success: function(){
      obj.find('input[name="id"]').val(id);
    },
    yes: function(index, layero){
      if(list.is_click){
        list.is_click = false;
        var data = {'data': id,'info': obj.find('#info').val()};
        commit(data, url, index);
        obj[0].reset();
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
}

function commit(data, url, index){
  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    dataType: 'json',
    success:function(msg){
        if(msg.status){
          common.alert(msg.info);
          window.location.reload();
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
  layer.close(index);
}