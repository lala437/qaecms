@extends('admin.layout.layout')
@section('title','工作台')
@section('css')
    <style>
        /** 应用快捷块样式 */
        .console-app-group {
            padding: 16px;
            border-radius: 4px;
            text-align: center;
            background-color: #fff;
            cursor: pointer;
            display: block;
        }

        .console-app-group .console-app-icon {
            width: 32px;
            height: 32px;
            line-height: 32px;
            margin-bottom: 6px;
            display: inline-block;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 32px;
            color: #69c0ff;
        }

        .console-app-group:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, .08);
        }

        /** //应用快捷块样式 */

        /** 小组成员 */
        .console-user-group {
            position: relative;
            padding: 10px 0 10px 60px;
        }

        .console-user-group .console-user-group-head {
            width: 32px;
            height: 32px;
            position: absolute;
            top: 50%;
            left: 12px;
            margin-top: -16px;
            border-radius: 50%;
        }

        .console-user-group .layui-badge {
            position: absolute;
            top: 50%;
            right: 8px;
            margin-top: -10px;
        }

        .console-user-group .console-user-group-name {
            line-height: 1.2;
        }

        .console-user-group .console-user-group-desc {
            color: #8c8c8c;
            line-height: 1;
            font-size: 12px;
            margin-top: 5px;
        }

        /** 卡片轮播图样式 */
        .admin-carousel .layui-carousel-ind {
            position: absolute;
            top: -41px;
            text-align: right;
        }

        .admin-carousel .layui-carousel-ind ul {
            background: 0 0;
        }

        .admin-carousel .layui-carousel-ind li {
            background-color: #e2e2e2;
        }

        .admin-carousel .layui-carousel-ind li.layui-this {
            background-color: #999;
        }

        /** 广告位轮播图 */
        .admin-news .layui-carousel-ind {
            height: 45px;
        }

        .admin-news a {
            display: block;
            line-height: 70px;
            text-align: center;
        }

        /** 最新动态时间线 */
        .layui-timeline-dynamic .layui-timeline-item {
            padding-bottom: 0;
        }

        .layui-timeline-dynamic .layui-timeline-item:before {
            top: 16px;
        }

        .layui-timeline-dynamic .layui-timeline-axis {
            width: 9px;
            height: 9px;
            left: 1px;
            top: 7px;
            background-color: #cbd0db;
        }

        .layui-timeline-dynamic .layui-timeline-axis.active {
            background-color: #0c64eb;
            box-shadow: 0 0 0 2px rgba(12, 100, 235, .3);
        }

        .dynamic-card-body {
            box-sizing: border-box;
            overflow: hidden;
        }

        .dynamic-card-body:hover {
            overflow-y: auto;
            padding-right: 9px;
        }

        /** 优先级徽章 */
        .layui-badge-priority {
            border-radius: 50%;
            width: 20px;
            height: 20px;
            padding: 0;
            line-height: 18px;
            border-width: 2px;
            font-weight: 600;
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid ew-console-wrapper">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增视频数<span class="layui-badge layui-badge-blue pull-right">个</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$newvideo}}</p>
                        <p>总视频数<span class="pull-right">{{$totalvideo}}</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增文章数<span class="layui-badge layui-badge-red pull-right">篇</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$newarticle}}</p>
                        <p>总文章数<span class="pull-right">{{$totalarticle}}</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增用户数
                        <span class="icon-text pull-right">位</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$newuser}}</p>
                        <p>总用户数<span class="pull-right">{{$totaluser}}</span></p>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        总VIP数
                        <span class="icon-text pull-right">位</span>
                    </div>
                    <div class="layui-card-body">
                        <p class="lay-big-font">{{$totalvip}}</p>
                        <p>过期VIP数<span class="pull-right">{{$oldvip}}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 快捷方式 -->
        <div class="layui-row layui-col-space15">
            <div class="layui-col-sm6" style="padding-bottom: 0;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group" ew-href="{{route('qaecmsadmin.webconfig')}}" ew-title="网站设置">
                            <i class="console-app-icon layui-icon layui-icon-website" style="color: #ff9c6e;"></i>
                            <div class="console-app-name">网站</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group" ew-href="{{route('qaecmsadmin.user')}}" ew-title="用户列表">
                            <i class="console-app-icon layui-icon layui-icon-group"
                               style="font-size: 26px;padding-top: 3px;margin-right: 6px;"></i>
                            <div class="console-app-name">用户</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group" ew-href="{{route('qaecmsadmin.shop')}}" ew-title="商品列表">
                            <i class="console-app-icon layui-icon layui-icon-cart" style="color: #ff9c6e;"></i>
                            <div class="console-app-name">商品</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3" ew-href="{{route('qaecmsadmin.order')}}" ew-title="订单列表">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-form"
                               style="color: #b37feb;font-size: 30px;"></i>
                            <div class="console-app-name">订单</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6" style="padding-bottom: 0;">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group" ew-href="{{route('qaecmsadmin.video')}}" ew-title="视频列表">
                            <i class="console-app-icon layui-icon layui-icon-layer"
                               style="color: #ffd666;font-size: 34px;"></i>
                            <div class="console-app-name">视频</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3" ew-href="{{route('qaecmsadmin.article')}}" ew-title="文章列表">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-email"
                               style="color: #5cdbd3;font-size: 36px;"></i>
                            <div class="console-app-name">文章</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3">
                        <div class="console-app-group" ew-href="{{route('qaecmsadmin.ad')}}" ew-title="广告列表">
                            <i class="console-app-icon layui-icon layui-icon-note"
                               style="color: #ff85c0;font-size: 28px;"></i>
                            <div class="console-app-name">广告</div>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm3" ew-href="{{route('qaecmsadmin.link')}}" ew-title="友情链接">
                        <div class="console-app-group">
                            <i class="console-app-icon layui-icon layui-icon-slider" style="color: #ffc069;"></i>
                            <div class="console-app-name">友链</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8 layui-col-sm6">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">最新视频</div>
                            <div class="layui-card-body dynamic-card-body mini-bar" style="height: 500px;">
                                <ul class="layui-timeline layui-timeline-dynamic">
                                     @foreach($newvideolist as $video)
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content layui-text">
                                            <a href="{{route('qaecmsindex.play',['id'=>$video->id])}}" target="_blank"><div class="layui-timeline-title">{{$video->title}}<span class="pull-right">{{$video->last}}</span></div></a>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">最新文章</div>
                            <div class="layui-card-body dynamic-card-body mini-bar" style="height: 500px;">
                                <ul class="layui-timeline layui-timeline-dynamic">
                                    @foreach($newarticlelist as $article)
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content layui-text">
                                            <div class="layui-timeline-title">{{$article->title}}
                                                <span class="pull-right">{{$article->created_at}}</span></div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
{{--                    <div class="layui-col-md6">--}}
{{--                        <div class="layui-card">--}}
{{--                            <div class="layui-card-header">最新留言</div>--}}
{{--                            <div class="layui-card-body"  style="height: 500px;">--}}
{{--                                <table class="layui-table" lay-skin="line">--}}
{{--                                    <colgroup>--}}
{{--                                        <col width="80"/>--}}
{{--                                        <col/>--}}
{{--                                        <col width="80"/>--}}
{{--                                    </colgroup>--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <td align="center">排序</td>--}}
{{--                                        <td>留言标题</td>--}}
{{--                                        <td align="center">用户</td>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td align="center">--}}
{{--                                            <span class="layui-badge layui-badge-red layui-badge-priority">1</span>--}}
{{--                                        </td>--}}
{{--                                        <td><span class="layui-text"><a>请添加XXX视频</a></span></td>--}}
{{--                                        <td align="center">张山</td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="layui-col-md4 layui-col-sm6">
                <div class="layui-card">
                    <div class="layui-card-header">版本信息</div>
                    <div class="layui-card-body">
                        <table class="layui-table layui-text">
                            <colgroup>
                                <col width="90">
                                <col>
                            </colgroup>
                            <tbody>
                            <script type="text/html" ew-tpl>
                                <tr>
                                    <td>当前版本</td>
                                    <td>{{$version}}&emsp; <button class="layui-btn layui-btn-sm" id="update">检查更新</button></td>
                                </tr>
                                <tr>
                                    <td>Author</td>
                                    <td>心落虞渊丶</td>
                                </tr>
                            </script>
                            <tr>
                                <td>主要特色</td>
                                <td>全新快速建站系统</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">新会员</div>
                    <div class="layui-card-body">
                        @foreach($newuserlist as $user)
                        <div class="console-user-group">
                            <img src="{{asset('assets/images/head.jpg')}}" class="console-user-group-head" alt=""/>
                            <div class="console-user-group-name">{{$user->name}}</div>
                            <div class="console-user-group-desc">{{$user->vip==0?"普通会员":"VIP"}}</div>
                            <span class="layui-badge layui-badge-green">注册时间:{{$user->created_at}}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'carousel', 'element'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var carousel = layui.carousel;
            var device = layui.device();
            updatesystem();
            // 渲染轮播
            carousel.render({
                elem: '#workplaceNewsCarousel',
                width: '100%',
                height: '70px',
                arrow: 'none',
                autoplay: true,
                trigger: device.ios || device.android ? 'click' : 'hover',
                anim: 'fade'
            });

            function updatesystem() {
                let check_url = "{{route('qaecmsadmin.checkversion')}}";
                layer.load(2);
                $.get(check_url, function (res) {
                    layer.closeAll('loading')
                    if (res.code == 200) {
                        layer.confirm("系统有新版本,确认更新?", function (index) {
                            layer.close(index)
                            let update_url = "{{route('qaecmsadmin.update')}}";
                            layer.msg('正在更新中。。', {
                                icon: 16,
                                shade: 0.1,
                                time: 100 * 10000
                            });
                            $.get(update_url, function (res,status) {
                                layer.closeAll('loading')
                                if(status=="success"){
                                    if (res.code == 200) {
                                        layer.msg("更新成功,建议退出后台重新进入后台");
                                    } else {
                                        layer.msg(res.msg);
                                    }
                                }else {
                                    layer.msg('更新失败')
                                }
                            })
                        })
                    } else if (res.code == 204) {
                        layer.msg(res.msg);
                    }
                })
            }

            $('#update').click(function () {
                updatesystem();
            })

        });
    </script>
@endsection
