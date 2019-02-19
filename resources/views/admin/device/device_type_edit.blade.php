<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/dev_type')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属分类：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="category_name" name="category_name" placeholder="请先选择所属分类">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>类型名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="name" name="name" placeholder="请输入类型名称">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" class="msg_must" value="" id="category_id" name="category_id" placeholder="请先选择所属分类">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>