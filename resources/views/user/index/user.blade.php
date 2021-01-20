@extends('user.layout.layout')
@section('title','用户中心')
@section('css')
{{--    <link rel="stylesheet" href="{{asset('assets/css/main.css?v=317')}}"/>--}}
    <style>
        {{--body{--}}
        {{--    background-image: url("{{asset('assets/images/bg-screen.jpg')}}");--}}
        {{--}--}}
        /* 用户信息 */
        .user-info-head {
            width: 110px;
            height: 110px;
            line-height: 110px;
            position: relative;
            display: inline-block;
            border: 2px solid #eee;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto;
        }

        .user-info-head img {
            width: 110px;
            height: 110px;
        }

        .user-info-list-item {
            position: relative;
            padding-bottom: 8px;
        }

        .user-info-list-item > .layui-icon {
            position: absolute;
        }

        .user-info-list-item > p {
            padding-left: 30px;
        }

        .layui-line-dash {
            border-bottom: 1px dashed #ccc;
            margin: 15px 0;
        }

        /* 基本信息 */
        #userInfoForm .layui-form-item {
            margin-bottom: 25px;
        }

        /* 账号绑定 */
        .user-bd-list-item {
            padding: 14px 60px 14px 10px;
            border-bottom: 1px solid #e8e8e8;
            position: relative;
        }

        .user-bd-list-item .user-bd-list-lable {
            color: #333;
            margin-bottom: 4px;
        }

        .user-bd-list-item .user-bd-list-oper {
            position: absolute;
            top: 50%;
            right: 10px;
            margin-top: -8px;
            cursor: pointer;
        }

        .user-bd-list-item .user-bd-list-img {
            width: 48px;
            height: 48px;
            line-height: 48px;
            position: absolute;
            top: 50%;
            left: 10px;
            margin-top: -24px;
        }

        .user-bd-list-item .user-bd-list-img + .user-bd-list-content {
            margin-left: 68px;
        }

        /** 商品列表样式 */
         .project-list-item {
             background-color: #fff;
             border: 1px solid #e8e8e8;
             border-radius: 4px;
             cursor: pointer;
             transition: all .2s;
         }

        .project-list-item:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        }

        .project-list-item .project-list-item-cover {
            width: 100%;
            height: 220px;
            display: block;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .project-list-item-body {
            padding: 20px;
        }

        .project-list-item .project-list-item-body > h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
        }

        .project-list-item .project-list-item-text {
            height: 44px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .project-list-item .project-list-item-desc {
            position: relative;
        }

        .project-list-item .project-list-item-desc .price {
            color: red;
            font-size: 24px;
        }

        .project-list-item .project-list-item-desc .ew-head-list {
            position: absolute;
            right: 0;
            top: 0;
        }

        .ew-head-list .ew-head-list-item {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid #fff;
            margin-left: -10px;
        }

        .ew-head-list .ew-head-list-item:first-child {
            margin-left: 0;
        }

        /** // 商品列表样式结束 */
    </style>
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <!-- 左 -->
            <div class="layui-col-sm12 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-body" style="padding: 25px;">
                        <div class="text-center layui-text">
                            <div class="user-info-head" id="userInfoHead">
                                <img src="{{asset('assets/images/head.jpg')}}" alt=""/>
                            </div>
                            <h2 style="padding-top: 20px;">{{$name}}</h2>
                        </div>
                        <div class="layui-text" style="padding-top: 30px;">
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-email"></i>
                                <p>昵称:{{$nick}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-vercode"></i>
                                <p>等级:{{$vip==0?"注册用户":"VIP".$vip}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-dollar"></i>
                                <p>积分:{{$integral}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-email"></i>
                                <p>邮箱:{{$email}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-location"></i>
                                <p>当前登录IP:{{$loginip}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-about"></i>
                                <p>上次登录IP:{{$lastloginip}}</p>
                            </div>
                            <div class="user-info-list-item">
                                <i class="layui-icon layui-icon-male"></i>
                                <p>上次登录时间:{{$lastlogintime}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 右 -->
            <div class="layui-col-sm12 layui-col-md9">
                <div class="layui-card" style="padding-top: 25px;">
                    <!-- 选项卡开始 -->
                    <div class="layui-tab layui-tab-brief" lay-filter="userInfoTab">
                        <ul class="layui-tab-title">
                            @if(qae_pay())
                            <li class="layui-this">我的订单</li>
                            <li>商品购买</li>
                            <li>基本信息</li>
                            <li>设置</li>
                            @else
                                <li class="layui-this">基本信息</li>
                                <li>设置</li>
                            @endif
                        </ul>
                        <div class="layui-tab-content">
                            @if(qae_pay())
                            <div class="layui-tab-item  layui-show">
                                <div class="layui-row">
                                    <div class="layui-col-md12">
                                        <table id="DataTable" lay-filter="DataTable"></table>
                                    </div>
                                </div>
                                <!-- 表格操作列 -->
                                @verbatim
                                <script type="text/html" id="tableBar">
                                    {{# if(d.status==0){ }}
                                    <a class="layui-btn layui-btn-sm layui-btn-warm" lay-event="pay"><i class="layui-icon">&#xe609;</i>立即支付</a>
                                    <a class="layui-btn layui-btn-sm" lay-event="cannel"><i class="layui-icon">&#xe640;</i>取消订单</a>
                                    {{# } }}
                                </script>
                                @endverbatim
                            </div>
                            <!-- tab1 -->
                            <div class="layui-tab-item" style="padding-bottom: 20px;">
                                <div class="layui-row layui-col-space30" id="shoplist"></div>
                            </div>
                            @endif
                            <!-- tab1 -->
                            @if(qae_pay())
                            <div class="layui-tab-item">
                                @else
                                    <div class="layui-tab-item layui-show">
                                    @endif
                                <form class="layui-form" id="userInfoForm" lay-filter="userInfoForm"
                                      style="max-width: 400px;padding: 25px 10px 0 0;">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label layui-form-required">邮箱:</label>
                                        <div class="layui-input-block">
                                            <input name="email" value="{{$email}}" class="layui-input"
                                                   lay-verify="required" required/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label layui-form-required">昵称:</label>
                                        <div class="layui-input-block">
                                            <input name="nick" value="{{$nick}}" class="layui-input"
                                                   lay-verify="required" required/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label layui-form-required">密码:</label>
                                        <div class="layui-input-block">
                                            <input name="password" value="" placeholder="留空则不修改密码" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" id="BtnAction" lay-filter="userInfoSubmit" lay-submit>更新基本信息
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-row">
                                    <div class="layui-col-md12">
                                        <a ew-event="logout" class="layui-btn" data-url="{{route('qaecmsindex.logout')}}">退出登录</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //选项卡结束 -->
                </div>
            </div>
        </div>
    </div>
   @verbatim
    <script type="text/html" id="shopitem">
        <div class="layui-col-md3">
            <div class="project-list-item">
                <img class="project-list-item-cover" src="{{d.image}}"/>
                <div class="project-list-item-body">
                    <h2>{{d.name}}</h2>
                    <div class="project-list-item-text layui-text">{{d.desc}}</div>
                    <div class="project-list-item-desc">
                        <span class="price">￥{{d.price}}</span>
                        <div class="layui-btn layui-btn-sm layui-btn-warm ew-head-list" shopid="{{d.id}}">立即购买</div>
                    </div>
                </div>
            </div>
        </div>
    </script>
   @endverbatim
@endsection
@section('js')
    <script>
        layui.use(['layer', 'form', 'element','dataGrid','admin', 'table'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var element = layui.element;
            var dataGrid = layui.dataGrid;
            var admin = layui.admin;
            var table = layui.table;

            @if(qae_pay())
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsindex.user',['action'=>'orderlist'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'order_id', align: "center", width: 300, title: '订单ID'},
                    {
                        field: 'shop', align: "center", title: '商品', templet: function (dd) {
                            return dd.shop.name;
                        }
                    },
                    {field: 'money', align: "center", title: '支付金额'},
                    {
                        field: 'status', align: "center", title: '状态', templet: function (dd) {
                            let status = ['未支付', '支付成功', '支付失败']
                            return status[dd.status];
                        }
                    },
                    {field: 'success_at', align: "center", width: 200, title: '成功时间'},
                    {field: 'created_at', align: "center", width: 200, title: '创建时间'},
                    {title: '操作', toolbar: '#tableBar', width: 250, align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 工具条点击事件
            table.on('tool(DataTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "pay":
                        layer.load(2);
                        let url = "{{route('qaecmsindex.pay',['action'=>'nowpay'],false)}}"
                        admin.req(url, {data: {id: data.order_id}}, function (res) {
                            if (res.status == 200) {
                                window.location.href = res.url;
                            } else {
                                layer.msg(res.msg)
                            }
                        }, 'post')
                        break;
                    case "cannel":
                        layer.confirm("确认取消?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsindex.pay',['action'=>'cannel'],false)}}"
                            admin.req(url, {data: {id: data.order_id}}, function (res) {
                                layer.closeAll('loading');
                                layer.msg(res.msg);
                                if (res.status == 200) {
                                    table.reload('DataTable')
                                }
                            }, 'post')
                        });
                        break;
                }
            });

            // 商品
            $.get('{{route('qaecmsindex.user',['action'=>'shoplist'])}}', function (res) {
                dataGrid.render({
                    elem: '#shoplist',
                    templet: '#shopitem',
                    data: res.data,
                    page: {limit: 8, limits: [8, 16, 24, 32, 40]}
                });
            });

            dataGrid.on('item(shoplist)', function (obj) {
               let url = "{{route('qaecmsindex.pay')}}?shop="+obj.data.id;
               location.href = url;
            });
            @endif
            /* 监听表单提交 */
            form.on('submit(userInfoSubmit)', function (obj) {
                admin.btnLoading('#BtnAction')
                let url = "{{route('qaecmsindex.user',['action'=>"update"])}}";
                admin.req(url, obj.field, function (res) {
                    admin.btnLoading('#BtnAction', false)
                    layer.msg(res.msg);
                }, 'post')
                return false;
            });
        });
    </script>
@endsection
