<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/project')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="name" name="name" placeholder="请输入项目名称">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>总里程：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入总里程" id="mileage" name="mileage">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入监理数量" id="supervision_num" name="supervision_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入标段数量" id="section_num" name="section_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">项目概况：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<textarea name="summary" id="summary" cols="" rows="" class="textarea" placeholder="请输入项目概况"></textarea>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>