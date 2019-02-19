@extends('admin.layouts.iframe')

@section('container')
    <div class="mt-10 dataTables_wrapper">
        <table id="table_list" class="table table-border table-bordered table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="30">序号</th>
                <th width="80">人员姓名</th>
                <th width="80">阅读时间</th>
            </tr>
            </thead>
            <tbody>
                @if($data)
                  @foreach($data as$k =>$v)
                      <tr class="text-c">
                          <td>{{$k+1}}</td>
                          <td>{{$v['user']['name']}}</td>
                          <td>{{date('Y-m-d H:i:s',$v['created_at'])}}</td>
                      </tr>
                  @endforeach
                @endif
            </tbody>
        </table>

    </div>

@stop


@section('script')
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/common.js"></script>

    <script type="text/javascript">
       list.init();
    </script>
@stop