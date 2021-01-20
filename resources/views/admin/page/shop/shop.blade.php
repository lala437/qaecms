@extends('admin.layout.layout')
@section('title','商品列表')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="AddDataBtn" class="layui-btn">添加商品</button>
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
                <label class="layui-form-label layui-form-required">商品分类:</label>
                <div class="layui-input-block">
                    <select name="type" lay-verType="tips" lay-verify="required" required>
                        <option value="vip">VIP</option>
                        <option value="integral">积分</option>
                        <option value="other">其他</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">商品名称:</label>
                <div class="layui-input-block">
                    <input name="name" placeholder="请输入商品名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">商品描述:</label>
                <div class="layui-input-block">
                    <textarea name="desc" placeholder="请输入商品描述" class="layui-textarea"  lay-verType="tips" lay-verify="required" required></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">商品图片:</label>
                <div class="layui-input-block">
                    <input name="image" placeholder="请输入商品图片地址" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">商品规格:</label>
                <div class="layui-input-block">
                    <input name="number" placeholder="请输入商品规格,分类VIP这里请填写天数" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">商品价格:</label>
                <div class="layui-input-block">
                    <input name="price" placeholder="请输入商品价格" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">限制等级:</label>
                <div class="layui-input-block">
                    <select name="vip" lay-verType="tips" lay-verify="required" required>
                        <option value="0">普通会员</option>
                        <option value="1">VIP会员</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">库存:</label>
                <div class="layui-input-block">
                    <input name="stock" placeholder="请输入商品库存" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" name="status" value="1" title="上架">
                    <input type="radio" name="status" value="0" title="下架" checked>
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
            var shop,action;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.shop',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'name', align: "center", title: '名称'},
                    {field: 'type', align: "center", title: '分类'},
                    {field: 'image', width: 150, align: "center", title: '图片',templet: function (d) {
                            let src = d.image;
                            return '<img data-index="' + (d.LAY_TABLE_INDEX) + '" src="' + src + '" class="tb-img-circle" tb-img alt=""/>';
                        }},
                    {field: 'number',  align: "center", title: '规格'},
                    {field: 'desc',  align: "center", title: '描述'},
                    {field: 'price',  align: "center", title: '价格'},
                    {field: 'vip',  align: "center", title: '限制等级',templet:function (dd) {
                             return dd.vip==0?"普通会员":"VIP会员";
                        }},
                    {field: 'stock',  align: "center", title: '库存'},
                    {field: 'status',  align: "center", title: '状态',templet:function (dd) {
                             return dd.status==1?"上架":"下架"
                        }},
                    {title: '操作',  toolbar: '#tableBar', width:200,align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 15,
            });

            // 添加商品
            $('#AddDataBtn').click(function () {
                action = 'add';
                shopaddorupdate('add');
            });

            //打开添加或者编辑视频窗口
            function shopaddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加商品':"编辑商品",
                    area: ['500px','700px'],
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
                                type:data.type,
                                image:data.image,
                                name:data.name,
                                number:data.number,
                                desc:data.desc,
                                price:data.price,
                                vip:data.vip,
                                stock:data.stock,
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
                    let url = "{{route('qaecmsadmin.shop',['_time'=>time()],false)}}&action=" + action;
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
                                    shop.update({
                                        name:data.name,
                                        type:data.type,
                                        image:data.image,
                                        desc:data.desc,
                                        number:data.number,
                                        price:data.price,
                                        vip:data.vip,
                                        stock:data.stock,
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
                        shop = obj,
                            action = "update",
                            shopaddorupdate('update',obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.shop',['action'=>'delete'],false)}}"
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
