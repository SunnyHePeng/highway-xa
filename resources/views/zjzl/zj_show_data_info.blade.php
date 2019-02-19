@extends('admin.layouts.iframe')

@section('container')
@if(($user['role'] == 3 || $user['role'] == 4 || $user['role'] == 5) && $info['is_warn'] == 1)
<div class="cl mb-10"> 
  <span class="l">
    <a class="btn btn-primary radius edit-r-one" data-title="处理意见" data-url="{{url('zlyj/deal/'.$info['id'].'?cat_id='.$info['device_cat'])}}" data-id="{{$info['id']}}" href="javascript:;">报警处理</a>
  </span>
</div>
@endif
<div class="row cl hidden-xs">
  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
    <table class="table table-border table-bordered table-bg">
      <thead>
        <tr class="@if($info['is_warn']) red-line @else blue-line @endif">
          <th class="text-r" width="100">报警状态</th>
          <td>@if($info['is_warn']) 有 @else 无 @endif</td> 
          <th class="text-r" width="100">报警信息</th>
          <td>{{$info['warn_info']}}</td>
        </tr>
        @if($info['is_sup_deal'] || $info['is_sec_deal'])
        <tr>
          <th class="text-r" width="100">处理意见</th>
          <td colspan="5">
            监理处理意见：{{$deal_info['sup_info']}}<br/>
            标段处理意见：{{$deal_info['sec_info']}}
          </td>
        </tr>
        @endif
        <tr>
          <th class="text-r" width="100">项目名称</th>
          <td>{{$info['project']['name']}}</td> 
          <th class="text-r" width="100">合同段</th>
          <td>{{$info['section']['name']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">监理单位</th>
          <td>{{$info['jldw']}}</td>            
          <th class="text-r" width="100">张拉单位</th>
          <td>{{$info['zldw']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">张拉日期</th>
          <td>{{date('Y-m-d H:i:d', $info['time'])}}</td> 
          <th class="text-r" width="100">操作人员</th>
          <td>{{$info['czry']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">砼设计值</th>
          <td>{{$info['tsjz']}}</td> 
          <th class="text-r" width="100">砼强度</th>
          <td>{{$info['tqd']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">张拉梁号</th>
          <td>{{$info['zllh']}}</td> 
          <th class="text-r" width="100">预制梁场</th>
          <td>{{$info['yzlc']}}</td>
        </tr>
      </thead>
    </table>
  </div>
</div> 

<div class="row cl visible-xs">
  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
    <table class="table table-border table-bordered table-bg">
      <thead>
        <tr class="red-line">
          <th class="text-r" width="100">报警状态</th>
          <td>@if($info['is_warn']) 有 @else 无 @endif</td> 
        </tr>
        <tr class="red-line">
          <th class="text-r" width="100">报警信息</th>
          <td>{{$info['warn_info']}}</td>
        </tr>
        @if($info['is_sup_deal'] || $info['is_sec_deal'])
        <tr>
          <th class="text-r" width="100">处理意见</th>
          <td colspan="5">
            监理处理意见：{{$deal_info['sup_info']}}<br/>
            标段处理意见：{{$deal_info['sec_info']}}
          </td>
        </tr>
        @endif
        <tr>
          <th class="text-r" width="100">项目名称</th>
          <td>{{$info['project']['name']}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">合同段</th>
          <td>{{$info['section']['name']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">监理单位</th>
          <td>{{$info['jldw']}}</td>
		</tr>
        <tr>            
          <th class="text-r" width="100">张拉单位</th>
          <td>{{$info['zldw']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">张拉日期</th>
          <td>{{date('Y-m-d H:i:d', $info['time'])}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">操作人员</th>
          <td>{{$info['czry']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">砼设计值</th>
          <td>{{$info['tsjz']}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">砼强度</th>
          <td>{{$info['tqd']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">张拉梁号</th>
          <td>{{$info['zllh']}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">预制梁场</th>
          <td>{{$info['yzlc']}}</td>
        </tr>
      </thead>
    </table>
  </div>
</div> 

<div class="row cl mt-20">
  <div class="wl_info col-xs-12 col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-header">具体信息</div>
      <div class="panel-body" class="padding: 0;">
        <table class="table table-border table-bordered table-bg">
          <thead>
            <tr class="text-c">
              <th width="50">钢束编号</th>  
              <th width="100">张拉方式</th>
              <th width="100">孔道名称</th>
              <th width="100">张拉断面</th>
              <th width="100">记录项目</th>
              <th width="100">初始行程(10%)</th>
              <th width="100">第一行程(20%)</th>
              <th width="100">第二行程(50%)</th>
              <th width="100">第三行程(50%)</th>
              <th width="100">第四行程(100%)</th>
              <th width="100">设计张力</th>
              <th width="100">回缩值1</th>
              <th width="100">回缩值2</th>
              <th width="100">张拉顺序</th>
              <th width="100">张拉比例</th>
              <th width="100">设计伸长量</th>
              <th width="100">实际伸长量</th>
              <th width="100">延伸量误差(%)</th>
            </tr>
          </thead>
          <tbody id="detail_info">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@stop

@section('layer')
@include('zlyj.deal_info')
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
list.init();
</script>
@stop