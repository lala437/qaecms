@extends('admin.layout.layout')
@section('title','支付设置')
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
        <div class="layui-col-sm12 layui-col-md12">
            <div class="layui-card" style="padding-top: 25px;">
                <!-- 选项卡开始 -->
                <div class="layui-tab layui-tab-brief" lay-filter="userInfoTab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">码支付设置</li>
                    </ul>
                    <div class="layui-tab-content">
                        <!-- tab1 -->
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" id="mapay" lay-filter="mapay">
                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">商户ID:</label>
                                    <div class="layui-input-block">
                                        <input name="arg1" value="{{$mapay->arg1??""}}" placeholder="请输入商户ID" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">通讯密钥</label>
                                    <div class="layui-input-block">
                                        <input name="arg2" value="{{$mapay->arg2??""}}" placeholder="请输入通讯密钥" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">状态:</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="开启" {{isset($mapay->status)&&$mapay->status==1?"checked":""}}>
                                        <input type="radio" name="status" value="0" title="关闭" {{isset($mapay->status)&&$mapay->status==0?"checked":""}}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" id="MaBtnAction" lay-filter="formMaPaySubmit" lay-submit>更新配置</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- //选项卡结束 -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form','admin','element'], function () {
            var $ = layui.jquery,
                admin = layui.admin,
                element = layui.element,
                form = layui.form;


            /* 监听表单提交 */
            form.on('submit(formMaPaySubmit)', function (data) {
                admin.btnLoading('#MaBtnAction')
                let url = "{{route('qaecmsadmin.payconfig',['type'=>'mapay'])}}"
                admin.req(url,{data:data.field},function (res) {
                    admin.btnLoading('#MaBtnAction',false)
                    layer.msg(res.msg)
                },'post')
                return false;
            });

        });
    </script>
@endsection
