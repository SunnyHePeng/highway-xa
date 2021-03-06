<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<div>
    <form method="get" name="search">
        合同段：
        <span class="select-box inline" style="border:1px solid #ddd;">
			<select class="select" size="1" name="section_id" id="section_id">
				<option value="0" >全部合同段</option>
                @if($section_data)
                    @foreach($section_data as $v)
                        <option value="{{$v->id}}" @if($search['section_id']==$v->id) selected="selected"@endif>{{$v->name}}</option>
                    @endforeach
                @endif
			</select>
		</span>&nbsp;&nbsp;&nbsp;&nbsp;
        张拉时间：
        <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">

        <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

    </form>
</div>

<div class="mt-20 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="80">合同段名称</th>
            <th width="80">所属梁场</th>
            <th width="80">设备名称</th>
            <th width="80">梁号</th>
            <th width="120">压浆开始时间</th>
            <th width="100">梁板类型</th>
            <th width="100">压浆方向</th>
            <th width="100">压浆剂</th>
            <th width="100">配合比</th>
            <th width="100">流动度</th>
            <th width="100">搅拌时间(min)</th>
            <th width="100">环境温度</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
         @if($data)
           @foreach($data as $v)
               <tr class="text-c">
                   <td>{{$from++}}</td>
                   <td>{{$v['section']['name']}}</td>
                   <td>{{$v['beam_site']['name']}}</td>
                   <td>{{$v['device']['name']}}</td>
                   <td>{{$v['girder_number']}}</td>
                   <td>{{date('Y-m-d H:i:s',$v['time'])}}</td>
                   <td>{{$v['girdertype']}}</td>
                   <td>{{$v['mudjackdirect']}}</td>
                   <td>{{$v['mudjackagent']}}</td>
                   <td>{{$v['groutingagent']}}</td>
                   <td>{{$v['mobility']}}</td>
                   <td>{{$v['stirtime']}}</td>
                   <td>{{$v['environment_temperature']}}</td>
                   <td>
                       <input class="btn btn-success radius size-MINI open-detail-data" data-title="详细信息" data-url="{{url('mudjack/mudjack_detail'.'/'.$v['id'])}}" type="button" value="详细信息">
                   </td>
               </tr>
           @endforeach
         @endif
        </tbody>
    </table>
    @if($last_page>1)
        @include('admin.layouts.page')
    @endif
</div>