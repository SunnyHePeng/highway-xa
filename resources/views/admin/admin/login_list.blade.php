@extends('admin.layouts.default')

@section('content')
<div class="main">
  <table id="list" class="admin mdl-data-table mdl-js-data-table">
    <thead>
      <tr>
        <th class="td2">用户名</th>
        <th class="td2 table_sort" data-id="list" data-tr="1" data-str="1">登陆日期</th>
        <th class="td2 table_sort" data-id="list" data-tr="2">登陆次数</th>
        <th class="td2">登陆IP</th>
        <th class="td2">登陆城市</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $value)
      <tr id="tr_{{ $value['id'] }}">
        <td class="td2">{{ $value['name'] }}</td>
        <td class="td2">{{ $value['date'] }}</td>
        <td class="td2">{{ $value['count'] }}</td>
        <td class="td2">{{ $value['ip'] }}</td>
        <td class="td2">{{ $value['city'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @if($last_page > 1)
    @include('admin.layouts.page')
  @endif
</div>
@stop