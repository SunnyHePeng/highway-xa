<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/role')}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>关键字(唯一)：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入关键字" id="name" name="name" required>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入角色名称" id="display_name" name="display_name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea class="textarea" placeholder="输入角色描述" id="description" name="description"></textarea>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">权限：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<dl class="permission-list">
					
				@if($permission)
				@foreach($permission as $key=>$value)
					@if($key==0 || ($value['mid'] != $permission[$key-1]['mid']))
					<dt>
						<label>{{$value['mod']['name']}}</label>
					</dt>
					@endif
					<dl class="cl permission-list2">
						<dt>
							<label><input type="checkbox" value="{{$value['id']}}" name="permission[]">&nbsp;{{$value['name']}}</label>
						</dt>
						@if($value['child'])
						<dd>
							@foreach($value['child'] as $value)
							<label><input type="checkbox" value="{{$value['id']}}" name="permission[]">&nbsp;{{$value['name']}}</label>
							@endforeach
						</dd>
						@endif
					</dl>
				@endforeach
				@endif
				</dl>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>