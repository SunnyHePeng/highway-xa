<article class="page-container" id="layer-edit">
    <form class="form form-horizontal" id="form_container" data-url="{{url('manage/department')}}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select msg_must" name="role_id" id="role_id" placeholder="请选择所属角色">
						<option value="">请选择</option>
                        @if($role_list)
                            @foreach($role_list as $v)
                                <option value="{{ $v['id']}}">{{ $v['display_name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>部门名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入部门名称" id="name" name="name">
            </div>
        </div>
        <input type="hidden" value="" id="id" name="id">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</article>