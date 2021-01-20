@extends('admin.layout.layout')
@section('title','搜索设置')
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
                        <li class="layui-this">Algolia设置</li>
                        <li>同步全文搜索(开启时必须点击)</li>
                    </ul>
                    <div class="layui-tab-content">
                        <!-- tab1 -->
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" id="search" lay-filter="search">
                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">APP_ID:</label>
                                    <div class="layui-input-block">
                                        <input name="arg1" value="{{$algolia->arg1??"default"}}" placeholder="请输入Algolia APP_ID" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label layui-form-required">APP_KEY</label>
                                    <div class="layui-input-block">
                                        <input name="arg2" value="{{$algolia->arg2??"default"}}" placeholder="请输入Algolia ADMIN_KEY" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">全文搜索:</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="开启" {{isset($algolia->status)&&$algolia->status==1?"checked":""}}>
                                        <input type="radio" name="status" value="0" title="关闭" {{isset($algolia->status)&&$algolia->status==0?"checked":""}}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" id="SearchBtnAction" lay-filter="formSearchSubmit" lay-submit>更新配置</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-input-inline">
                                <button class="layui-btn" id="SyncBtnAction">同步数据</button>
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
        layui.use(['form','admin','element'], function () {
            var $ = layui.jquery,
                admin = layui.admin,
                element = layui.element,
                form = layui.form;


            /* 监听表单提交 */
            form.on('submit(formSearchSubmit)', function (data) {
                admin.btnLoading('#SearchBtnAction')
                let url = "{{route('qaecmsadmin.searchconfig',['type'=>"algolia"])}}"
                admin.req(url,{data:data.field},function (res) {
                    admin.btnLoading('#SearchBtnAction',false)
                    layer.msg(res.msg)
                },'post')
                return false;
            });

            $('#SyncBtnAction').click(function () {
                admin.btnLoading('#SyncBtnAction')
                let url = "{{route('qaecmsadmin.searchconfig',['type'=>'sync'])}}"
                admin.req(url, {data: 1}, function (res) {
                    admin.btnLoading('#SyncBtnAction', false)
                    layer.msg(res.msg)
                }, 'post')
            })

        });
    </script>
@endsection
