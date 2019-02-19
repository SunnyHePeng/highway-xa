<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/collection')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" id="project_name" name="project_name" placeholder="请输入项目名称">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="section_id" id="section_id" class="select">
		                @if(isset($section))
		                @foreach($section as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<!-- <div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备分类：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="category_id" id="category_id" class="select">
		                @if(isset($category))
		                @foreach($category as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>采集地点：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="category_id" id="category_id" class="select">
		                @if(isset($category))
		                @foreach($category as $k=>$v)
		                <option value="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div> -->
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>采集点名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入采集点名称" id="name" name="name">
			</div>
		</div>
		<input type="hidden" value="" id="id" name="id">
		<input type="hidden" class="input-text" id="project_id" name="project_id">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>