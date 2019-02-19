<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/module')}}">
		<div class="row cl show-all hidden-pass">
	      <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>所属模块：</label>
	      <div class="formControls col-xs-8 col-sm-8">
	        <span class="select-box">
	          <select class="select" id="pid" name="pid" placeholder="请选择模块">
	            <option value="0">请选择</option>
	            @foreach($data as $key=>$val )
	            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
	            @endforeach
	          </select>
	        </span>
	      </div>
	    </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>模块名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入模块名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>URL：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入URL" id="url" name="url">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>ICON名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入ICON名称" id="icon" name="icon">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>排序：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="0" placeholder="请输入排序" id="sort" name="sort">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新窗口打开：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
		          <select class="select" id="is_new" name="is_new" placeholder="请选择是否新窗口打开">
		            <option value="0">否</option>
		            <option value="1">是</option>
		          </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>是否显示：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
		          <select class="select" id="shown" name="shown" placeholder="请选择是否显示">
		            <option value="0">否</option>
		            <option value="1">是</option>
		          </select>
		        </span>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>