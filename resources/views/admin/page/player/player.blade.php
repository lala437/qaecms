@extends('admin.layout.layout')
@section('title','播放器列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加播放器</button>
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
                    <label class="layui-form-label layui-form-required">名称:</label>
                    <div class="layui-input-block">
                        <input name="name" placeholder="请输入播放器名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                    </div>
                </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">类型:</label>
                <div class="layui-input-block">
                    <select name="type" lay-verType="tips" lay-verify="required" required>
                        <option value="zhilian">直接播放</option>
                        <option value="guanfang">官方解析</option>
                        <option value="m3u8">M3U8</option>
                        <option value="mp4">MP4</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">地址:</label>
                <div class="layui-input-block">
                    <input name="url" placeholder="请输入播放器地址 可以留空" class="layui-input" lay-verType="tips"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">排序:</label>
                <div class="layui-input-block">
                    <input name="sort" placeholder="数字越大越靠前" class="layui-input" lay-verType="tips"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" checked title="开启">
                    <input type="radio" name="status" value="0" title="关闭">
                </div>
            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit" lay-submit>提交</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表格操作列 -->
    <script type="text/html" id="tableBar">
        <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="edit"><i class="layui-icon">&#xe642;</i>编辑</a>
        <a class="layui-btn layui-btn-sm" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</a>
    </script>
@endsection
@section('js')
    <script>
        layui.use(['layer','admin','form','table'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var admin = layui.admin;
            var form = layui.form;
            var table = layui.table;
            var player,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.player',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'name', align: "center", title: '名称'},
                    {field: 'type',  align: "center", title: '类型',templet:function (dd) {
                            let types = {zhilian:"直接播放",m3u8:"M3U8",mp4:"MP4",guanfang:"官方解析"};
                            return types[dd.type]
                        }},
                    {field: 'url',  align: "center", title: '地址'},
                    {field: 'sort',  align: "center", title: '排序'},
                    {field: 'status',  width:80,align: "center", title: '状态',templet:function (dd) {
                              let status = ['关闭','开启'];
                              return status[dd.status];
                        }},
                    {field: 'created_at',  align: "center", title: '创建时间'},
                    {title: '操作', width:300, toolbar: '#tableBar', align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加播放器
            $('#AddDataBtn').click(function () {
                action = 'add';
                playeraddorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function playeraddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加播放器':"编辑播放器",
                    area: ['500px','400px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar :false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow','visible');
                        //渲染时间
                        if(type=="update"){
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                name:data.name,
                                type:data.type,
                                url:data.url,
                                sort:data.sort,
                                status:data.status,
                            })
                        }
                        form.render();
                    }
                });
            }

            //提交数据
            form.on("submit(DataFormSubmit)", function (obj) {
                layer.confirm("确认提交?", function (index) {
                    layer.load(2);
                    let data = obj.field;
                    let url = "{{route('qaecmsadmin.player',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url,{data:data},function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                        switch (action) {
                            case "add":
                                if (res.status == 200) {
                                    table.reload('DataTable')
                                }
                                break;
                            case "update":
                                if (res.status == 200) {
                                    player.update({
                                        name:data.name,
                                        type:data.type,
                                        url:data.url,
                                        sort:data.sort,
                                        status:data.status,
                                    })
                                }
                                break;

                        }
                    },'post')
                })
                return false;
            })

            // 工具条点击事件
            table.on('tool(DataTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "edit":
                        player = obj,
                            action = "update",
                            playeraddorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.player',['action'=>'delete'],false)}}"
                            admin.req(url,{data: {id:data.id}},function (res) {
                                layer.closeAll('loading');
                                if(res.status==200){
                                    obj.del();
                                }
                                layer.msg(res.msg);
                            },'post')
                        });
                        break;
                }
            });
        });
    </script>
@endsection
