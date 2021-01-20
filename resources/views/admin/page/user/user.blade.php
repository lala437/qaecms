@extends('admin.layout.layout')
@section('title','采集管理')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加会员</button>
            </div>
            <div class="layui-card-body">
                <table id="DataTable" lay-filter="DataTable"></table>
            </div>
        </div>
    </div>
    {{--    用户添加修改页面--}}
    <script type="text/html" id="DataFormView">
        <form id="DataForm" lay-filter="DataForm" class="layui-form model-form layui-row">
            <input name="id" type="hidden"/>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">用户名:</label>
                <div class="layui-input-block">
                    <input name="name" placeholder="请输入用户名" class="layui-input" lay-verType="tips" maxlength="16" minlength="3" lay-verify="required|h5" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">密码:</label>
                <div class="layui-input-block">
                    <input name="password" type="password"  placeholder="请输入密码" class="layui-input" maxlength="16" minlength="6" lay-verType="tips" lay-verify="required|h5"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">昵称:</label>
                <div class="layui-input-block">
                    <input name="nick" placeholder="请输入昵称" class="layui-input" maxlength="16" minlength="2" lay-verType="tips" lay-verify="required|h5" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">邮箱:</label>
                <div class="layui-input-block">
                    <input name="email" type="email" placeholder="请输入邮箱" class="layui-input" maxlength="30" minlength="5" lay-verType="tips" lay-verify="required|h5" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">VIP:</label>
                <div class="layui-input-block">
                    <select name="vip" lay-verType="tips" lay-verify="required" required>
                        <option value="0">普通用户</option>
                        <option value="1">VIP1</option>
                        <option value="2">VIP2</option>
                        <option value="3">VIP3</option>
                        <option value="4">VIP4</option>
                        <option value="5">VIP5</option>
                        <option value="6">VIP6</option>
                        <option value="7">VIP7</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">VIP到期时间:</label>
                <div class="layui-input-block">
                    <input name="vip_endtime" placeholder="请输入VIP到期时间" value=""  id="vip_endtime" class="layui-input" lay-verType="tips"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">积分:</label>
                <div class="layui-input-block">
                    <input name="integral" placeholder="请输入积分" value="0" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="可用"  checked>
                    <input type="radio" name="status" value="0" title="不可用">
                </div>
            </div>
            <div class="layui-form-item text-right" style="margin-top: 50px">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit" lay-submit>提交</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表格操作列 -->
    @verbatim
    <script type="text/html" id="tableBar">
        <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="edit"><i class="layui-icon">&#xe642;</i>编辑</a>
        {{# if(d.status==0){ }}
        <a class="layui-btn layui-btn-sm" lay-event="status"><i class="layui-icon">&#xe605;</i>启用</a>
        {{# }else{ }}
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="status"><i class="layui-icon">&#x1006;</i>禁用</a>
        {{# } }}
    </script>
    @endverbatim
@endsection
@section('js')
    <script>
        layui.use(['layer','admin','form','table','laydate','formX'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var formX = layui.formX;
            var admin = layui.admin;
            var form = layui.form;
            var table = layui.table;
            var laydate = layui.laydate;
            var user,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.user',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {type:'checkbox'},
                    {field: 'name', align: "center", title: '用户名'},
                    {field: 'nick',  align: "center", title: '昵称'},
                    {field: 'email',  align: "center", title: '邮箱'},
                    {field: 'vip',  align: "center", title: 'VIP',templet:function (dd) {
                          return dd.vip==0?"普通用户":"VIP"+dd.vip;
                        }},
                    {field: 'vip_endtime',  align: "center", title: 'VIP到期时间'},
                    {field: 'integral',  align: "center", title: '积分'},
                    {field: 'status',  align: "center", title: '状态',templet:function (dd) {
                             let statusarr = ['不可用','可用']
                             return statusarr[dd.status];
                        }},
                    {field: 'created_at',  align: "center", title: '创建时间'},
                    {title: '操作',  toolbar: '#tableBar',width:200, align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加数据绑定
            $('#AddDataBtn').click(function () {
                action = 'add';
                useraddorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function useraddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加用户':"编辑用户",
                    area: ['500px','600px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar: false,
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow','visible');
                        $("input[name=password]").attr("lay-verify","required")
                        laydate.render({
                            elem: '#vip_endtime'
                            ,type: 'datetime'
                        });
                        if(type=="update"){
                            $("input[name=password]").attr("lay-verify","")
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                name:data.name,
                                nick:data.nick,
                                vip:data.vip,
                                vip_endtime:data.vip_endtime,
                                email:data.email,
                                integral:data.integral,
                                status:data.status
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
                    let url = "{{route('qaecmsadmin.user',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        if (res.hasOwnProperty('error')) {
                            let error = res.error;
                            let tip = "";
                            $.each(error, function (i, v) {
                                tip += v[0] + "/"
                            })
                            tip = tip.substring(0, tip.length - 1);
                            layer.msg(tip);
                            return false;
                        } else {
                            layer.msg(res.msg);
                        }
                        if (res.status == 200) {
                            switch (action) {
                                case "add":
                                    table.reload('DataTable')
                                    break;
                                case "update":
                                    user.update({
                                        name: data.name,
                                        nick: data.nick,
                                        vip: data.vip,
                                        vip_endtime: data.vip_endtime,
                                        email: data.email,
                                        integral: data.integral,
                                        status: data.status
                                    })
                                    break;
                            }
                        }
                    }, 'post')
                })
                return false;
            })

            // 工具条点击事件
            table.on('tool(DataTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "edit":
                            user = obj,
                            action = "update",
                            useraddorupdate('update',obj)
                        break;
                    case "status":
                        layer.confirm("确认修改?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.user',['action'=>'status'],false)}}"
                            admin.req(url,{data: {id:data.id,status:data.status}},function (res) {
                                layer.closeAll('loading');
                                obj.update({
                                    status:data.status==1?0:1
                                })
                                layer.msg(res.msg);
                            },'post')
                        });
                        break;
                }
            });
        });
    </script>
@endsection
