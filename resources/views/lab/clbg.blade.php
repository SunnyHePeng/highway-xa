@extends('admin.layouts.iframe')

@section('container')
<style type="text/css">
th, td { font-size: 14px; height: 40px;}
</style>
<style type="text/css" media="print">
.noprint{display : none}
</style>
<article class="page-container" id="show_detail" style="width:780px; margin: 0 auto;">
	<div class="cl pd-5 bg-1 bk-gray noprint"> 
	  <span onclick="javascript:window.print();" class="ml-10 export-file export-dy" data-name="生产统计报表" data-obj="table_list" data-type="dy"><a href="javascript:;" title="打印文件"><img src="/static/admin/images/dayin.svg"></a></span>
	</div>
	<div class="row cl">
	  <div class="col-xs-12 col-sm-12" class="padding-left: 0;">
	  	<h1 class="text-c">{{$project['name']}}</h1>
	  	<h2 class="text-c">试验室信息化不合格处理记录表</h2>
	  	<p class="f-r">编号：第{{$section['name']}}-{{date('YmdHis', $time)}}号</p>
	  	<table class="table table-border table-bordered table-bg">
          <tdead>
            <tr>
              <td width="120">单位名称</td> 
              <td>{{$sydw}}</td>
            </tr>
            <tr>
              <td width="120">设备名称</td>
              <td>{{$device['name']}}</td> 
            </tr>
            <tr>              
              <td width="120">试验日期</td>
              <td>{{date('Y-m-d H:i:s', $time)}}</td>
            </tr>
            <tr>              
              <td width="120">试验名称</td>
              <td>
                @if(in_array($sylx,[1,2,3,4,5,6,7,8,9,10]))
                {{$symc[$sylx]}}
                @else
                {{$sylx}}
                @endif
              </td>
            </tr>
            <tr>
              <td width="120">使用部位(工程名称)</td>
              <td>{{$project['name']}} {{$section['name']}}</td>
            </tr>
            <tr height="100px">
              <td width="120">不合格情况</td>
              <td>报警级别：
              	@if($warn_sx_level && $warn_sx_level > $warn_level)
        					{{$level[$warn_sx_level]}}({{$warn_sx_info}})
        				@else
        					高级
        				@endif
        				<br>
        				报警信息：{{$warn_info}}
			         </td>
            </tr>
            <tr height="150px">
              <td width="120">施工单位原因分析及处理结果</td>
              <td>{{$sec_info}}</td>
            </tr>
            <tr height="150px">
              <td width="120">监理单位原因分析及处理结果</td>
              <td>{{$sup_info}}</td>
            </tr>

          </tdead>
        </table>
        <div class="col-xs-4 col-sm-4" class="padding-left: 0;">信息员:{{$syry}}</div>
        <div class="col-xs-4 col-sm-4" class="padding-left: 0;">试验负责人:{{$sec_name}}</div>
        <div class="col-xs-4 col-sm-4" class="padding-left: 0;">监理:{{$sup_name}}</div>
	  </div>
	</div>
</article>
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
@stop