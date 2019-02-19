<article class="cl pd-20">
    <div class="f-14 cl pd-10 bg-1 bk-gray mt-20">
        <p class="c-error">点击表格行即可查看该公告详细信息</p>
    </div>
</article>
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table  table-bg">
        <thead>
        <tr class="text-c">
            <th width="100">类别</th>
            <th width="300">内容</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        @if($data)
            @foreach($data as $v)
                <tr class="text-c pt-20">
                  <td class="pb-20 details" data-url="{{url('notice/notice_details').'/'.$v['id']}}">创建人</td>
                  <td class="pb-20 details" data-url="{{url('notice/notice_details').'/'.$v['id']}}">{{$v['publish_user']['name']}}</td>
                  <td class="pb-20" rowspan="4" style="border-bottom: 3px solid #e3e3e3;">
                      @if($v['status']==0)
                          <input class="btn btn-primary radius approval" type="button" data-url="{{url('notice/approval_publish').'?id='.$v['id']}}" value="审核"><br>
                      @else
                          <input class="btn btn-default radius" type="button" value="审核"><br>
                      @endif
                      <input class="btn radius btn-secondary mt-10 notice-edit" data-title="修改" data-url="{{url('notice/notice_edit').'?id='.$v['id']}}" type="button" value="修改">
                  </td>
                </tr>
                <tr class="text-c details" data-url="{{url('notice/notice_details').'/'.$v['id']}}">
                    <td >创建时间</td>
                    <td>{{date('Y-m-d H:i:s',$v['publish_time'])}}</td>
                </tr>
                <tr class="text-c details" data-url="{{url('notice/notice_details').'/'.$v['id']}}">
                    <td>标题</td>
                    <td>{{$v['title']}}</td>
                </tr>
                <tr class="text-c details" data-url="{{url('notice/notice_details').'/'.$v['id']}}">
                    <td style="border-bottom: 3px solid #e3e3e3;">审核状态</td>
                    <td style="border-bottom: 3px solid #e3e3e3;">
                        @if($v['status']==0)
                            <input class="btn btn-warning-outline radius disabled" type="button" value="未审核">
                        @else
                            <input class="btn btn-success-outline radius disabled" type="button" value="已审核">
                        @endif
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