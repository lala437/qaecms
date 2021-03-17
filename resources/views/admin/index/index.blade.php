@extends('admin.layout.layout')
@section('title','快简易CMS')
@section('content')
    <div class="layui-layout layui-layout-admin">
        <!-- 头部 -->
        <div class="layui-header">
            <div class="layui-logo">
                <img src="{{asset('assets/images/logo.png')}}" style="height: 50px"/>
            </div>
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item" lay-unselect>
                    <a ew-event="flexible" title="侧边伸缩"><i class="layui-icon layui-icon-shrink-right"></i></a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a ew-event="refresh" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
{{--                <li class="layui-nav-item" lay-unselect>--}}
{{--                    <a ew-event="message" title="消息">--}}
{{--                        <i class="layui-icon layui-icon-notice"></i>--}}
{{--                        <span class="layui-badge-dot"></span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="layui-nav-item" lay-unselect>--}}
{{--                    <a ew-event="note" title="便签"><i class="layui-icon layui-icon-note"></i></a>--}}
{{--                </li>--}}
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a ew-event="fullScreen" title="全屏"><i class="layui-icon layui-icon-screen-full"></i></a>
                </li>
{{--                <li class="layui-nav-item layui-hide-xs" lay-unselect>--}}
{{--                    <a ew-event="lockScreen" title="锁屏"><i class="layui-icon layui-icon-password"></i></a>--}}
{{--                </li>--}}
                <li class="layui-nav-item" lay-unselect>
                    <a>
                        <img src="{{asset('assets/images/head.jpg')}}" class="layui-nav-img">
                        <cite>{{auth('admin')->user()->name}}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd lay-unselect><a ew-event="logout" data-url="{{route('qaecmsadmin.logout')}}">退出</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a ew-event="theme" title="主题" data-url="{{route('qaecmsadmin.themeset')}}"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边栏 -->
        <div class="layui-side">
            <div class="layui-side-scroll">
                <ul class="layui-nav layui-nav-tree arrow2" lay-filter="admin-side-nav" lay-shrink="_all">
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-console"></i>&emsp;<cite>Dashboard</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.workspace')}}">工作台</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-set"></i>&emsp;<cite>系统管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.webconfig')}}">网站设置</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.nav')}}">导航设置</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.carousel')}}">轮播设置</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.seoconfig')}}">SEO设置</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-user"></i>&emsp;<cite>用户管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.user')}}">用户列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-diamond"></i>&emsp;<cite>内容管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.type')}}">分类管理</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.article')}}">文章管理</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.video')}}">视频管理</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.singlepage')}}">单页管理</a></dd>
{{--                            <dd><a lay-href="{{route('qaecmsadmin.annex')}}">附件管理</a></dd>--}}
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-heart"></i>&emsp;<cite>资源管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.job')}}">采集资源</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.datatomysql')}}">数据入库</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.notosql')}}">未入库数据</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-cart"></i>&emsp;<cite>商品管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.shop')}}">商品列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-dollar"></i>&emsp;<cite>支付管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.payconfig')}}">支付设置</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.order')}}">订单列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-search"></i>&emsp;<cite>搜索管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.searchconfig')}}">搜索设置</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-link"></i>&emsp;<cite>友情链接</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.link')}}">友链列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-template"></i>&emsp;<cite>缓存管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.cache')}}">缓存设置</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-play"></i>&emsp;<cite>播放器管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.player')}}">播放器列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-notice"></i>&emsp;<cite>广告管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.ad')}}">广告列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-senior"></i>&emsp;<cite>任务管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.task')}}">定时任务</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a><i class="layui-icon layui-icon-rss"></i>&emsp;<cite>留言管理</cite></a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{route('qaecmsadmin.commentconfig')}}">留言设置</a></dd>
                            <dd><a lay-href="{{route('qaecmsadmin.comment')}}">留言列表</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item">
                        <a href="{{route('qaecmsindex.index')}}"><i class="layui-icon layui-icon-home"></i>&emsp;<cite>回到首页</cite></a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 主体部分 -->
        <div class="layui-body"></div>
        <!-- 底部 -->
        <div class="layui-footer layui-text">
            copyright © 2020 <a href="http://www.qaecms.com" target="_blank">快简易CMS</a> all rights reserved.
            <span class="pull-right"></span>
        </div>
    </div>

    <!-- 加载动画 -->
    <div class="page-loading">
        <div class="rubik-loader"></div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['index'], function () {
            var $ = layui.jquery;
            var index = layui.index;

            // 默认加载主页
            index.loadHome({
                menuPath: '{{route('qaecmsadmin.workspace')}}',
                menuName: '<i class="layui-icon layui-icon-home"></i>'
            });

        });
    </script>
@endsection

