@extends('admin.layout.layout')
@section('title','分类管理')
@section('css')
    <style>
        /* 左树 */
        #organizationTreeBar {
            padding: 10px 15px;
            border: 1px solid #e6e6e6;
            background-color: #f2f2f2;
        }

        #organizationTree {
            border: 1px solid #e6e6e6;
            border-top: none;
            padding: 10px 5px;
            overflow: auto;
            height: -webkit-calc(100vh - 125px);
            height: -moz-calc(100vh - 125px);
            height: calc(100vh - 125px);
        }

        .layui-tree-entry .layui-tree-txt {
            padding: 0 5px;
            border: 1px transparent solid;
            text-decoration: none !important;
        }

        .layui-tree-entry.ew-tree-click .layui-tree-txt {
            background-color: #fff3e0;
            border: 1px #FFE6B0 solid;
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid" style="padding-bottom: 0;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <!-- 树工具栏 -->
                        <div class="layui-form toolbar" id="DataTreeBar">
                            <button id="TypeAddBtn" class="layui-btn layui-btn-sm icon-btn">
                                <i class="layui-icon">&#xe654;</i>添加
                            </button>&nbsp;
                            <button id="TypeEditBtn" class="layui-btn layui-btn-sm layui-btn-warm icon-btn">
                                <i class="layui-icon">&#xe642;</i>修改
                            </button>&nbsp;
                            <button id="TypeDelBtn"
                                    class="layui-btn layui-btn-sm layui-btn-danger icon-btn">
                                <i class="layui-icon">&#xe640;</i>删除
                            </button>
                        </div>
                    </div>
                    <div class="layui-card-body" style="padding: 10px;">
                        <form action="" class="layui-form" lay-filter="TypeType">
                            <div class="layui-form-item">
                                <div class="layui-input-inline">
                                    <select name="type" lay-verType="tips" lay-filter="selecttype" lay-verify="required" required>
                                        @foreach(config('system.type') as $key=>$v)
                                        <option value="{{$key}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                        <!-- 分类树 -->
                        <div id="TypeTree"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 表单弹窗1 -->
    <script type="text/html" id="TypeData">
        <form id="TypeDataForm" lay-filter="TypeDataForm" class="layui-form model-form"
              style="padding-right: 20px;">
            <input name="id" type="hidden"/>
            <div class="layui-row">
                <div class="layui-col-md12">
                    <div class="layui-form-item">
                        <label class="layui-form-label">父级分类:</label>
                        <div class="layui-input-block">
                            <div id="TypePid" class="ew-xmselect-tree"></div>
                            <div class="layui-word-aux">留空则代表是顶级分类</div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">分类名称:</label>
                        <div class="layui-input-block">
                            <input name="name" placeholder="请输入分类名称" class="layui-input"
                                   lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-form-required">排序:</label>
                        <div class="layui-input-block">
                            <input name="sort" placeholder="请输入排序数字越大越靠前" class="layui-input" type="number"
                                   lay-verType="tips" lay-verify="required" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" lay-filter="TypeDataSubmit" lay-submit>保存</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'form', 'table', 'util', 'admin', 'tree', 'dropdown', 'xmSelect', 'treeTable'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var admin = layui.admin;
            var tree = layui.tree;
            var xmSelect = layui.xmSelect;
            var type;
            var selObj, treeData,action;  // 左树选中数据
            /* 渲染树形 */
            function renderTree() {
                type = (form.val('TypeType')).type;
                $.get('{{route('qaecmsadmin.type',['action'=>'list'])}}&type='+type, function (res) {
                    for (let i = 0; i < res.data.length; i++) {
                        res.data[i].title = res.data[i].name+"--"+res.data[i].id;
                        res.data[i].id = res.data[i].id;
                        res.data[i].spread = true;
                    }
                    treeData = layui.treeTable.pidToChildren(res.data, 'id', 'pid');
                    tree.render({
                        elem: '#TypeTree',
                        onlyIconControl: true,
                        data: treeData,
                        click: function (obj) {
                            selObj = obj;
                            $('#TypeTree').find('.ew-tree-click').removeClass('ew-tree-click');
                            $(obj.elem).children('.layui-tree-entry').addClass('ew-tree-click');
                        }
                    });
                    $('#TypeTree').find('.layui-tree-entry:first>.layui-tree-main>.layui-tree-txt').trigger('click');
                });
            }
            renderTree();

            form.on("select(selecttype)",function () {
                 renderTree();
            })

            /* 添加 */
            $('#TypeAddBtn').click(function () {
                action = "add";
                showEditModel(null, selObj ? selObj.data.pid : null);
            });

            /* 修改 */
            $('#TypeEditBtn').click(function () {
                action = "update";
                if (!selObj) return layer.msg('未选择分类', {icon: 2});
                showEditModel(selObj.data);
            });

            /* 删除 */
            $('#TypeDelBtn').click(function () {
                if (!selObj) return layer.msg('未选择分类', {icon: 2});
                doDel(selObj);
            });

            /* 显示表单弹窗 */
            function showEditModel(mData, pid) {
                admin.open({
                    type: 1,
                    area: '600px',
                    scrollbar: false,
                    title: (mData ? '修改' : '添加') + '分类',
                    content: $('#TypeData').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow','visible');
                        // 回显表单数据
                        form.val('TypeDataForm', mData);
                        // 表单提交事件
                        form.on('submit(TypeDataSubmit)', function (data) {
                            data.field.pid = (insXmSel.getValue('valueStr')==""?0:insXmSel.getValue('valueStr'));
                            data.field.type = type;
                            let loadIndex = layer.load(2);
                            let url = "{{route('qaecmsadmin.type',['_time'=>time()])}}&action=" + action;
                            admin.req(url,{data:data.field},function (res) {
                                layer.close(loadIndex);
                                layer.msg(res.msg)
                                switch (action) {
                                    case "add":
                                        if (res.status == 200) {
                                            layer.msg(res.msg, {icon: 1});
                                            renderTree();
                                        }
                                        break;
                                    case "update":
                                        if (res.status == 200) {
                                            layer.msg(res.msg, {icon: 1});
                                            renderTree();
                                        }
                                        break;
                                }
                            },'post')
                            return false;
                        });
                        // 渲染下拉树
                        var insXmSel = xmSelect.render({
                            el: '#TypePid',
                            height: '250px',
                            data: treeData,
                            initValue: mData ? [mData.pid] : (pid ? [pid] : []),
                            toolbar: {
                                show: true,
                                list: ['CLEAR']
                            },
                            model: {label: {type: 'text'}},
                            prop: {
                                name: 'name',
                                value: 'id'
                            },
                            radio: true,
                            clickClose: true,
                            tree: {
                                show: true,
                                indent: 15,
                                strict: false,
                                expandedKeys: true
                            }
                        });
                    }
                });
            }

            /* 删除 */
            function doDel(obj) {
                layer.confirm('将删除分类下所有内容,确定要删除此分类吗?', {
                    skin: 'layui-layer-admin',
                    shade: .1
                }, function (i) {
                    layer.close(i);
                    var loadIndex = layer.load(2);
                    let url = "{{route('qaecmsadmin.type',['action'=>'delete'],false)}}"
                    admin.req(url,{data: {id:obj.data.id}},function (res) {
                        layer.close(loadIndex);
                        if(res.status==200){
                            renderTree();
                        }
                        layer.msg(res.msg);
                    },'post')
                });
            }

        });
    </script>
@endsection

