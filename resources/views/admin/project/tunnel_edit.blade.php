<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/tunnel')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>隧道名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入隧道名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>左洞起始桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入左洞起始桩号" id="left_begin_position" name="left_begin_position">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>左洞终止桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入左洞终止桩号" id="left_end_position" name="left_end_position">
			</div>
		</div>
		<div class="row cl">							
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>右洞起始桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入右洞起始桩号" id="right_begin_position" name="right_begin_position">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>右洞终止桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入右洞终止桩号" id="right_end_position" name="right_end_position">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>隧道长度：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入隧道长度" id="length" name="length">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>基站总数：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入隧道基站数" id="station_num" name="station_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>状态：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入状态" id="status" name="status">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" class="input-text" value="{{$search['project_id']}}" id="project_id" name="project_id">
		<input type="hidden" class="input-text" value="{{$search['sec_id']}}" id="section_id" name="section_id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>