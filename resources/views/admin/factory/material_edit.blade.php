<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/material')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>材料名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="name" name="name" placeholder="请输入材料名称">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属分类：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入所属分类" id="type" name="type">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设计配合比：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入设计配合比" id="dasign_rate" name="dasign_rate">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>报警比例：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入报警比例" id="warn_rate" name="warn_rate">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">备注：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<textarea name="note" id="note" cols="" rows="" class="textarea" placeholder="请输入备注"></textarea>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>