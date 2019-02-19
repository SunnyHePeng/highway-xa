<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/map')}}">
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
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>类型：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="type" id="type" class="select msg_must" placeholder="请选择类型">
		                <option value="">请选择类型</option>
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
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="name" name="name" placeholder="请输入名称">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>经纬度：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="jwd" name="jwd" placeholder="请输入经纬度">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>排序：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="100" placeholder="请输入序号" id="sort" name="sort">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3">描述：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<textarea class="textarea" placeholder="输入描述" id="content" name="content"></textarea>
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>