@extends('admin.layout.layout')
@section('title','数据入库')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="MulitDataToMysql" class="layui-btn">多选入库</button>
                <button id="AddDataBtn" class="layui-btn">添加数据绑定</button>
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
                <label class="layui-form-label layui-form-required">采集分类:</label>
                <div class="layui-input-block">
                    <select name="type" lay-verType="tips" lay-verify="required" required>
                            <option value="video">视频</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">元类型:</label>
                <div class="layui-input-block">
                    <div id="metadata"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">现类型:</label>
                <div class="layui-input-block">
                    <div id="TypePid" class="ew-xmselect-tree"></div>
                </div>
            </div>
            <div class="layui-form-item text-right" style="margin-top: 50px">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit" lay-submit>提交</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表格操作列 -->
    <script type="text/html" id="tableBar">
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="exc"><i class="layui-icon">&#xe62c;</i>入库</a>
        <a class="layui-btn layui-btn-sm" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</a>
    </script>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'admin', 'form', 'table', 'treeTable', 'xmSelect'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var admin = layui.admin;
            var form = layui.form;
            var treeTable = layui.treeTable;
            var xmSelect = layui.xmSelect;
            var table = layui.table;
            var treeData, datatomysql, action, insXmSel, metadataXmsel,methodtype;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.datatomysql',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {type: 'checkbox'},
                    {field: 'metadata', align: "center", title: '元数据类型'},
                    {
                        field: 'nowtype', align: "center", title: '现数据类型', templet: function (dd) {
                           return dd.nowtype?dd.nowtype.name:"未知";
                        }
                    },
                    {field: 'lasttime', align: "center", title: '最近一次入库时间'},
                    {field: 'created_at', align: "center", title: '创建时间'},
                    {title: '操作', width: 400, toolbar: '#tableBar', align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 100,
            });

            // 添加数据绑定
            $('#AddDataBtn').click(function () {
                action = 'add';
                datatomysqladdorupdate('add');
            });

            $('#MulitDataToMysql').click(function () {
                layer.confirm("确认执行?", function (index) {
                    layer.close(index)
                    let checkids = table.checkStatus('DataTable').data
                    let ids = [];
                    $.each(checkids, function (index, item) {
                        ids.push(item.id)
                    })
                    if (ids.length == 0) {
                        layer.msg("请先勾选相应绑定");
                        return false;
                    }
                    let url = "{{route('qaecmsadmin.datatomysql',['action'=>'mulitepush'],false)}}&id="+JSON.stringify(ids);
                    admin.open({
                        type: 2,
                        title:  "多选入库进度",
                        scrollbar: false,
                        area: ['500px', '250px'],
                        offset: 'auto',
                        content: [url, 'no'],
                        end: function () {
                            table.reload('DataTable')
                        }
                    });
                });
            })

            //打开添加或者编辑视频窗口
            function datatomysqladdorupdate(type, dataobj = null) {
                admin.open({
                    type: 1,
                    scrollbar: false,
                    title: type == 'add' ? '添加绑定' : "编辑绑定",
                    area: ['500px', '350px'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow','visible');
                        methodtype = $('select[name=type]').val()
                        //获取分类
                        $.get('{{route('qaecmsadmin.type',['action'=>'list'])}}&type=' + methodtype, function (res) {
                            for (let i = 0; i < res.data.length; i++) {
                                res.data[i].title = res.data[i].name;
                                res.data[i].id = res.data[i].id;
                                res.data[i].spread = true;
                            }
                            treeData = treeTable.pidToChildren(res.data, 'id', 'pid');
                            insXmSel = xmSelect.render({
                                el: '#TypePid',
                                height: '250px',
                                data: treeData,
                                initValue: dataobj ? [dataobj.data.nowdata] : ([]),
                                model: {label: {type: 'text'}},
                                prop: {
                                    name: 'name',
                                    value: 'id'
                                },
                                radio: true,
                                filterable: true,
                                clickClose: true,
                                tree: {
                                    show: true,
                                    indent: 15,
                                    strict: false,
                                    expandedKeys: true
                                }
                            });
                        });
                        metadataXmsel = xmSelect.render({
                            el: '#metadata',
                            data: [],
                            clickClose: true,
                            filterable: true,
                            empty: '正在努力获取中..稍后',
                            model: {label: {type: 'text'}},
                            initValue: dataobj ? [dataobj.data.metadata] : ([]),
                            radio: true
                        })
                        getparsedata();
                        // if (type == "update") {
                        //     let data = dataobj.data;
                        //     form.val("DataForm", {
                        //         id: data.id,
                        //         type: data.type,
                        //         metadata: data.metadata,
                        //         nowdata: data.nowdata
                        //     })
                        // }
                        form.render();
                    }
                });
            }

            function getparsedata(){
                $.get('{{route('qaecmsadmin.datatomysql',['action'=>'parsedata'])}}&type=' + methodtype, function (res) {
                    metadataXmsel.update({data: res})
                })
            }

            //提交数据
            form.on("submit(DataFormSubmit)", function (obj) {
                layer.confirm("确认提交?", function (index) {
                    layer.load(2);
                    let data = obj.field;
                    data.metadata = metadataXmsel.getValue('valueStr')
                    data.nowdata = insXmSel.getValue('valueStr');
                    if(data.metadata==""||data.nowdata==""){
                        layer.closeAll('loading');
                        layer.msg("数据不能为空,请检查");
                        return false;
                    }
                    let url = "{{route('qaecmsadmin.datatomysql',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                        switch (action) {
                            case "add":
                                if (res.status == 200) {
                                    getparsedata()
                                    table.reload('DataTable')
                                }
                                break;
                            // case "update":
                            //     if (res.status == 200) {
                            //         datatomysql.update({
                            //             nowtype: {id: data.nowdata, name: insXmSel.getValue('nameStr')},
                            //             metadata: data.metadata,
                            //         })
                            //     }
                            //     break;
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
                    case "exc":
                        layer.confirm("确认执行?", function (index) {
                            let url = "{{route('qaecmsadmin.datatomysql',['_time'=>time()],false)}}&action=push&id=" + data.id;
                            layer.close(index)
                            admin.open({
                                type: 2,
                                title: data.metadata + "->" + data.nowtype.name + "进度",
                                scrollbar: false,
                                area: ['500px', '250px'],
                                offset: 'auto',
                                content: [url, 'no'],
                                end: function () {
                                    table.reload('DataTable')
                                }
                            });
                        })
                        break;
                    case "edit":
                        datatomysql = obj,
                            action = "update",
                            datatomysqladdorupdate('update', obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.datatomysql',['action'=>'delete'],false)}}"
                            admin.req(url, {data: {id: data.id}}, function (res) {
                                layer.closeAll('loading');
                                if (res.status == 200) {
                                    obj.del();
                                }
                                layer.msg(res.msg);
                            }, 'post')
                        });
                        break;
                }
            });
        });
    </script>
@endsection
