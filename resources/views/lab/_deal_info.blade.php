<div class="mt-20 col-xs-12 col-sm-12">
	<div class="panel panel-primary">
	  <div class="panel-header">处理意见</div>
	  <div class="panel-body row cl" id="deal_info" style="min-height: 150px;">
	    @if($lab_info['is_warn'])
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

	        @if($deal_info['pro_time'])
	          <p>(建设单位)处理人：{{$deal_info['pro_name']}}</p>
	          <p>(建设单位)处理意见：{{$deal_info['pro_info']}}
	          @if($deal_info['pro_img'])
	            <div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['pro_img']}}" target="_blank"><img width="150px" src="{{Config()->get('common.show_path').$deal_info['pro_img']}}"></a></div>
	          @endif
	          @if($deal_info['pro_file'])
	            <div style="margin-left: 50px;"><a href="{{Config()->get('common.show_path').$deal_info['pro_file']}}" target="_blank">查看处理文档</a></div>
	          @endif
	          </p>
	          <p>(建设单位)处理时间：{{date('Y-m-d H:i:s', $deal_info['pro_time'])}}</p>
	        @elseif($lab_info['warn_level'] == 3 || $lab_info['warn_sx_level'] == 3)
	          <p>(建设单位)处理人：</p>
	          <p>(建设单位)处理意见：</p>
	          <p>(建设单位)处理时间：</p>
	        @endif
	      @endif
	  </div>
	</div>
</div>