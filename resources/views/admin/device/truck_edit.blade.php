<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/truck')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="project_name" name="project_name" placeholder="请先选择项目名称">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="section_id" id="section_id" class="select msg_must" placeholder="请选择标段名称" data-url="{{url('manage/get_device_type')}}">
		                @if(isset($section))
		                @foreach($section as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>分组编号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入分组编号" id="group_code" name="group_code">
			</div>
		</div>
		<div class="row cl" id="show-cjd">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>车牌号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入车牌号" id="truck_num" name="truck_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>车辆类型：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="truck_type" id="truck_type" class="select msg_must" placeholder="请选择车辆类型">
		                @if(isset($category))
		                @foreach($category as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl" id="show-type">							
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属单位：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入所属单位" id="unit_name" name="unit_name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>司机名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入司机名称" id="driver_name" name="driver_name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系电话：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入联系电话" id="phone" name="phone">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>模拟1：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="by1_select" id="by1_select" class="select" placeholder="请选择模拟1">
		                <option value="">请选择</option>
		                @if($select)
		                @foreach($select as $k=>$v)
		                <option value="{{$k}}">{{$v}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>模拟2：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="by2_select" id="by2_select" class="select" placeholder="请选择模拟2">
		                <option value="">请选择</option>
		                @if($select)
		                @foreach($select as $k=>$v)
		                <option value="{{$k}}">{{$v}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>模拟3：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="by3_select" id="by3_select" class="select" placeholder="请选择模拟3">
		                <option value="">请选择</option>
		                @if($select)
		                @foreach($select as $k=>$v)
		                <option value="{{$k}}">{{$v}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>模拟4：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="by4_select" id="by4_select" class="select" placeholder="请选择模拟4">
		                <option value="">请选择</option>
		                @if($select)
		                @foreach($select as $k=>$v)
		                <option value="{{$k}}">{{$v}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>出厂编号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入出厂编号" id="factory_code" name="factory_code">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" class="input-text msg_must" id="project_id" name="project_id" placeholder="请先选择项目名称">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>