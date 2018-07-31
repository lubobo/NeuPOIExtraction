@extends('layout.layout')
@section('title','getTestPoi')
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>文件上传</h3>

                        <p>导入文件(.CSV)</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadFile')}}">
                            <input class="hidden" name="filePath" value="{{$syFileData}}">
                            <button type="submit" class="btn-block btn btn-xs btn-info small-box-footer">下载文件 <i class="fa fa-arrow-circle-down"></i></button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>数据清洗</h3>

                        <p>GPS数据格式转换</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadSyFile')}}">
                            <input class="hidden" name="filePath" value="{{$cleanFileName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-success small-box-footer">下载文件
                                <i class="fa fa-arrow-circle-down"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-purple-active">
                    <div class="inner">
                        <h3>数据清洗</h3>

                        <p>获取上下车点数据</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadSyFile')}}">
                            <input class="hidden" name="filePath" value="{{$disFileName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-yahoo small-box-footer">
                                下载文件 <i class="fa fa-arrow-circle-down"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-light-blue-active">
                    <div class="inner">
                        <h3>POI数据提取</h3>

                        <p>KMeanData数据提取</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadSyFile')}}">
                            <input class="hidden" name="filePath" value="{{$KMeansFileName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-bitbucket small-box-footer">
                                下载文件 <i class="fa fa-arrow-circle-down"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>POI数据提取</h3>

                        <p>基本测试POI数据提取</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadSyFile')}}">
                            <input class="hidden" name="filePath" value="{{$TempPoiName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-warning small-box-footer">
                                下载文件<i class="fa fa-arrow-circle-down"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-light-blue-gradient">
                    <div class="inner">
                        <h3>POI数据提取</h3>

                        <p>测试集POI数据提取</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('downloadSyFile')}}">
                            <input class="hidden" name="filePath" value="{{$fileName}}">
                            <button type="submit" class="btn-block btn btn-xs btn-twitter small-box-footer">
                                下载文件
                                <i class="fa fa-arrow-circle-down"></i>
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-7">
                <!-- small box -->
                <div class="small-box bg-maroon-active">
                    <div class="inner">
                        <h3>POI数据提取</h3>

                        <p>测试集POI数据类别提取</p>
                    </div>
                    <div class="small-box-footer">
                        <form method="post" action="{{route('getPoiIdData')}}">
                            <input class="hidden" name="fileName" value="{{$fileName}}">
                            <button type="submit" data-toggle="modal" data-target=".bs-example-modal-sm"
                                    class="btn-block btn btn-xs btn-google small-box-footer">
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
                    @if(isset($fileArray)&&$fileArray!='empty')
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        @php
                                            $array_1 = iconv('GBK','utf-8',$fileArray[0]);
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
                    @endif
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
                {{--</div>--}}
                {{--<!-- /.box -->--}}
            {{--</section>--}}

        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
@endsection
