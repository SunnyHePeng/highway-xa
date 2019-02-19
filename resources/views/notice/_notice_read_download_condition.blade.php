<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div class="text-l">
    <form id="search_form" data="start_date,end_date,title_antistop" method="get" data-url="{{url('notice/read_download_condition')}}">
        公告日期范围：
        <input name="start_date" placeholder="请输入开始时间" value="@if($search && array_key_exists('start_date',$search)){{$search['start_date']}}@endif" type="text" onfocus="WdatePicker()" id="start_date" class="input-text Wdate">
        -
        <input name="end_date" placeholder="请输入结束时间" value="@if($search && array_key_exists('end_date',$search)){{$search['end_date']}}@endif" type="text" onfocus="WdatePicker()" id="end_date" class="input-text Wdate">
        &nbsp;&nbsp;&nbsp;公告标题关键词
        <input type="text" name="title_antistop" id="title_antistop" placeholder="请输入公告标题关键词" class="input-text search-input" value="@if(isset($search['title_antistop']) && $search['title_antistop']){{$search['title_antistop']}}@endif">
        <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜索</button>
    </form>
</div>
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="20">序号</th>
            <th width="60" class="hidden-xs">发布时间</th>
            <th width="50" class="hidden-xs">发布人</th>
            <th width="300">标题</th>
            <th width="30">阅读次数</th>
            <th width="30">附件下载次数</th>
            <th width="120">操作</th>
        </tr>
        </thead>
        <tbody>
           @if($data)
             @foreach($data as $v)
                 <tr class="text-c">
                    <td>{{$from++}}</td>
                    <td class="hidden-xs">{{date('Y-m-d H:i:s',$v['publish_time'])}}</td>
                     <td class="hidden-xs">{{$v['publish_user']['name']}}</td>
                    <td>{{$v['title']}}</td>
                     <td>{{$v['read_number']}}</td>
                     <td>{{$v['download_number']}}</td>
                     <td>
                         <input class="btn btn-secondary radius size-MINI show-read" type="button" value="查看已阅读人员" data-title="已阅读人员信息" data-url="{{url('notice/get_already_read_user/'.$v['id'])}}" >
                         <input class="btn radius btn-success size-MINI show-download" type="button" value="查看已下载附件人员" data-title="已下载附件人员信息" data-url="{{url('notice/get_already_download_user/'.$v['id'])}}" >
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