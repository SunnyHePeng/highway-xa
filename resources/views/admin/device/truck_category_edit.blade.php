<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/truck_category')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>分类名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="name" name="name" placeholder="请输入分类名称">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>