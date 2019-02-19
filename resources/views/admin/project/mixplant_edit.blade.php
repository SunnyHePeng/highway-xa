<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/mixplant')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌合站名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入拌合站名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>数据库类型：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入数据库类型" id="database_type" name="database_type">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>采集状态：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入采集状态" id="status" name="status">
			</div>
		</div>
		<div class="row cl">							
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>生产能力：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入生产能力" id="product_rate" name="product_rate">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>生产厂家：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入生产厂家" id="factory" name="factory">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>公称容量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入公称容量" id="capacity" name="capacity">
			</div>
		</div>
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
		<input type="hidden" class="input-text" value="{{$search['project_id']}}" id="project_id" name="project_id">
		<input type="hidden" class="input-text" value="{{$search['sec_id']}}" id="section_id" name="section_id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>