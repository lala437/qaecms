@extends('admin.layout.layout')
@section('title','评论列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">留言板列表</div>
            <div class="layui-card-body">
                <form class="layui-form toolbar" lay-filter="DataSearch">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label w-auto">关键字:</label>
                            <div class="layui-input-inline mr0">
                                <input name="name" class="layui-input" type="text" placeholder="请输入关键字"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn icon-btn" lay-filter="DataSearchSubmit" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="CleanAll" class="layui-btn">清除全部</button>
            </div>
            <div class="layui-card-body">
                <table id="DataTable" lay-filter="DataTable"></table>
            </div>
        </div>
    </div>
    {{--    文章添加修改页面--}}
    <script type="text/html" id="DataFormView">
        <form id="DataForm" lay-filter="DataForm" class="layui-form model-form layui-row">
            <input name="id" type="hidden"/>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">回复内容:</label>
                <div class="layui-input-block">
                    <textarea name="content" placeholder="请输入回复内容" class="layui-textarea" lay-verType="tips" lay-verify="required" required></textarea>
                </div>
            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit" lay-submit>回复</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表格操作列 -->
    <script type="text/html" id="tableBar">
        <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="reply"><i class="layui-icon">&#xe642;</i>回复</a>
        <a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="replylist"><i class="layui-icon">&#xe648;</i>回复列表</a>
        <a class="layui-btn layui-btn-sm" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</a>
    </script>
    <script type="text/html" id="replyList">
        <table id="ReplyTable" lay-filter="ReplyTable"></table>
    </script>
    <script type="text/html" id="ReplytableBar">
        <a class="layui-btn layui-btn-sm" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</a>
    </script>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'admin', 'form', 'table'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var admin = layui.admin;
            var form = layui.form;
            var table = layui.table;
            var comment, action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.comment',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'id', width: 50, align: "center", title: 'ID'},
                    {field: 'content', align: "left", title: '内容'},
                    {field: 'created_at', width: 200, align: "center", title: '创建时间'},
                    {title: '操作', toolbar: '#tableBar', width: 300, align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 监听搜索操作
            form.on('submit(DataSearchSubmit)', function (data) {
                let result = JSON.stringify(data.field);
                //执行搜索重载
                table.reload('DataTable', {
                    page: {
                        curr: 1
                    }
                    , where: {
                        params: result
                    }
                }, 'data');

                return false;
            });

            $('#CleanAll').click(function () {
                layer.confirm("确认清空?", function () {
                    layer.load(2)
                    let url = "{{route('qaecmsadmin.comment',['action'=>'clean'])}}";
                    $.get(url, function (res) {
                        table.reload("DataTable")
                        layer.msg(res.msg)
                        layer.closeAll("loading")
                    })
                })
            })

            //打开添加或者编辑视频窗口
            function commentform(type, dataobj = null) {
                admin.open({
                    type: 1,
                    title: "回复留言",
                    area: ['500px', '250px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar: false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        let data = dataobj.data;
                        form.val("DataForm", {
                            id: data.id
                        })
                        form.render();
                    }
                });
            }

            function openreplylist(id) {
                let w = ($(window).width()) * 0.8;
                let h = ($(window).height()) * 0.7;
                admin.open({
                    type: 1,
                    title: "已回复列表",
                    area: [w + 'px', h + 'px'],
                    offset: 'auto',
                    content: $('#replyList').html(),
                    scrollbar: false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        // 渲染表格
                        table.render({
                            elem: '#ReplyTable',
                            url: "{{route('qaecmsadmin.comment',['action'=>'list'])}}&id=" + id,
                            page: true,
                            cellMinWidth: 100,
                            cols: [[
                                {field: 'id', width: 50, align: "center", title: 'ID'},
                                {field: 'content', align: "left", title: '内容'},
                                {field: 'created_at', width: 200, align: "center", title: '创建时间'},
                                {title: '操作', toolbar: '#ReplytableBar', width: 300, align: "center"}
                            ]],
                            size: 'lg',
                            limits: [10, 15, 20, 25, 50, 100],
                            limit: 15,
                        });
                        form.render();
                    }
                });
            }

            //提交数据
            form.on("submit(DataFormSubmit)", function (obj) {
                layer.confirm("确认提交?", function (index) {
                    layer.load(2);
                    let data = obj.field;
                    let url = "{{route('qaecmsadmin.comment',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                        table.reload('DataTable')
                    }, 'post')
                })
                return false;
            })

            // 工具条点击事件
            table.on('tool(DataTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "reply":
                        comment = obj;
                        action = "reply";
                        commentform('reply', obj)
                        break;
                    case "replylist":
                        openreplylist(data.id)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.comment',['action'=>'delete'],false)}}"
                            admin.req(url, {data: {id: data.id}}, function (res) {
                                layer.closeAll('loading');
                                table.reload('DataTable')
                                layer.msg(res.msg);
                            }, 'post')
                        });
                        break;
                }
            });

            table.on('tool(ReplyTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.comment',['action'=>'delete'],false)}}"
                            admin.req(url, {data: {id: data.id}}, function (res) {
                                layer.closeAll('loading');
                                table.reload('ReplyTable')
                                layer.msg(res.msg);
                            }, 'post')
                        });
                        break;
                }
            });
        });
    </script>
@endsection
