@extends('user.layout.layout')
@section('title','产品支付')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/main.css?v=317')}}"/>
@endsection
@section('content')
    <div class="layui-container body-card">
        <div class="layui-card" style="max-width: 750px;margin: 0 auto;">
            <div class="layui-card-header">
            <span class="layui-breadcrumb" style="visibility: visible;">
                <a href="{{route('qaecmsindex.user')}}">用户中心</a><span lay-separator="">/</span>
                <a><cite>确认订单</cite></a>
            </span>
            </div>
            <div class="layui-card-body buy-card">
                <div class="layui-row layui-col-space15 goods-info-group">
                    <div class="layui-col-md5">
                        <div class="goods-cover" style="background-image: url({{$shop->image}});background-size: cover"></div>
                    </div>
                    <div class="layui-col-md7 goods-info">
                        <h1 class="goods-title">{{$shop->name}}</h1>
                        <p class="goods-desc">{!! $shop->desc !!}</p>
                        <div class="goods-spec-group">
                            <div class="goods-spec-item">
                                <div class="goods-spec-item-title">商品种类：</div>
                                <div class="goods-spec-item-list">
                                    <div class="goods-spec-item-text">{{$shop->type=='vip'?"VIP":"积分"}}</div>
                                </div>
                            </div>
                            <div class="goods-spec-item">
                                <div class="goods-spec-item-title">商品规格：</div>
                                <div class="goods-spec-item-list">
                                    <div class="goods-spec-item-text">{{$shop->number.($shop->type=='vip'?"天":"分")}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="goods-price-group">
                            <div class="goods-price-label">商品价格：</div>
                            <div class="goods-price-text"><span class="small">￥</span>{{$shop->price}}
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="buy-card-title">选择支付方式</h2>
                <div class="pay-type-group">
                    <div class="pay-type-ali active" data-id="alipay">支付宝</div>
                    <div class="pay-type-wexin" data-id="wxpay">微信支付</div>
                </div>
                <div class="buy-price-gruop">
                    <div>订单总价：<span class="price"><span class="small">￥</span>{{$shop->price}}</span>
                    </div>
                    <div>支付金额：<span class="price" id="payMoney"><span class="small">￥</span>{{$shop->price}}</span>
                    </div>
                </div>
                <div class="buy-btn-group">
                    <a ew-event="back" class="layui-btn layui-btn-primary layui-btn-lg">返回上级</a>
                    <button id="btnPay" class="layui-btn layui-btn-warm layui-btn-lg">立即支付</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['layer','admin'], function () {
            var $ = layui.jquery;
            var admin= layui.admin;
            var layer = layui.layer;


            // 支付方式点击事件
            $('.pay-type-group>div').click(function () {
                $('.pay-type-group>div').removeClass('active');
                $(this).addClass('active');
            });

            // 立即支付
            $('#btnPay').click(function () {
                doPay();
            });

            function doPay() {
                var loadIndex = layer.msg('请求中...', {icon: 16, shade: 0.01, time: false});
                let url = "{{route('qaecmsindex.pay',['action'=>'pay'])}}"
                admin.req(url, {shopid:{{$shop->id}},paytype:$('.pay-type-group>.active').data('id')},function (res) {
                    if(res.status==200){
                        window.location.href = res.url;
                    }else{
                        layer.msg(res.msg);
                    }
                },'post')
            }
        });
    </script>
@endsection
