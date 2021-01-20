@extends('admin.layout.layout')
@section('title','轮播设置')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加轮播</button>
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
                <label class="layui-form-label layui-form-required">标题:</label>
                <div class="layui-input-block">
                    <input name="title" placeholder="请输入轮播标题" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">地址:</label>
                <div class="layui-input-block">
                    <input name="href" placeholder="请输入轮播地址" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">图片:</label>
                <div class="layui-input-block">
                    <input name="image" placeholder="请输入轮播图片地址" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">位置:</label>
                <div class="layui-input-block">
                    <select name="location" lay-verType="tips" lay-verify="required" required>
                        @foreach(config('system.carousel_location') as $key=>$name)
                            <option value="{{$key}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">排序:</label>
                <div class="layui-input-block">
                    <input name="sort" placeholder="请输入轮播排序,数字越大越靠前" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
        layui.use(['layer','laydate','admin','form','table'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var laydate = layui.laydate;
            var admin = layui.admin;
            var form = layui.form;
            var table = layui.table;
            var carousel,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.carousel',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'title', align: "center", title: '名称'},
                    {field: 'href', align: "center", title: '地址'},
                    {field: 'image', align: "center", title: '图片'},
                    {field: 'location', align: "center", title: '位置'},
                    {field: 'status', align: "center", title: '状态',templet:function (dd) {
                               return dd.status==0?"关闭":"开启"
                        }},
                    {title: '操作',  toolbar: '#tableBar', align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加任务
            $('#AddDataBtn').click(function () {
                action = 'add';
                carouseladdorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function carouseladdorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加轮播':"编辑轮播",
                    area: ['500px','500px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar :false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow');
                        if(type=="update"){
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                title:data.title,
                                href:data.href,
                                image:data.image,
                                location:data.location,
                                status:data.status,
                                sort:data.sort,
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
                    let url = "{{route('qaecmsadmin.carousel',['_time'=>time()],false)}}&action=" + action;
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
                                    carousel.update({
                                        title:data.title,
                                        href:data.href,
                                        image:data.image,
                                        location:data.location,
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
                        carousel = obj,
                            action = "update",
                            carouseladdorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.carousel',['action'=>'delete'],false)}}"
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
