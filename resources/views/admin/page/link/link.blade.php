@extends('admin.layout.layout')
@section('title','友链列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加友情链接</button>
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
                <label class="layui-form-label layui-form-required">友链名称:</label>
                <div class="layui-input-block">
                    <input name="name" placeholder="请输入友链名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">友链地址:</label>
                <div class="layui-input-block">
                    <input name="link" placeholder="请输入友链地址" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">友链排序:</label>
                <div class="layui-input-block">
                    <input name="sort" placeholder="请输入友链排序" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="显示">
                    <input type="radio" name="status" value="0" title="隐藏" checked>
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
        <a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="detect"><i class="layui-icon">&#xe615;</i>检测</a>
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
            var link,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.link',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'name', align: "center", title: '名称'},
                    {field: 'link', align: "center", title: '地址'},
                    {field: 'sort', align: "center", title: '排序'},
                    {field: 'status',  align: "center", title: '状态',templet:function (dd) {
                             return dd.status==1?"显示":"隐藏"
                        }},
                    {title: '操作',  toolbar: '#tableBar', width:300,align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加友链
            $('#AddDataBtn').click(function () {
                action = 'add';
                linkaddorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function linkaddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加友链':"编辑友链",
                    area: ['500px','400px'],
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
                                name:data.name,
                                link:data.link,
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
                    let url = "{{route('qaecmsadmin.link',['_time'=>time()],false)}}&action=" + action;
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
                                    link.update({
                                        name:data.name,
                                        link:data.link,
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
                    case "detect":
                        layer.load(2);
                        let url = "{{route('qaecmsadmin.link',['action'=>'detect'],false)}}";
                        admin.req(url,{data: {id:data.id}},function (res) {
                            layer.closeAll('loading');
                            if(res.status==200){
                                layer.msg(res.msg,{icon:6})
                            }else{
                                layer.msg(res.msg,{icon:5})
                            }
                        },'post')
                    break;
                    case "edit":
                        link = obj,
                            action = "update",
                            linkaddorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.link',['action'=>'delete'],false)}}"
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
            /* 点击图片放大 */
            $(document).off('click.tbImg').on('click.tbImg', '[tb-img]', function () {
                let imgList = table.cache['DataTable'].map(function (d) {
                    return {
                        alt: d.name,
                        src: d.image
                    }
                });
                layer.photos({photos: {data: imgList, start: $(this).data('index')}, shade: .1, closeBtn: true});
            });
        });
    </script>
@endsection
