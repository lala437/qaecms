@extends('admin.layout.layout')
@section('title','网站设置')
@section('css')
    <style>
        #formBasForm {
            max-width: 700px;
            margin: 30px auto;
        }

        #formBasForm .layui-form-item {
            margin-bottom: 25px;
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <!-- 表单开始 -->
                <form class="layui-form" id="formBasForm" lay-filter="formBasForm">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">名称:</label>
                        <div class="layui-input-block">
                            <input name="name" placeholder="给网站起一个名吧" class="layui-input"
                                  value="{{$__WEBNAME__}}"  lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">副标题:</label>
                        <div class="layui-input-block">
                            <input name="subtitle" placeholder="给网站起一个副标题吧" class="layui-input"
                                   value="{{$__WEBSUBTITLE__}}"  lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">域名:</label>
                        <div class="layui-input-block">
                            <input name="domin" placeholder="请输入网站域名 不包含http/https" class="layui-input"
                                   value="{{$__WEBDOMIN__}}"    lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">logo:</label>
                        <div class="layui-input-block">
                            <input name="logo" placeholder="请输入logo地址" class="layui-input"
                                   value="{{$__WEBLOGO__}}"   lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">ICP:</label>
                        <div class="layui-input-block">
                            <input name="icp" placeholder="请输入ICP备案信息" class="layui-input"
                                   value="{{$__WEBICP__}}"   lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">email:</label>
                        <div class="layui-input-block">
                            <input name="email" placeholder="请输入站长邮箱" class="layui-input"
                                   value="{{$__WEBEMAIL__}}"    lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">联系方式:</label>
                        <div class="layui-input-block">
                            <input name="contact" placeholder="请输入其他联系方式" class="layui-input"
                                   value="{{$__WEBCONTACT__}}"   lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">统计API:</label>
                        <div class="layui-input-block">
                            <textarea name="statistic" placeholder="统计API" class="layui-textarea" value="{!!qaecms('statistic')!!}"   lay-verType="tips"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">网站模板:</label>
                        <div class="layui-input-block">
                            <select name="template" lay-verType="tips" lay-verify="required" required>
                                @foreach($templates as $template)
                                    <option value="{{$template}}" {{$__WEBTEMPLATE__==$template?"selected":""}}>{{$template}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">网站状态:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="公开" {{$__WEBSTATUS__==1?"checked":""}}>
                            <input type="radio" name="status" value="0" title="关闭" {{$__WEBSTATUS__==0?"checked":""}}>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" id="BtnAction" lay-filter="formBasSubmit" lay-submit>&emsp;更新&emsp;</button>
                            <button type="reset" class="layui-btn layui-btn-primary">&emsp;重置&emsp;</button>
                        </div>
                    </div>
                </form>
                <!-- //表单结束 -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form','admin'], function () {
             var $ = layui.jquery,
                admin = layui.admin,
                form = layui.form;


            /* 监听表单提交 */
            form.on('submit(formBasSubmit)', function (data) {
                admin.btnLoading('#BtnAction')
                let url = "{{route('qaecmsadmin.webconfig')}}"
                admin.req(url,{data:data.field},function (res) {
                    admin.btnLoading('#BtnAction',false)
                    layer.msg(res.msg)
                },'post')
                return false;
            });

        });
    </script>
@endsection
