<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="icon" type="image/png" href="{{ asset('assets/i/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/i/app-icon72x72@2x.png') }}">
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <script src="{{ asset('assets/js/echarts.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/amazeui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/amazeui.datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    @section('style')
    @show
</head>
<body>
	<script src="{{ asset('assets/js/theme.js') }}"></script>
	<div class="am-g tpl-g">
        <!-- 头部导航栏 -->
		    @include('admin.base.header')
        <!-- 侧边导航栏 -->
            <div class="left-sidebar">               
                <!-- 列表 -->
                @include('admin.base.menu')
            </div>
        <!-- 内容 -->
    		<div class="tpl-content-wrapper">
                @include('admin.base.message')
                @yield('content')
    		</div>
	</div>
</body>
@section('javascript')
@show
<script src="{{ asset('assets/js/amazeui.min.js') }}"></script>
<script src="{{ asset('assets/js/amazeui.datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>
    var fullscreen = $.AMUI.fullscreen
    //加载进度条
    $(document).ready(function(){
        $.AMUI.progress.start();
    })
    
    // 结束进度条
    window.onload = function(){
        $.AMUI.progress.done();
    }

    $(function() {
        //实现显示选择图片缩略图
        $('.row-content').on('change','.doc-form-file', function() {
          var that = $(this);
          var fileNames = '';
          // $.each(this.files, function() {
          //   fileNames += '<span class="am-badge"><img src="'+ this.name +'"></span> ';
          // });
          // $('#file-list').html(fileNames);
          var file = this.files[0];
            function getObjectURL(file) {
                var url = null;
                    if (window.createObjectURL != undefined) { // basic
                        url = window.createObjectURL(file);
                    }
                    else if (window.URL != undefined) {
                        url = window.URL.createObjectURL(file);
                    }
                    else if (window.webkitURL != undefined) {
                        url = window.webkitURL.createObjectURL(file);
                    }   
                return url;
             }
            var url = getObjectURL(file);
            fileNames = '<div class="tpl-form-file-img" style="margin-bottom:30px"><img src="'+ url +'" style="width:300px;height:150px"></div> ';

            that.parent().next('.file-list').html(fileNames);
        });

      });
</script>
</html>