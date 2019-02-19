@extends('admin.layouts.iframe')

@section('container')
    <meta http-equiv="refresh" content="62">
    <article class="" id="show_detail">
            <div class="wl_info col-xs-12 col-sm-8" class="padding-left: 0;">
                <div class="panel panel-primary">
                    <div class="panel-header">施工配合比</div>
                    <div class="panel-body" class="padding: 0;">
                        <table class="table table-border table-bordered table-bg">
                            <thead>
                            <tr class="text-c">
                                <th width="50">序号</th>
                                <th width="100">物料名称</th>
                                <th width="100">设计量KG</th>
                                <th width="100">投放量KG</th>
                                <th width="100">偏差率</th>
                            </tr>
                            </thead>
                            <tbody id="detail_info">
                            @foreach($detail_info as $key=>$value)
                                @if($key < 9)
                                    @if(abs($value['pcl']) > $snbhz_detail[$value['type']]['pcl'])
                                        <?php $cl = 'red-line';?>
                                    @else
                                        <?php $cl = '';?>
                                    @endif
                                    <tr class="text-c {{$cl}}">
                                        <td>{{$value['type']}}</td>
                                        <td>{{$snbhz_detail[$value['type']]['name']}}</td>
                                        <td>{{$value['design']}}</td>
                                        <td>{{$value['fact']}}</td>
                                        <td>{{$value['pcl']}}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </article>
@stop

@section('script')
    <script type="text/javascript" src="/static/admin/js/common.js"></script>
    <script type="text/javascript" src="/lib/plupload/2.3.1/js/plupload.full.min.js"></script>
    <script type="text/javascript" src="/static/admin/js/upload.js"></script>
    <script type="text/javascript">
        list.init();

        var snbhz_detail = eval('('+$('#snbhz_detail').val()+')');

        uploadFile('imgPicker', '{{url('snbhz/file/upload')}}', 'uploadimg', 'imgList', 'imgShow', {name:'thumb',type:'images'}, 'thumb')
        uploadFile('filePicker', '{{url('snbhz/file/upload')}}', 'uploadfiles', 'fileList', 'fileShow', {name:'file',type:'file'}, 'file')
    </script>
    <script type="text/javascript" src="/static/admin/js/snbhz_info.js"></script>
@stop