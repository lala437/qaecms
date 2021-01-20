@extends('admin.layout.layout')
@section('title','订单列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <table id="DataTable" lay-filter="DataTable"></table>
            </div>
        </div>
    </div>
    <!-- 表格操作列 -->
    <script type="text/html" id="tableBar">
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
            var order,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.order',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'order_id', align: "center", title: '订单ID'},
                    {field: 'platform',  align: "center", title: '支付平台'},
                    {field: 'platform_id',  align: "center", title: '支付平台ID'},
                    {field: 'user',  align: "center", title: '用户',templet:function (dd) {
                            return dd.user==null?"用户已删除":dd.user.name;
                        }},
                    {field: 'shop',  align: "center", title: '商品',templet:function (dd) {
                            return dd.shop==null?"商品已删除":dd.shop.name;
                        }},
                    {field: 'money',  align: "center", title: '支付金额'},
                    {field: 'currency_type',  align: "center", title: '货币类型'},
                    {field: 'status',  align: "center", title: '状态',templet:function (dd) {
                         let status = ['未支付','支付成功','支付失败']
                        return status[dd.status];
                        }},
                    {field: 'success_at',  align: "center", title: '成功时间'},
                    {field: 'created_at',  align: "center", title: '创建时间'},
                    {title: '操作',  toolbar: '#tableBar', align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 工具条点击事件
            table.on('tool(DataTable)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                switch (layEvent) {
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.order',['action'=>'delete'],false)}}"
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
