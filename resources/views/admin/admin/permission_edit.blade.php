<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/permission')}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>所属模块：</label>
			<!-- <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select msg_must" name="mid" id="mid" placeholder="请选择所属模块">
						<option value="">请选择</option>
						@if($module)
						@foreach($module as $v)
						<option value="{{ $v['id']}}">{{ $v['name']}}</option>
						@endforeach
						@endif
					</select>
				</span>
			</div> -->
			<div class="formControls col-xs-8 col-sm-9">
		        <input type="text" class="input-text msg_must" value="" placeholder="请先选择所属模块" id="mname" name="mname" required>
		    </div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>所属组：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="pid" id="pid">
						<option value="0">顶级</option>
						@if($parent)
						@foreach($parent as $v)
						<option value="{{ $v['id']}}">{{ $v['name']}}</option>
							@if($v['child'])
							@foreach($v['child'] as $v)
							<option value="{{ $v['id']}}">&emsp;|--&nbsp;{{ $v['name']}}</option>
							@endforeach
							@endif
						@endforeach
						@endif
					</select>
				</span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>菜单显示：</label>
			<div class="formControls skin-minimal col-xs-8 col-sm-9">
				<div class="radio-box">
		          	<input type="radio" id="radio-1" name="status" value="1">
		          	<label for="radio-1">显示</label>
		        </div>
		        <div class="radio-box">
		          	<input type="radio" id="radio-2" name="status" value="0">
		          	<label for="radio-2">不显示</label>
        		</div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>URL：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入URL，如 manage/admin" id="url" name="url">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>权限名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入权限名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">权限图标：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="请输入权限图标" id="icon" name="icon">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea class="textarea" placeholder="输入权限描述" id="description" name="description"></textarea>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>排序：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="100" placeholder="请输入序号" id="sort" name="sort">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" value="" id="mid" name="mid" placeholder="请先选择所属模块">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>