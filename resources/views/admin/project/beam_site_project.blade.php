@extends('admin.layouts.tree')

@section('container')
@include('components.treeDataHiddenInput')
@if($user_act)
<div class="cl pd-5 bg-1 bk-gray mt-20">
	<span class="l">
		<a class="btn btn-primary radius add-r" data-for="project" data-title="添加梁场" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i>添加梁场</a>
	</span>
</div>
@endif
<div class="mt-10 dataTables_wrapper">
	<table id="table_list" class="table table-border table-bordered table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="50">序号</th>
				<th width="100">监理</th>
				<th width="100">合同段</th>
				<th width="100">梁场名称</th>
				<th width="100">面积</th>
				<th width="100">台座数量</th>
                <th width="100">喷淋控制设备数量</th>
				<th width="100">喷淋设备类型</th>
				<th width="100">负责人</th>
				<th width="100">联系方式</th>
				<th width="100">预制梁类型</th>
				<th width="100">预制梁数量</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
		@if($beam_site_data)
			@foreach($beam_site_data as$k=>$v)
				<tr class="text-c" data-id="{{$v['id']}}">
                   <td>{{$k+1}}</td>
					<td>{{$v['supervision']['name']}}</td>
					<td>{{$v['section']['name']}}</td>
					<td>{{$v['name']}}</td>
					<td>{{$v['area']}}</td>
					<td>{{$v['pedestal_number']}}</td>
					<td>{{$v['device_number']}}</td>
					<td>{{$v['device_type']}}</td>
					<td>{{$v['fzr']}}</td>
					<td>{{$v['phone']}}</td>
					<td>{{$v['beam_type']}}</td>
					<td>{{$v['beam_number']}}</td>
					<td>
                      @if($user_act)
							<a style="text-decoration:none" class="mt-5 beam_site_edit btn btn-secondary radius size-MINI" data-for="project" data-title="编辑梁场" data-url="{{url('manage/beam_site_edit?id='.$v['id'])}}" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
							<a style="text-decoration:none" class="mt-5 ml-5 beam_site_del btn btn-danger radius size-MINI" href="javascript:;" data-id="{{$v['id']}}" data-url="{{url('manage/beam_site_del/'.$v['id'])}}" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
						@endif
					</td>
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>

</div>
@stop

@section('layer')
@include('admin.project.beam_site_add')
@stop

@section('script')
	<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/lib/zTree/jquery.ztree.all-3.5.min.js"></script>
	<script type="text/javascript" src="/static/admin/js/common.js"></script>
	<script type="text/javascript" src="/static/admin/js/ztree_page.js"></script>
<script type="text/javascript">
list.init();
$("#project_id").on('change',function(){
     var project_id=$(this).val();
     var url=$(this).attr('data-url');

     $.get(url+'?pro_id='+project_id,function(data){
          if(data.status==1){
              layer.alert(data.mess);
		  }
		  if(data.status==0){
              var htmlstr='<option>请选择监理</option>';
              console.dir(data.mess);
              for(var i=0;i<data.mess.length;i++){
                  htmlstr+="<option value=\""+data.mess[i].id+"\">"+data.mess[i].name+"</option>";
			  }
			  $("#supervision_id").empty().append(htmlstr);
		  }
	 });

});
$("#supervision_id").on('change',function(){
    var supervision_id=$(this).val();
    var url=$(this).attr("data-url");

    $.get(url+'?sup_id='+supervision_id,function(data){
              if(data.status==0){
                  var htmlstr='<option>请选择合同段</option>';
                  htmlstr+="<option value=\""+data.mess.id+"\">"+data.mess.name+"</option>";
                  $("#section_id").empty().append(htmlstr);
			  }
    });
});
$(".beam_site_edit").on('click',function(){
//    alert(0);
   var url=$(this).attr("data-url");
   var title=$(this).attr("data-title");

    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        shade: 0.3,
        area: ['30%', '60%'],
        content: url
    });


});
$(".beam_site_del").on('click',function(){

    var url=$(this).attr('data-url');
    layer.confirm('确定要删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.get(url,function(data){
            if(data.status==1){
              layer.alert(data.info);
              var id='data-id='+data.id;

              $("tr ["+id+"]").parent().parent().remove();
			}
			if(data.status==0){
                layer.alert(data.info);
			}

		});

    }, function(){

    });
});
</script>
@stop