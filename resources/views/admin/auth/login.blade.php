@extends('admin.layout.layout')
@section('title','后台登录')
@section('css')
    <style>
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        body {
            background-image: url("{{asset('assets/images/bg-screen.jpg')}}");
            background-size: cover;
            background-repeat: no-repeat;
        }

        body:after {
            content: '';
            background-repeat: no-repeat;
            background-size: cover;
            -webkit-filter: blur(3px);
            -moz-filter: blur(3px);
            -o-filter: blur(3px);
            -ms-filter: blur(3px);
            filter: blur(3px);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .layui-container {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        .admin-login-background {
            width: 360px;
            height: 300px;
            position: absolute;
            left: 50%;
            top: 40%;
            margin-left: -180px;
            margin-top: -100px;
        }

        .logo-title {
            text-align: center;
            letter-spacing: 2px;
            padding: 14px 0;
        }

        .logo-title h1 {
            color: #02B5E0;
            font-size: 25px;
            font-weight: bold;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 3px;
            padding: 14px 20px;
        }

        .login-form .layui-form-item {
            position: relative;
        }

        .login-form .layui-form-item label {
            position: absolute;
            left: 1px;
            top: 1px;
            width: 38px;
            line-height: 36px;
            text-align: center;
            color: #d2d2d2;
        }

        .login-form .layui-form-item input {
            padding-left: 36px;
        }

        #loginbtn{
            background-color: #02B5E0;
        }
        .captcha {
            width: 60%;
            display: inline-block;
        }

        .captcha-img {
            display: inline-block;
            width: 34%;
            float: right;
        }

        .captcha-img img {
            height: 34px;
            height: 36px;
            width: 100%;
        }
        .layui-form-checked[lay-skin=primary] i{
            background-color: #02B5E0;
            border-color: #02B5E0!important;
        }
    </style>
@endsection
@section('content')
    <div class="layui-container">
        <div class="admin-login-background">
            <div class="layui-form login-form">
                <form class="layui-form" action="">
                    <div class="layui-form-item logo-title">
                        <h1>快简易CMS</h1>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-username" for="username"></label>
                        <input type="text" name="name" lay-verify="required|account" placeholder="管理员名称"
                               autocomplete="off" class="layui-input" style="background-color: rgba(0,0,0,0.3);color: white">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-password" for="password"></label>
                        <input type="password" name="password" lay-verify="required|password" placeholder="管理员密码"
                               autocomplete="off" class="layui-input" style="background-color: rgba(0,0,0,0.3);color: white">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-vercode" for="captcha"></label>
                        <input type="text" name="captcha" lay-verify="required|captcha" placeholder="图形验证码"
                               autocomplete="off" class="layui-input verification captcha" style="background-color: rgba(0,0,0,0.3);color: white">
                        <div class="captcha-img">
                            <img id="captchaPic" title="点击图片重新获取验证码">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <input type="checkbox" name="remember"  lay-skin="primary" title="记住我">
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-fluid" id="loginbtn" lay-submit="" lay-filter="login">登 入</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('assets/libs/jquery/jquery-3.2.1.min.js')}}" charset="utf-8"></script>
    <script src="{{asset('assets/js/jquery.particleground.min.js')}}" charset="utf-8"></script>
    <script>
        layui.use(['form'],function () {
            var form = layui.form;
            var captchaUrl = "{{captcha_src('flat')}}";
            $('#captchaPic').attr('src',captchaUrl);
            // 登录过期的时候，跳出ifram框架
            if (top.location != self.location) top.location = self.location;


            // 粒子线条背景
            $(document).ready(function () {
                $('.layui-container').particleground({
                    dotColor: '#008A9A',
                    lineColor: '#008A9A'
                });
            });

            // 进行登录操作
            form.on('submit(login)', function (data) {
                data = data.field;
                if (data.name == '') {
                    layer.msg('管理员名称不能为空');
                    return false;
                }
                if (data.password == '') {
                    layer.msg('管理员密码不能为空');
                    return false;
                }
                if (data.captcha == '') {
                    layer.msg('验证码不能为空');
                    return false;
                }
                layer.msg('登录中', {
                    icon: 16
                    , shade: 0.01, time: 10 * 1000
                });
                $.ajax({
                    type: "post",
                    url: "{{route('qaecmsadmin.login')}}?_time=" + Math.random(),
                    dataType: "json",
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (res) {
                        if (res.status == 200) {
                            $('#loginbtn').attr('disabled','true');
                            layer.msg('登录成功', function () {
                                window.location = '{{route('qaecmsadmin.index')}}';
                            });
                        } else {
                            layer.msg(res.msg)
                        }
                    },
                    error: function () {
                        $('#captchaPic').attr('src',captchaUrl + '=' + (new Date).getTime());
                        layer.msg('登录失败')
                    }
                });
                return false;
            });
            /* 图形验证码 */
            $('#captchaPic').click(function () {
                $(this).attr('src',captchaUrl + '=' + (new Date).getTime());
            })
        });
    </script>
@endsection
