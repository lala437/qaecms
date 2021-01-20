@extends('admin.layout.layout')
@section('title','缓存设置')
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
                        <li class="layui-this">缓存设置</li>
                        <li>清理缓存</li>
                    </ul>
                    <div class="layui-tab-content">
                        <!-- tab1 -->
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" id="cache" lay-filter="mapay">
{{--                                <div class="layui-form-item">--}}
{{--                                    <label class="layui-form-label layui-form-required">过期时间:</label>--}}
{{--                                    <div class="layui-input-block">--}}
{{--                                        <input name="arg1" value="{{$cache->arg1??""}}" placeholder="缓存过期时间"  class="layui-input" lay-verify="required" required/>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="layui-form-item">
                                    <label class="layui-form-label">全站缓存:</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="开启" {{isset($cache->status)&&$cache->status==1?"checked":""}}>
                                        <input type="radio" name="status" value="0" title="关闭" {{isset($cache->status)&&$cache->status==0?"checked":""}}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" id="CacheBtnAction" lay-filter="formCacheSubmit" lay-submit>更新配置</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="layui-tab-item">
                             <div class="layui-input-inline">
                                 <button class="layui-btn" id="ClearBtnAction">清理缓存</button>
                             </div>
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
        layui.use(['form', 'admin', 'element'], function () {
            var $ = layui.jquery,
                admin = layui.admin,
                element = layui.element,
                form = layui.form;


            /* 监听表单提交 */
            form.on('submit(formCacheSubmit)', function (data) {
                admin.btnLoading('#CacheBtnAction')
                let url = "{{route('qaecmsadmin.cache',['action'=>'cacheconfig'])}}"
                admin.req(url, {data: data.field}, function (res) {
                    admin.btnLoading('#CacheBtnAction', false)
                    layer.msg(res.msg)
                }, 'post')
                return false;
            });

            $('#ClearBtnAction').click(function () {
                admin.btnLoading('#ClearBtnAction')
                let url = "{{route('qaecmsadmin.cache',['action'=>'clearcache'])}}"
                admin.req(url, {data: 1}, function (res) {
                    admin.btnLoading('#ClearBtnAction', false)
                    layer.msg(res.msg)
                }, 'post')
            })

        });
    </script>
@endsection
