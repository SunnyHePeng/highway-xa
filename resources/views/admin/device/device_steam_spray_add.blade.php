<article class="page-container" id="layer-edit">
    <form class="form form-horizontal" id="form_container" data-url="{{url('manage/steam_spray_device_add')}}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>项目公司：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="project_id" id="project_id" placeholder="请选择项目公司" data-url="{{url('manage/get_sup_by_pro')}}">
						<option value="0">请选择项目公司</option>
                        @if($project_data)
                            @foreach($project_data as $v)
                                <option value="{{$v['id']}}">{{$v['name']}}</option>
                            @endforeach
                        @endif
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>监理：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="supervision_id" id="supervision_id" placeholder="请选择监理" data-url="{{url('manage/get_sec_by_sup')}}">
						<option value="">请选择</option>
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>合同段：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="section_id" id="section_id" placeholder="请选择合同段" data-url="{{url('manage/get_beam_site_by_sec')}}">
						<option value="">请选择</option>
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>梁场：</label>
            <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box" style="width:150px;">
					<select class="select" name="beam_site_id" id="beam_site_id" placeholder="请选择梁场">
						<option value="">请选择</option>
					</select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备名称：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入设备名称" id="name" name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备编码：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text" value="" placeholder="请输入设备编码" id="dcode" name="dcode">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>设备型号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入设备型号" id="model" name="model">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>摄像头1编号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入摄像头1编号" id="camera1" name="camera1">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>摄像头2编号：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入摄像头2编号" id="camera2" name="camera2">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>生产厂家：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入生产厂家" id="factory_name" name="factory_name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>出厂日期：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入出厂日期" id="factory_date" name="factory_date">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>增压泵额定功率(KW)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入增压泵额定功率(kw)" id="parame1" name="parame1">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>增压泵扬程(M)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入增压泵扬程(M)" id="parame2" name="parame2">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>最大养生梁数量(个)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入最大养生梁数量" id="parame3" name="parame3">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>最大养生时间(D)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入最大养生时间(D)" id="parame4" name="parame4">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>负责人(D)：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入负责人" id="fzr" name="fzr">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3"><span class="c-red">*</span>联系方式：</label>
            <div class="formControls col-xs-8 col-sm-8">
                <input type="text" class="input-text msg_must" value="" placeholder="请输入负责人联系方式" id="phone" name="phone">
            </div>
        </div>
        <input type="hidden" name="cat_id" value="{{$cat_id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</article>