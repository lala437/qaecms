<form method="POST" action="{{route('qaecmsindex.comments')}}" class="layui-form" accept-charset="UTF-8">
    {{csrf_field()}}
    @if(isset($pid))
        <input type="hidden" name="pid" value="{{$pid}}">
    @endif
        <div class="layui-form-item">
            <textarea id="body" name="content" style="height: 180px" class="layui-textarea" required="required"></textarea>
        </div>
        <div class="layui-form-item login-captcha-group">
            <input class="layui-input" name="captcha" placeholder="请输入验证码" autocomplete="off" required/>
            <img class="login-captcha" alt=""/>
        </div>
    <div>
        <button type="submit" class="btn btn-success">回复</button>
    </div>
</form>
