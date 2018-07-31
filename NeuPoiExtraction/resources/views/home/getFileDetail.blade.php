@extends('layout.layout')
@section('title','getFileDetail')
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>文件上传</h3>

                        <p>导入文件(.CSV)</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadFile')}}">
                            <input class="hidden" name="filePath" value="{{$fileName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-info small-box-footer">下载文件 <i class="fa fa-arrow-circle-down"></i></button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>数据清洗</h3>

                        <p>基础GPS数据清洗</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('cleanData')}}">
                            <input class="hidden" name="filePath" value="{{$fileName}}">
                            <button type="submit" data-toggle="modal" data-target=".bs-example-modal-sm"
                                    class="btn-block btn btn-xs btn-success small-box-footer">
                                点击开始
                                <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            {{--模态框--}}
            <div class="modal fade bs-example-modal-sm" tabindex="-1"
                 role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header alert-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title text-center" id="myModalLabel">
                                <i class="icon-exclamation-sign">  系统提示</i></h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <i class="icon-spinner text-info icon-spin icon-3x"></i>
                                <h5><strong class="text-info">数据生成中</strong></h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">

            <section class="col-lg-12 connectedSortable">
                <!-- TABLE: LATEST ORDERS -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">数据展示界面:<span class="h5"> <strong>(共{{$len}}条)</strong></span></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    @php
                                        $array_1 = iconv('gb2312','utf-8',$fileArray[0]);
                                        $arr = explode(',', $array_1);
                                    @endphp
                                    @foreach($arr as $file)
                                        <th class="bg-red">{{$file}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <h class = "hidden">{{$i = 0}}</h>
                                @foreach($fileArray as $array)
                                    <h class = "hidden">{{$i ++}}</h>
                                    @if($i > 1)
                                        <tr>
                                            @php
                                                $arr_t = explode(',', $array);
                                            @endphp
                                            @foreach($arr_t as $t)
                                                @if($i%2 == 0)
                                                    <td>{{iconv('GBK','utf-8',$t)}}</td>
                                                @else
                                                    <td class="bg-gray-light">{{iconv('GBK','utf-8',$t)}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                {{--<div class="box-footer clearfix">--}}
                {{--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>--}}
                {{--<a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>--}}
                {{--</div>--}}
                <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </section>

            {{--<section class="col-lg-12 connectedSortable">--}}
                {{--<!-- MAP & BOX PANE -->--}}
                {{--<div class="box box-success">--}}
                    {{--<div class="box-header with-border">--}}
                        {{--<h3 class="box-title">地图展示界面</h3>--}}

                        {{--<div class="box-tools pull-right">--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                            {{--</button>--}}
                            {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!-- /.box-header -->--}}
                    {{--<div class="box-body no-padding">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-9 col-sm-8">--}}
                                {{--<div class="pad">--}}
                                    {{--<!-- Map will be created here -->--}}
                                    {{--<div id="world-map-markers" style="height: 325px;"></div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!-- /.col -->--}}
                        {{--<div class="col-md-3 col-sm-4">--}}
                        {{--<div class="pad box-pane-right bg-green" style="min-height: 280px">--}}
                        {{--<div class="description-block margin-bottom">--}}
                        {{--<div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>--}}
                        {{--<h5 class="description-header">8390</h5>--}}
                        {{--<span class="description-text">Visits</span>--}}
                        {{--</div>--}}
                        {{--<!-- /.description-block -->--}}
                        {{--<div class="description-block margin-bottom">--}}
                        {{--<div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>--}}
                        {{--<h5 class="description-header">30%</h5>--}}
                        {{--<span class="description-text">Referrals</span>--}}
                        {{--</div>--}}
                        {{--<!-- /.description-block -->--}}
                        {{--<div class="description-block">--}}
                        {{--<div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>--}}
                        {{--<h5 class="description-header">70%</h5>--}}
                        {{--<span class="description-text">Organic</span>--}}
                        {{--</div>--}}
                        {{--<!-- /.description-block -->--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- /.col -->--}}
                        {{--</div>--}}
                        {{--<!-- /.row -->--}}
                    {{--</div>--}}
                    {{--<!-- /.box-body -->--}}
                {{--</div>--}}
                {{--<!-- /.box -->--}}
            {{--</section>--}}

        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
@endsection