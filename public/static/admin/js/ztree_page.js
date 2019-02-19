
var setting = {
	view: {
		dblClickExpand: false,
		showLine: true,
		selectedMulti: false
	},
	data: {
		simpleData: {
			enable:false,
			idKey: "id",
			pIdKey: "pId",
			rootPId: ""
		}
	},
	callback: {
		beforeClick: function(treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("tree");
			/*if (treeNode.isParent) {
				zTree.expandNode(treeNode);
				return false;
			} else {*/
				//获取对应标段信息
				var key = treeNode.key ? treeNode.key : 'pro_id';
				window.location.href = $('#tree_url').val() + "?"+key+"="+treeNode.id;
				//$('#showiframe').attr("src",$('#tree_url').val() + "?pro_id="+treeNode.id);
				/*$.ajax({
					url: $('#tree_url').val()+'/'+treeNode.id,
			        type: 'GET',
			        data: '',
			        dataType: 'json',
			        success:function(msg){
			          	if(msg.status){
			          		setInfo(msg.data);
			          	}else{
			          		common.alert(msg.info);
			          	}
			        },
			        error: function(){
			        	common.alert('提交出错...');
			        }
				})*/
				
				return true;
			//}
		}
	}
};

var zNodes = eval('('+ $('#tree_data').val() +')');

$(document).ready(function(){
	var t = $("#treeDemo");
	t = $.fn.zTree.init(t, setting, zNodes);
	var zTree = $.fn.zTree.getZTreeObj("treeDemo");
	zTree.selectNode(zTree.getNodeByParam("name", $('#tree_name').val()));
	//setInfo(eval('('+ $('#tree_info').val() +')'));
});

function setInfo(data){
	var str = '';
	if(data){
		str = getPageInfo(data);
	}
	$('.show-sec').html(str);
}

function getPageInfo(data){
	var page = $('#tree_page').val();
	var str = '';
	var url = $('.act-span').attr('data-url');
	$('.edit-r').attr('data-url', url+'/'+data.id+'/edit');
	$('.del-r').attr('data-url', url+'/'+data.id);
	if(page == 'section'){
		str  = '<p><strong>标段名称:</strong> '+data.name+'</p>'
			 + '<p><strong>项目名称:</strong> '+data.project['name']+'</p>'
			 + '<p><strong>监理名称:</strong> '+data.sup[0]['name']+'</p>'
			 + '<p><strong>承包商名称:</strong> '+data.cbs_name+'</p>'
			 + '<p><strong>起始桩号:</strong> '+data.begin_position+'</p>'
			 + '<p><strong>终止桩号:</strong> '+data.end_position+'</p>'
			 + '<p><strong>负责人:</strong> '+data.fzr+'</p>'
			 + '<p><strong>联系方式:</strong> '+data.phone+'</p>'
			 + '<p><strong>登记时间:</strong> '+data.created_at+'</p>';
	}
	if(page == 'supervision'){
		url = $('.act-span').attr('data-set-url');
		$('.open-iframe').attr('data-url', url+'?sup_id='+data.id+'&pro_id='+data.project['id']);
		str  = '<p><strong>监理名称:</strong> '+data.name+'</p>'
			 + '<p><strong>项目名称:</strong> '+data.project['name']+'</p>'
			 + '<p><strong>监理类型:</strong> '+data.type+'</p>'
			 + '<p><strong>所属单位:</strong> '+data.company+'</p>'
			 + '<p><strong>负责人:</strong> '+data.fzr+'</p>'
			 + '<p><strong>联系方式:</strong> '+data.phone+'</p>'
	}

	return str;
}