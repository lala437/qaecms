@extends('admin.layout.layout')
@section('title','定时任务列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加任务</button>
            </div>
            <div class="layui-card-body">
                <table id="DataTable" lay-filter="DataTable"></table>
            </div>
        </div>
    </div>

    <script type="text/html" id="DataFormView">
        <form id="DataForm" lay-filter="DataForm" class="layui-form model-form layui-row">
            <input name="id" type="hidden"/>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">任务:</label>
                <div class="layui-input-block">
                    <select name="task" lay-verType="tips" lay-verify="required" required>
                        @foreach(config('task') as $key=>$task)
                        <option value="{{$key}}">{{$task}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" checked title="开启">
                    <input type="radio" name="status" value="0" title="关闭">
                </div>
            </div>
            <div class="layui-form-item text-right model-form-footer">
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
            var ad,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.task',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'task', align: "center", title: '任务',templet:function (dd) {
                            let tasks = @json(config('task'));
                            return tasks[dd.task];
                        }},
                    {field: 'command', align: "center", title: '命令'},
                    {field: 'lasttime', align: "center", title: '上次执行时间'},
                    {field: 'status',  align: "center", title: '状态',templet:function (dd) {
                            return dd.status==1?"开启":"关闭"
                        }},
                    {title: '操作',  toolbar: '#tableBar', width:300,align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加
            $('#AddDataBtn').click(function () {
                action = 'add';
                taskaddorupdate('add');
            });

            //打开添加或者编辑视窗口
            function taskaddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加任务':"编辑任务",
                    area: ['500px','250px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar :false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        //渲染时间
                        if(type=="update"){
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                task:data.task,
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
                    let url = "{{route('qaecmsadmin.task',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url,{data:data},function (res) {
                        layer.closeAll('loading');
                        if(res.hasOwnProperty('error')){
                            layer.msg('任务添加重复')
                            return false;
                        }
                        layer.msg(res.msg)
                        switch (action) {
                            case "add":
                                if (res.status == 200) {
                                    table.reload('DataTable')
                                }
                                break;
                            case "update":
                                if (res.status == 200) {
                                    ad.update({
                                        task:data.task,
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
                        ad = obj,
                            action = "update",
                            taskaddorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.task',['action'=>'delete'],false)}}"
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
