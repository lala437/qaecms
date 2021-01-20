@extends('user.layout.layout')
@section('title',"用户登录")
@section('css')
    <style>
        body {
            background-image: url("{{asset('assets/images/bg-login.jpg')}}");
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
        }

        body:before {
            content: "";
            background-color: rgba(0, 0, 0, .2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .login-wrapper {
            max-width: 420px;
            padding: 20px;
            margin: 0 auto;
            position: relative;
            box-sizing: border-box;
            z-index: 2;
        }

        .login-wrapper > .layui-form {
            padding: 25px 30px;
            background-color: #fff;
            box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.19);
            box-sizing: border-box;
            border-radius: 4px;
        }

        .login-wrapper > .layui-form > h2 {
            color: #333;
            font-size: 18px;
            text-align: center;
            margin-bottom: 25px;
        }

        .login-wrapper > .layui-form > .layui-form-item {
            margin-bottom: 25px;
            position: relative;
        }

        .login-wrapper > .layui-form > .layui-form-item:last-child {
            margin-bottom: 0;
        }

        .login-wrapper > .layui-form > .layui-form-item > .layui-input {
            height: 46px;
            line-height: 46px;
            border-radius: 2px !important;
        }

        .login-wrapper .layui-input-icon-group > .layui-input {
            padding-left: 46px;
        }

        .login-wrapper .layui-input-icon-group > .layui-icon {
            width: 46px;
            height: 46px;
            line-height: 46px;
            font-size: 20px;
            color: #909399;
            position: absolute;
            left: 0;
            top: 0;
            text-align: center;
        }

        .login-wrapper > .layui-form > .layui-form-item.login-captcha-group {
            padding-right: 135px;
        }

        .login-wrapper > .layui-form > .layui-form-item.login-captcha-group > .login-captcha {
            height: 46px;
            width: 120px;
            cursor: pointer;
            box-sizing: border-box;
            border: 1px solid #e6e6e6;
            border-radius: 2px !important;
            position: absolute;
            right: 0;
            top: 0;
        }

        .login-wrapper > .layui-form > .layui-form-item > .layui-form-checkbox {
            margin: 0 !important;
            padding-left: 25px;
        }

        .login-wrapper > .layui-form > .layui-form-item > .layui-form-checkbox > .layui-icon {
            width: 15px !important;
            height: 15px !important;
        }

        .login-wrapper > .layui-form .layui-btn-fluid {
            height: 48px;
            line-height: 48px;
            font-size: 16px;
            border-radius: 2px !important;
        }

        .login-wrapper > .layui-form > .layui-form-item.login-oauth-group > a > .layui-icon {
            font-size: 26px;
        }

        .login-copyright {
            color: #eee;
            padding-bottom: 20px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        @media screen and (min-height: 550px) {
            .login-wrapper {
                margin: -250px auto 0;
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                width: 100%;
            }

            .login-copyright {
                display: none;
                position: absolute;
                bottom: 0;
                right: 0;
                left: 0;
            }
        }

        .layui-btn {
            background-color: #5FB878;
            border-color: #5FB878;
        }

        .layui-link {
            color: #5FB878 !important;
        }
    </style>
@endsection
@section('content')
    <div class="login-wrapper layui-anim layui-anim-scale layui-hide">
        <form class="layui-form">
            <h2>用户登录</h2>
            <div class="layui-form-item layui-input-icon-group">
                <i class="layui-icon layui-icon-username"></i>
                <input class="layui-input" name="name" minlength="3" maxlength="16" placeholder="请输入登录账号" autocomplete="off"
                       lay-verType="tips" lay-verify="required|h5" required/>
            </div>
            <div class="layui-form-item layui-input-icon-group">
                <i class="layui-icon layui-icon-password"></i>
                <input class="layui-input" name="password" minlength="6" maxlength="16" placeholder="请输入登录密码" type="password"
                       lay-verType="tips" lay-verify="required|h5" required/>
            </div>
            <div class="layui-form-item layui-input-icon-group login-captcha-group">
                <i class="layui-icon layui-icon-auz"></i>
                <input class="layui-input" name="captcha" placeholder="请输入验证码" autocomplete="off"
                       lay-verType="tips" lay-verify="required" required/>
                <img class="login-captcha" alt=""/>
            </div>
            <div class="layui-form-item">
                <input type="checkbox" name="remember" title="记住密码" lay-skin="primary">
                <a href="{{route('qaecmsindex.reg')}}" class="layui-link pull-right">注册账号</a>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" id="BtnAction" lay-filter="loginSubmit" lay-submit>登录</button>
            </div>
{{--            <div class="layui-form-item login-oauth-group text-center">--}}
{{--                <a href="javascript:;"><i class="layui-icon layui-icon-login-qq" style="color:#3492ed;"></i></a>&emsp;--}}
{{--                <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat" style="color:#4daf29;"></i></a>&emsp;--}}
{{--                <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo" style="color:#CF1900;"></i></a>--}}
{{--            </div>--}}
        </form>
    </div>
    <div class="login-copyright">copyright © 2020 {{$__WEBDOMIN__}} all rights reserved.</div>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'form','admin'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var admin = layui.admin;
            var form = layui.form;
            var captchaUrl = "{{captcha_src('flat')}}";
            $('.login-wrapper').removeClass('layui-hide');
            $('img.login-captcha').attr('src',captchaUrl);
            /* 表单提交 */
            form.on('submit(loginSubmit)', function (obj) {
                admin.btnLoading('#BtnAction')
                let url = "{{route('qaecmsindex.login')}}"
                admin.req(url,obj.field,function (res) {
                    admin.btnLoading('#BtnAction',false)
                    if(res.hasOwnProperty('error')){
                        layer.msg('登录信息或验证码不正确')
                        $('img.login-captcha').attr('src',captchaUrl + '=' + (new Date).getTime());
                    }else{
                        layer.msg(res.msg);
                    }
                    if (res.status == 200) {
                        setTimeout(function () {
                            location.replace("{{route('qaecmsindex.user')}}")
                        },500)
                    }
                },'post')
                return false;
            });

            /* 图形验证码 */
            $('img.login-captcha').click(function () {
                $(this).attr('src',captchaUrl + '=' + (new Date).getTime());
            })

        });
    </script>
@endsection
