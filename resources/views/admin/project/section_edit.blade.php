<article class="page-container" id="layer-edit">
	<form class="form form-horizontal" id="form_container" data-url="{{url('manage/section')}}">
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>项目名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="project_id" id="project_id" class="select msg_must" placeholder="请选择项目名称" data-url="{{url('manage/get_psec')}}">
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
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>标段名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<span class="select-box">
					<select name="name" id="name" class="select msg_must" placeholder="请选择标段名称">
		                <option value="">请选择标段</option>
		                @if(isset($section))
		                @foreach($section as $k=>$v)
		                <option value="{{$v['name']}}" data-id="{{$v['id']}}">{{$v['name']}}</option>
		                @endforeach
		                @endif
		            </select>
		        </span>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>起始桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入起始桩号" id="begin_position" name="begin_position">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>终止桩号：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入终止桩号" id="end_position" name="end_position">
			</div>
		</div>
		<div class="row cl">							
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>承包商名称：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text msg_must" value="" placeholder="请输入承包商名称" id="cbs_name" name="cbs_name">
			</div>
		</div>
		<!-- <div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>拌合站数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入拌合站数量" id="bhz_num" name="bhz_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>梁场数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入梁场数量" id="lc_num" name="lc_num">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>隧道数量：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="number" class="input-text msg_must" value="" placeholder="请输入隧道数量" id="sd_num" name="sd_num">
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
		<input type="hidden" value="" id="psection_id" name="psection_id" placeholder="请先选择标段名称">
		<!-- <input type="hidden" class="input-text msg_must" id="project_id" name="project_id" placeholder="请先选择项目名称"> -->
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</article>