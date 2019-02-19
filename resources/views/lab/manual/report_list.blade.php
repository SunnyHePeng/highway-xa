@extends('admin.layouts.default')

@section('container')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">

<div>
    <form id="search_form" data="start_date,end_date" method="get" action="/{{ request()->path() }}">
        标段：
        <select name="section" style="height:31px;position:relative;top:2px;">
            <option value="">--全部--</option>
            @foreach($sections as $section)
                <option value="{{ $section->station_code}}" {{ request('section') == $section->station_code ? 'selected' : '' }}>{{ $section->name }}</option>
            @endforeach
        </select>
    </form>
</div>
<div class="cl pd-5 bg-1 bk-gray mt-20 mr-10">
    <span class="l ml-10 pt-5 c-error">
    点击表格中的检测报告编号即可查看对应的检测报告
  </span>
</div>
<div class="mt-10 dataTables_wrapper">
    <table id="table_list" class="table table-border table-bordered table-bg table-sort">
        <thead>
        <tr class="text-c">
            <th width="40" class="hidden-xs hidden-sm">序号</th>
            <th width="100">标段名称</th>
            <th width="150">工程名称</th>
            <th width="60">工程部位</th>
            <th width="60">检测项目</th>
            <th width="60">检测报告</th>
            <th width="100" class="hidden-xs hidden-sm">检测标准</th>
            <th width="100" class="hidden-xs hidden-sm">检测结论</th>
            <th width="100" class="hidden-xs hidden-sm">检测结论描述</th>
        </tr>
        </thead>
        <tbody>
            @foreach($reports as $key => $report)
                <tr class="text-c">
                    <td class="hidden-xs hidden-sm">{{ $key + 1 }}</td>
                    <td>{{ $report['合同段'] or '-' }}</td>
                    <td>{{ $report['工程名称'] or '-' }}</td>
                    <td>{{ $report['工程部位'] or '-' }}</td>
                    <td>{{ $report['检测项目名称'] or '-' }}</td>
                    <td>{!! $report['报告编号'] or '-' !!}</td>
                    <td>{{ $report['检测标准'] or '-' }}</td>
                    <td>{{ $report['检测结论'] or '-' }}</td>
                    <td>{{ $report['检测结论描述'] or '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="dataTables_info">共 <strong>{{ $reports->lastPage() }}</strong>页/<strong>{{ $reports->total() }}</strong>条</div>
    <div class="dataTables_paginate paging_simple_numbers">
        {!! $reports->appends(request()->query())->render() !!}
    </div>
    
</div>
@endsection

@section('script')
<link href="/lib/My97DatePicker/4.8/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name=section]').change(function(){
            $(this).parent('form').submit();
        });
    });
</script>
@endsection