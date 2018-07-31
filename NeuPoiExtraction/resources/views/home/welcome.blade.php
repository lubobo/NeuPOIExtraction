@extends('layout.layout')
@section('title','welcome')
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>文件上传</h3>

                        <p>导入文件(.CSV)</p>
                    </div>

                    @if($file=='empty')
                        <div class="small-box-footer">
                            <form method="POST" action="{{route("postFile")}}">
                                <div id="aetherupload-wrapper" ><!--组件最外部需要有一个名为aetherupload-wrapper的id，用以包装组件-->
                                    <input type="file" class="fc-icon-left-double-arrow" id="file"  onchange="aetherupload(this,'file').success(someCallback).upload()"/><!--需要有一个名为file的id，用以标识上传的文件，aetherupload(file,group)中第二个参数为分组名，success方法可用于声名上传成功后的回调方法名-->
                                    <div class="progress" style="margin-bottom: 2px">
                                        <div id="progressbar" style="background:greenyellow;height: 20px; width: 0;"></div><!--需要有一个名为progressbar的id，用以标识进度条-->
                                    </div>
                                    <input type="hidden" name="file1" id="savedpath" ><!--需要有一个名为savedpath的id，用以标识文件保存路径的表单字段，还需要一个任意名称的name-->
                                    <input class="hidden" name="MyFileRename" id="MyFileRename">
                                    <input class="hidden" name="MyFileName" id="MyFileName">
                                    <span style="font-size:10px;color:whitesmoke;" id="output"></span><!--需要有一个名为output的id，用以标识提示信息-->
                                    <button type="submit" data-toggle="modal" data-target=".bs-example-modal-sm" class="btn btn-block btn-danger">
                                        提交文件</button>
                                </div>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    @elseif(isset($filePath)&&$file=='exist')
                        <div class="small-box-footer">
                            <form method="post" action="{{route("downloadFile")}}">
                                <input class="hidden" name="filePath" value="{{$filePath}}">
                                <button type="submit" class="btn-block btn btn-xs btn-info small-box-footer">
                                    下载文件 <i class="fa fa-arrow-circle-down"></i>
                                </button>
                                {{ csrf_field() }}
                            </form>
                            <p>

                            </p>
                            <form method="post" action="{{route('postFile')}}">
                                <input class="hidden" name="MyFileRename" value="{{$fileReName}}">
                                <input class="hidden" name="MyFileName" value="{{$fileName}}">
                                <span style="font-size:10px;color:whitesmoke;" id="output"></span><!--需要有一个名为output的id，用以标识提示信息-->
                                <button type="submit" data-toggle="modal" data-target=".bs-example-modal-sm"
                                        class="btn btn-xs small-box-footer btn-block btn-success">
                                    查看文件 <i class="fa fa-arrow-circle-right"></i>
                                </button>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    @endif
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
                        <h3 class="box-title">数据展示界面</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>

                    </div>
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


