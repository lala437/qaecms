@extends('admin.layout.layout')
@section('title','seo设置')
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
                        <label class="layui-form-label layui-form-required">关键字:</label>
                        <div class="layui-input-block">
                            <input name="keywords" placeholder="请输入关键字,多个关键字用逗号隔开." class="layui-input"
                                   value="{{$__SEOKEYWORDS__}}"  lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">图片ALT:</label>
                        <div class="layui-input-block">
                            <input name="picalt" placeholder="请输入图片ALT" class="layui-input"
                                   value="{{$__SEOPICALT__}}"  lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">描述:</label>
                        <div class="layui-input-block">
                            <input name="description" placeholder="请输入网站描述" class="layui-input"
                                   value="{{$__SEODESCRIPTION__}}"    lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">NOFOLLOW:</label>
                        <div class="layui-input-block">
                            <input type="radio" name="nofollow" value="1" title="开启" {{$__SEONOFOLLOW__=="nofollow"?"checked":""}}>
                            <input type="radio" name="nofollow" value="0" title="关闭" {{$__SEONOFOLLOW__==""?"checked":""}}>
                            <div class="layui-word-aux">告诉搜索引擎"不要追踪此网页上的链接或不要追踪此特定链接"</div>
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
                let url = "{{route('qaecmsadmin.seoconfig')}}"
                admin.req(url,{data:data.field},function (res) {
                    admin.btnLoading('#BtnAction',false)
                    layer.msg(res.msg)
                },'post')
                return false;
            });

        });
    </script>
@endsection
