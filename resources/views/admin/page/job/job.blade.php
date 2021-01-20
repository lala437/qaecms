@extends('admin.layout.layout')
@section('title','采集列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加采集</button>
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
                        <input name="name" placeholder="请输入采集名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                    </div>
                </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">分类:</label>
                <div class="layui-input-block">
                    <select name="method" lay-verType="tips" lay-verify="required" required>
                        <option value="video">采集视频</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">API:</label>
                <div class="layui-input-block">
                    <input name="api" placeholder="请输入采集API,目前只支持xml格式接口" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
        <a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="bindtype"><i class="layui-icon">&#xe653;</i>绑定分类</a>
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="exc"><i class="layui-icon">&#xe62c;</i>执行</a>
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
            var job,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.job',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'name', align: "center", title: '名称'},
                    {field: 'api',  align: "center", title: 'API'},
                    {field: 'lasttime',  align: "center", title: '最近执行时间'},
                    {field: 'status',  width:100,align: "center", title: '状态',templet:function (dd) {
                              let status = ['关闭','开启'];
                              return status[dd.status];
                        }},
                    {field: 'bindstatus',  width:100,align: "center", title: '绑定状态',templet:function (dd) {
                            let bindstatus = ['未绑定','已绑定'];
                            return bindstatus[dd.bindstatus];
                        }},
                    {title: '操作', width:350, toolbar: '#tableBar', align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加采集
            $('#AddDataBtn').click(function () {
                action = 'add';
                jobaddorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function jobaddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    scrollbar: false,
                    title: type=='add'?'添加采集':"编辑采集",
                    area: ['500px','350px'],
                    offset: 'auto',
                    fixed: true,
                    content: $('#DataFormView').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow', 'visible');
                        if(type=="update"){
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                name:data.name,
                                method:data.method,
                                api:data.api,
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
                    let url = "{{route('qaecmsadmin.job',['_time'=>time()],false)}}&action=" + action;
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
                                let method = $('[name=method] option:selected').text();
                                if (res.status == 200) {
                                    job.update({
                                        name:data.name,
                                        method:{id:data.method,name:method},
                                        api:data.api,
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
                    case "bindtype":
                        layer.load(2)
                        let url = "{{route('qaecmsadmin.job',['action'=>'bindtype'],false)}}";
                        admin.req(url, {data: {id: data.id}}, function (res) {
                            layer.closeAll('loading');
                            if (res.status == 200) {
                                layer.msg('绑定成功'+(res.faild.length>0?",但还有部分分类未绑定成功,请执行采集后,在数据入库选项中做手动绑定":""))
                                table.reload('DataTable');
                            } else {
                                layer.msg('绑定失败');
                            }
                        }, 'post')
                        break;
                    case "exc":
                        if(data.bindstatus==0){
                            layer.msg("请先绑定分类")
                            return false;
                        }
                        layer.confirm("确认执行?",function (index) {
                            let url = "{{route('qaecmsadmin.job',['_time'=>time()],false)}}&action=exc&id="+data.id;
                            layer.close(index)
                            admin.open({
                                type: 2,
                                title: "进度",
                                scrollbar: false,
                                area: ['500px', '250px'],
                                offset: 'auto',
                                content:[url,'no'],
                                end:function () {
                                    table.reload('DataTable')
                                }
                            });
                        })
                        break;
                    case "edit":
                        job = obj,
                            action = "update",
                            jobaddorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.job',['action'=>'delete'],false)}}"
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
