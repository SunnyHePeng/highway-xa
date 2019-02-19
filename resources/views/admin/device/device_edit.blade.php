<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/device')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="project_id" id="project_id" class="select msg_must" placeholder="请选择项目名称" data-url="{{url('manage/get_sup')}}">
		                <option value="">请选择项目</option>
		                @if(isset($project))
		                @foreach($project as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="supervision_id" id="supervision_id" class="select msg_must" placeholder="请选择监理名称" data-url="{{url('manage/get_sec')}}">
		                <option value="0">请选择监理</option>
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="section_id" id="section_id" class="select msg_must" placeholder="请选择标段名称">
		                <option value="0">请选择标段</option>
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入设备名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备编码：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入设备编码" id="dcode" name="dcode">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备型号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入设备型号" id="model" name="model">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>生产厂家：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入生产厂家" id="factory_name" name="factory_name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>出厂日期：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入出厂日期" id="factory_date" name="factory_date" onfocus="WdatePicker()">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">摄像头1编号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text" value="" placeholder="请输入设备对应的摄像头1编号" id="camera1" name="camera1">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">摄像头1对应编码设备uuid：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text" value="" placeholder="请输入摄像头1对应编码设备uuid" id="camera1_encoderuuid" name="camera1_encoderuuid">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">摄像头2编号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text" value="" placeholder="请输入设备对应的摄像头2编号" id="camera2" name="camera2">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">摄像头2对应编码设备uuid：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text" value="" placeholder="请输入摄像头2对应编码设备uuid" id="camera2_encoderuuid" name="camera2_encoderuuid">
			</div>
		</div>

		@foreach($para as $k=>$v)
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>{{$v}}：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入{{$v}}" id="parame{{$k+1}}" name="parame{{$k+1}}">
			</div>
		</div>
		@endforeach
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>负责人：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入负责人" id="fzr" name="fzr">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入联系方式" id="phone" name="phone">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" class="input-text msg_must" id="cat_id" name="cat_id" placeholder="请先选择设备分类" value="{{$cat_id}}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>