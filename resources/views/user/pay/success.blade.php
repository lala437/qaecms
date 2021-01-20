@extends('user.layout.layout')
@section('title',"支付成功")
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body" style="padding-top: 40px;">
                <div class="layui-row text-center">
                    <i class="layui-icon layui-icon-ok layui-circle"
                       style="background: #52C41A;color: #fff;font-size:30px;font-weight:bold;padding: 20px;line-height: 80px;"></i>
                    <div style="font-size: 24px;color: #333;margin-top: 30px;">支付成功</div>
                    <div style="font-size: 15px;color: #333;margin-top: 30px;"><span style="color: red" id="time">3</span>秒后跳转到我的订单页
                    </div>
                    <div style="text-align: center;margin: 50px 0 25px 0;">
                        <a href="{{route('qaecmsindex.user')}}" class="layui-btn">查看订单</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'form', 'steps'], function () {
            var $ = layui.jquery;
            let time = 3;
            let timer = setInterval(function () {
                time = time - 1;
                $('#time').text(time);
                if (time == 0) {
                    clearInterval(timer);
                    location.href = "{{route('qaecmsindex.user')}}"
                }
            }, 1000)

        })
    </script>
@endsection
