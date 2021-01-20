<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('assets/libs/layui/css/layui.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/module/admin.css?v=317')}}"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @section('css')
    @show
</head>
<body class="layui-layout-body">
@section('content')
@show
<script type="text/javascript" src="{{asset('assets/libs/layui/layui.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/common.js?v=319')}}"></script>
@section('js')
@show
</body>
</html>
