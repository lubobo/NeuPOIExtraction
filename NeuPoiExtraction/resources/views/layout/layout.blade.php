<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/morris/morris.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/AdminLTE-2.3.6/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="/Font-Awesome-3.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Font-Awesome-3.2.1/css/font-awesome.min.css">
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
</head>
<body class="hold-transition sidebar-collapse skin-blue sidebar-mini">
{{--<body class="hold-transition skin-blue sidebar-mini">--}}
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{route('getWelcome')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>N</b>EU</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>POI</b>EXTRACT</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/AdminLTE-2.3.6/dist/img/Neu.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><strong>东北大学</strong></p>
                <a href="#">POI数据提取系统</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">系统功能</li>
            <li class="active treeview">
                <a href="{{route('getWelcome')}}">
                    <i class="icon icon-home"></i>  <span> 返回首页</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
            </li>
            <li class="active treeview">
                <a href="{{route('resetSystem')}}">
                    <i class="icon icon-retweet"></i>  <span> 重置系统</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
    </section>
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                NEU
                <small>POI EXTRACT</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <!-- Main content -->
        @yield('content')
        <section class="content">

        </section>
        <!-- /.content -->
    </div>
        <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 0.1.0
                </div>
                <strong>Copyright &copy; 2017-2018 <a href="#">LUKBOB DEV</a>.</strong> All rights
                reserved.
        </footer>
        <aside class="control-sidebar control-sidebar-dark">
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs"></ul>

            <div class="tab-content">

                <div class="tab-pane" id="control-sidebar-home-tab"></div>

                <div class="tab-pane" id="control-sidebar-stats-tab"></div>
            </div>

        </aside>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/AdminLTE-2.3.6/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ URL::asset('js/spark-md5.min.js') }}"></script><!--需要引入spark-md5.min.js-->
<script src="//cdn.bootcss.com/jquery/2.2.3/jquery.min.js"></script><!--需要引入jquery.min.js-->
<script src="{{ URL::asset('js/aetherupload.js') }}"></script><!--需要引入aetherupload.js-->
<script>
    // success(callback)中声名的回调方法需在此定义，参数callback可为任意名称，此方法将会在上传完成后被调用
    // 可使用this对象获得fileName,fileSize,uploadBaseName,uploadExt,subDir,group,savedPath等属性的值
    someCallback = function(){
// Example
        $('#MyFileName').val(this.fileName);
        $('#MyFileRename').val(this.savedPath.substr(this.savedPath.lastIndexOf('/') + 1));
        $('#result').append(
            '<p>执行回调 - 文件原名：<span >'+this.fileName+'</span> | 文件大小：<span >'+parseFloat(this.fileSize / (1000 * 1000)).toFixed(2) + 'MB'+'</span> | 文件储存名：<span >'+this.savedPath.substr(this.savedPath.lastIndexOf('/') + 1)+'</span></p>'
        );
    }

</script>

<script src="/AdminLTE-2.3.6/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/morris/morris.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/AdminLTE-2.3.6/plugins/knob/jquery.knob.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/AdminLTE-2.3.6/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/AdminLTE-2.3.6/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="/AdminLTE-2.3.6/plugins/fastclick/fastclick.js"></script>
<script src="/AdminLTE-2.3.6/dist/js/app.min.js"></script>
<script src="/AdminLTE-2.3.6/dist/js/pages/dashboard.js"></script>
<script src="/AdminLTE-2.3.6/dist/js/demo.js"></script>
</body>
</html>
