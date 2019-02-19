<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/supervision')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="project_id" id="project_id" class="select msg_must" placeholder="请选择项目名称">
		                <option value="">请选择项目</option>
		                @if(isset($project))
		                @foreach($project as $k=>$v)
		                <option value="{{$v['id']}}" @if($v['id'] == $search['pro_id']) selected @endif>{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入监理名称" id="name" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>监理类型：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="type" id="type" class="select">
		                @if(isset($type))
		                @foreach($type as $k=>$v)
		                <option value="{{$k}}">{{$v}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>所属单位：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入所属单位" id="company" name="company">
			</div>
		</div>
		<!-- <div class="row cl">							
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>职务：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入职务" id="position" name="position">
			</div>
		</div> -->
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
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>