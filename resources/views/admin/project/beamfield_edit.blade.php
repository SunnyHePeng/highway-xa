<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/beamfield')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>梁场名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入梁场名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>台座数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入台座数量" id="tz_num" name="tz_num">
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