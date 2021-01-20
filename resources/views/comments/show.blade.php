<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('assets/libs/layui/css/layui.css')}}"/>
    <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{asset('assets/libs/jquery/jquery-3.2.1.min.js')}}"></script>
    <style>
        .login-captcha-group{
            padding-right: 135px;
            position: relative;
        }
        .login-captcha{
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
        .login-wrapper {
            position: relative;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="layui-container" style="margin-top: 50px">
    <div class="login-wrapper">
        <h3>留言板</h3>
        @include('comments.form')
        <hr>
        @if(filled($comments))
        @include('comments.list',['collections'=>$comments['root']])
        @endif
    </div>
</div>
</body>
<script>
    $(function () {
        var captchaUrl = "{{captcha_src('flat')}}";
        $('img.login-captcha').attr('src',captchaUrl);
        /* 图形验证码 */
        $('img.login-captcha').click(function () {
            $(this).attr('src',captchaUrl + '=' + (new Date).getTime());
        })
    })
</script>
</html>
