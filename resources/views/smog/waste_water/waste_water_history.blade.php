@extends('admin.layouts.default')

@section('container')
    <link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
    <div>
        <form method="get" name="search">
            合同段：
            <span class="select-box inline" style="border:1px solid #ddd;">
			<select class="select" size="1" name="section_id" id="section_id">
				<option value="0" @if($search['section_id']==0)selected="selected"@endif>全部</option>
                @if($section_data)
                    @foreach($section_data as $v)
                        <option value="{{$v['id']}}" @if($search['section_id']==$v['id'])selected="selected"@endif>{{$v['name']}}</option>
                    @endforeach
                @endif
			</select>
		</span>
            时间：
            <input name="start_date" placeholder="请输入开始时间" value="{{$search['start_date']}}" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
            -
            <input name="end_date" placeholder="请输入结束时间" value="{{$search['end_date']}}" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">

            <button class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>

        </form>
    </div>


    <div class="cl pd-5 bg-1 bk-gray mt-20 hidden-xs">
  <span class="dropDown"> <a class="btn btn-success radius" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">导出</a>
    <ul class="dropDown-menu menu radius box-shadow">
      <li><a href="#" class="export-cur open-iframe" data-title="污水处理历史数据" data-url="{{$url.'&d=cur'.'&page='.$search['page']}}">当前页面数据</a></li>
      <li><a href="#" class="export-all open-iframe" data-title="污水处理历史数据" data-url="{{$url.'&d=all'}}">全部页面数据</a></li>
    </ul>
  </span>
    </div>

    @include('smog.waste_water._waste_water_history')


@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript">
    list.openIframe();

    </script>
@stop
