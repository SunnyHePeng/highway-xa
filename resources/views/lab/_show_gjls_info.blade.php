@extends('admin.layouts.iframe')

@section('container')
@if(($user['role'] == 3 || $user['role'] == 4 || $user['role'] == 5) && $lab_info['is_warn'])
<div class="cl mb-10"> 
  <span class="l">
    <a class="btn btn-primary radius open-iframe" data-is-reload="1" data-title="处理意见" data-url="{{url('lab/deal/'.$lab_info['id'].'?d_id='.$lab_info['device_id'])}}" data-id="{{$lab_info['id']}}" href="javascript:;">报警处理</a>
  </span>
</div>
@endif
<div class="row cl hidden-xs">
  <div class="wl_info col-xs-12 col-sm-12" class="padding-left: 0;">
    <table class="table table-border table-bordered table-bg">
      <thead>
        <tr class="@if($lab_info['is_warn']) red-line @else blue-line @endif">
          <th class="text-r" width="100">报警状态</th>
          <td>@if($lab_info['is_warn']) 有 @else 无 @endif</td> 
          <th class="text-r" width="100">下屈服强度</th>
          <td>@if($lab_info['is_warn_para1']) 不合格 @else 合格 @endif</td>               
          <th class="text-r" width="100">抗拉强度</th>
          <td>@if($lab_info['is_warn_para2']) 不合格 @else 合格 @endif</td>
        </tr>
        <tr>
          <th class="text-r" width="100">驻地办</th>
          <td>{{$lab_info['sup']['name']}}</td> 
          <th class="text-r" width="100">合同段</th>
          <td>{{$lab_info['section']['name']}}</td>               
          <th class="text-r" width="100">试验类型</th>
          <td>
            @if(in_array($lab_info['sylx'], [1,2,3,4,5,6,7,8,9,10,11,12,13]))
            {{$symc[$lab_info['sylx']]}}
            @else
            {{$lab_info['sylx']}}
            @endif
          </td>
        </tr>
        <tr>
          <th class="text-r" width="100">试验机名称</th>
          <td>{{$lab_info['device']['model']}}</td> 
          <th class="text-r" width="100">设备编号</th>
          <td>{{$lab_info['device']['dcode']}}</td>               
          <th class="text-r" width="100">监理单位</th>
          <td>{{$lab_info['jldw']}}</td>
        </tr>

        <tr>
          <th class="text-r" width="100">委托单位</th>
          <td>{{$lab_info['wtdw']}}</td> 
          <th class="text-r" width="100">试验单位</th>
          <td>{{$lab_info['sydw']}}</td>               
          <th class="text-r" width="100">试验日期</th>
          <td>{{date('Y-m-d H:i', $lab_info['time'])}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">类别牌号</th>
          <td>{{$lab_info['lbph']}}</td>
          <th class="text-r" width="100">试验组号</th>
          <td colspan="3">{{$lab_info['syzh']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">试件个数</th>
          <td>{{$lab_info['sjgs']}}</td>
          <th class="text-r" width="100">试验人员</th>
          <td colspan="3">{{$lab_info['syry']}}</td>
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
          <td>@if($lab_info['is_warn']) 有 @else 无 @endif</td>
        </tr>
        <tr class="red-line"> 
          <th class="text-r" width="100">下屈服强度</th>
          <td>@if($lab_info['is_warn_para1']) 不合格 @else 合格 @endif</td>
        </tr>
        <tr class="red-line">               
          <th class="text-r" width="100">抗拉强度</th>
          <td>@if($lab_info['is_warn_para2']) 不合格 @else 合格 @endif</td>
        </tr>
        <tr>
          <th class="text-r" width="100">驻地办</th>
          <td>{{$lab_info['sup']['name']}}</td>
        </tr>
        <tr> 
          <th class="text-r" width="100">合同段</th>
          <td>{{$lab_info['section']['name']}}</td>
        </tr>
        <tr>               
          <th class="text-r" width="100">试验类型</th>
          <td>
            @if(in_array($lab_info['sylx'], [1,2,3,4,5,6,7,8,9,10]))
            {{$symc[$lab_info['sylx']]}}
            @else
            {{$lab_info['sylx']}}
            @endif
          </td>
        </tr>
        <tr>
          <th class="text-r" width="100">试验机名称</th>
          <td>{{$lab_info['device']['model']}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">设备编号</th>
          <td>{{$lab_info['device']['dcode']}}</td> 
        </tr>
        <tr>              
          <th class="text-r" width="100">监理单位</th>
          <td>{{$lab_info['jldw']}}</td>
        </tr>

        <tr>
          <th class="text-r" width="100">委托单位</th>
          <td>{{$lab_info['wtdw']}}</td> 
        </tr>
        <tr>
          <th class="text-r" width="100">试验单位</th>
          <td>{{$lab_info['sydw']}}</td>
        </tr>
        <tr>               
          <th class="text-r" width="100">试验日期</th>
          <td>{{date('Y-m-d H:i', $lab_info['time'])}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">类别牌号</th>
          <td>{{$lab_info['lbph']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">试验组号</th>
          <td colspan="3">{{$lab_info['syzh']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">试件个数</th>
          <td>{{$lab_info['sjgs']}}</td>
        </tr>
        <tr>
          <th class="text-r" width="100">试验人员</th>
          <td colspan="3">{{$lab_info['syry']}}</td>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="row cl">
  @if($lab_info['is_sup_deal'] || $lab_info['is_sec_deal'])
  @include('lab._deal_info')
  @endif
  <div class="mt-20 wl_info col-xs-12 col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-header">试件信息</div>
      <div class="panel-body" class="padding: 0;">
        <table class="table table-border table-bordered table-bg">
          <thead>
            <tr class="text-c">
              <th width="50">序号</th> 
              <th width="50">下屈服力值(KN)</th> 
              <th width="100">下屈服强度(MPa)</th>
              <th width="100">龄期</th>
              <th width="100">采集类型</th>
              <th width="100">报警状态</th>
              <th width="50">极限荷载(KN)</th> 
              <th width="100">极限强度(MPa)</th>
              <th width="100">报警状态</th>
            </tr>
          </thead>
          <tbody id="detail_info">
            @foreach($detail_info as $v)
            <tr class="text-c">
              <td>试件{{$v['type']}}</td>
              <td>{{$v['lz']}}</td>
              <td>{{$v['qd']}}</td>
              <td>
                @if(array_key_exists('lingqi',$v))
                  {{$v['lingqi']}}
                @endif
              </td>
              <td>
                @if(array_key_exists('type1',$v))
                  {{$v['type1']}}
                @endif
              </td>
              <td>
                @if(array_key_exists('is_qd_warn',$v))
                 @if($v['is_qd_warn']) 下屈服强度不合格 @else 下屈服强度合格 @endif
                @endif
              </td>
              <td>
                @if(array_key_exists('jxhz',$v))
                {{$v['jxhz']}}
                @endif
              </td>
              <td>
                @if(array_key_exists('jxqd',$v))
                {{$v['jxqd']}}
                @endif
              </td>
              <td>
                @if(array_key_exists('is_jxqd_warn',$v))
                @if($v['is_jxqd_warn']) 极限强度不合格 @else 极限强度合格 @endif
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-20 deal_info col-xs-12 col-sm-12">
    <div class="panel panel-primary">
      <div class="panel-header">试验图片</div>
      <div class="panel-body row cl" id="deal_info" style="min-height: 150px;">
        @foreach($detail_info as $v)
        @if($v['image'])
        <img class="col-xs-12 col-sm-3" width="100%" src="data:image/png;base64,{{$v['image']}}">
        @endif
        @endforeach
      </div>
    </div>
  </div>
</div>
@stop

@section('script')
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript" src="/static/admin/js/lab_info.js"></script>
<script type="text/javascript">

list.init();
</script>
@stop