<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div class="text-l">
    <form id="search_form" data="start_date,end_date,title_antistop" method="get" data-url="{{url('notice/index')}}">
        公告日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if($search && array_key_exists('start_date',$search)){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if($search && array_key_exists('end_date',$search)){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        &nbsp;&nbsp;&nbsp;公告标题关键词
        <input type="text" name="title_antistop" id="title_antistop" placeholder="请输入公告标题关键词" class="input-text search-input" value="@if(isset($search['title_antistop']) && $search['title_antistop']){{$search['title_antistop']}}@endif">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20">
  <span class="l ml-10 pt-5 c-error">
    点击表格行即可查看详细内容及下载附件
  </span>
</div>
<div class="mt-10 dataTables_wrapper">
    <ul class="col-sm-12 col-xs-12">
        @if($data)
            @foreach($data as $v)
                <li class="Hui-tags mt-10 load-content" data-url="{{url('notice/notice_details/'.$v['id'])}}" title="查看公告详情">
                    <a href="javascript:;">
                        <h3 class="text-l" style="overflow: hidden;text-overflow: ellipsis; height: 25px;">{{$v['title']}}</h3>
                        <small class="f-16 ml-10 text-right c-666">
                            <i class="Hui-tags-icon Hui-iconfont">&#xe603;</i>【{{$v['publish_user']['name']}}】于{{date('Y-m-d H:i:s',$v['publish_time'])}}创建&nbsp;&nbsp;&nbsp;
                            <i class="Hui-tags-icon Hui-iconfont">&#xe725;</i>{{$v['read_number']}}次&nbsp;&nbsp;&nbsp;&nbsp;
                            <i class="Hui-tags-icon Hui-iconfont">&#xe640;</i>{{$v['download_number']}}次&nbsp;&nbsp;&nbsp;&nbsp;
                        </small>
                    </a>
                </li>
            @endforeach
        @else
            <li class="Hui-tags mt-10">
                <span class="c-blue">没有公告数据</span>
            </li>
        @endif
    </ul>
    @if($last_page>1)
        @include('admin.layouts.page')
    @endif
</div>