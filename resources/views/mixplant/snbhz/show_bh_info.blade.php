@extends('admin.layouts.iframe')

@section('container')
<article class="page-container" id="show_detail">
	@if(($user['role'] == 3 || $user['role'] == 4 || $user['role'] == 5) && $snbhz_info['is_warn'])
	<div class="cl mb-10"> 
	  <span class="l">
	    <a style="text-decoration:none" data-for='module' class="ml-5 open-iframe btn btn-secondary radius size-MINI" href="javascript:;" data-id="{{$snbhz_info['id']}}" data-url="{{url('snbhz/deal/'.$snbhz_info['id'].'?d_id='.$snbhz_info['device_id'])}}" data-is-reload="1" data-title="处理意见" title="处理">报警处理</a>
      </span>
	</div>
	@endif
	<div class="row cl hidden-xs">
	  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
	  	<table class="table table-border table-bordered table-bg">
          <thead>
            <tr class="text-c">
              <th width="100">工程名称</th> 
              <th width="100">生产时间</th>               
              <th width="100">生产单位</th>
              <th width="100">施工地点</th>
              <th width="100">浇筑部位</th>
              <th width="100">强度等级</th>
              <th width="100">盘方量(方)</th>
              <th width="100">拌和时长(秒)</th>
              <th width="100">操作员</th>
            </tr>
          </thead>
          <tbody id="snbhz_info">
          	<tr>
            	<td>{{$snbhz_info['project']['name']}}</td>
               	<td>{{$snbhz_info['time']}}</td>
               	<td>{{$snbhz_info['scdw']}}</td>
               	<td>{{$snbhz_info['sgdd']}}</td>
               	<td>{{$snbhz_info['jzbw']}}</td>
               	<td>{{$snbhz_info['pbbh']}}</td>
               	<td>{{$snbhz_info['pfl']}}</td>
               	<td>{{$snbhz_info['jssj'] - $snbhz_info['kssj']}}</td>
               	<td>{{$snbhz_info['operator']}}</td>
            </tr>
          </tbody>
        </table>
	  </div>
	</div>
	<div class="row cl visible-xs">
	  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
	  	<table class="table table-border table-bordered table-bg">
          <thead>
            <tr class="text-c">
              <th width="100">工程名称</th> 
              <td>{{$snbhz_info['project']['name']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">生产时间</th>
              <td>{{$snbhz_info['time']}}</td> 
            </tr>
            <tr class="text-c">              
              <th width="100">生产单位</th>
              <td>{{$snbhz_info['scdw']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">施工地点</th>
              <td>{{$snbhz_info['sgdd']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">浇筑部位</th>
              <td>{{$snbhz_info['jzbw']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">配比编号</th>
              <td>{{$snbhz_info['pbbh']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">盘方量(方)</th>
              <td>{{$snbhz_info['pfl']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">拌和时长(秒)</th>
              <td>{{$snbhz_info['jssj'] - $snbhz_info['kssj']}}</td>
            </tr>
            <tr class="text-c">
              <th width="100">操作员</th>
              <td>{{$snbhz_info['operator']}}</td>
            </tr>
          </thead>
        </table>
	  </div>
	</div> 	
	<div class="row cl mt-20">
	  <div class="wl_info col-xs-12 col-sm-8" class="padding-left: 0;">
	    <div class="panel panel-primary">
	      <div class="panel-header">物料信息</div>
	      <div class="panel-body" class="padding: 0;">
	        <table class="table table-border table-bordered table-bg">
	          <thead>
	            <tr class="text-c">
	              <th width="50">序号</th>                
	              <th width="100">物料名称</th>
	              <th width="100">设计量KG</th>
	              <th width="100">投放量KG</th>
	              <th width="100">偏差率%</th>
	            </tr>
	          </thead>
	          <tbody id="detail_info">
	          	@foreach($detail_info as $key=>$value)
	          	@if($key < 9)
			      @if(abs($value['pcl']) > $snbhz_detail[$value['type']]['pcl'])
			        <?php $cl = 'red-line';?>
			      @else
			      	<?php $cl = '';?>
			      @endif
    			<tr class="text-c {{$cl}}">
                   	<td>{{$value['type']}}</td>
                   	<td>{{$snbhz_detail[$value['type']]['name']}}</td>
                   	<td>{{$value['design']}}</td>
                   	<td>{{$value['fact']}}</td>
                  	<td>{{$value['pcl']}}</td>
                </tr>
                @endif
                @endforeach
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>

	  <div class="deal_info col-xs-12 col-sm-4" class="padding-right: 0;">
	    <div class="panel panel-primary">
	      <div class="panel-header">处理信息</div>
	      <div class="panel-body" id="deal_info" style="min-height: 150px;">
	      	@if($snbhz_info['is_warn'])
	      		@if($deal_info['sec_time'])
	      			<p>(标段)处理人：{{$deal_info['sec_name']}}</p>
	      			<p>(标段)处理意见：{{$deal_info['sec_info']}}
	      			@if($deal_info['sec_img'])
	      				<div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['sec_img']}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$deal_info['sec_img']}}"></a></div>
	      			@endif
	      			@if($deal_info['sec_file'])
	      				<div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['sec_file']}}" target="_blank">查看处理文档</a></div>
	      			@endif
	      			</p>
	      			<p>(标段)处理时间：{{date('Y-m-d H:i:s', $deal_info['sec_time'])}}</p>
	      		@else
	      			<p>(标段)处理人：</p>
	      			<p>(标段)处理意见：</p>
	      			<p>(标段)处理时间：</p>
	      		@endif

	      		@if($deal_info['sup_time'])
	      			<p>(监理)处理人：{{$deal_info['sup_name']}}</p>
	      			<p>(监理)处理意见：{{$deal_info['sup_info']}}
	      			@if($deal_info['sup_img'])
	      				<div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['sup_img']}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$deal_info['sup_img']}}"></a></div>
	      			@endif
	      			@if($deal_info['sup_file'])
	      				<div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['sup_file']}}" target="_blank">查看处理文档</a></div>
	      			@endif
	      			</p>
	      			<p>(监理)处理时间：{{date('Y-m-d H:i:s', $deal_info['sup_time'])}}</p>
	      		@else
	      			<p>(监理)处理人：</p>
	      			<p>(监理)处理意见：</p>
	      			<p>(监理)处理时间：</p>
	      		@endif

	      	@endif
	      </div>
	    </div>
	  </div>
	</div>
</article>
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/lib/plupload/2.3.1/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/static/admin/js/upload.js"></script> 
<script type="text/javascript">
list.init();

var snbhz_detail = eval('('+$('#snbhz_detail').val()+')');

uploadFile('imgPicker', '{{url('snbhz/file/upload')}}', 'uploadimg', 'imgList', 'imgShow', {name:'thumb',type:'images'}, 'thumb')
uploadFile('filePicker', '{{url('snbhz/file/upload')}}', 'uploadfiles', 'fileList', 'fileShow', {name:'file',type:'file'}, 'file')
</script>
<script type="text/javascript" src="/static/admin/js/snbhz_info.js"></script>
@stop