@extends('admin.layout.layout')
@section('title','视频列表')
@section('css')
    <style>
        .layui-upload-img {
            width: 92px;
            height: 92px;
            margin: 0 10px 10px 0;
        }

        #tbImgTable + .layui-table-view .layui-table-body tbody > tr > td {
            padding: 0;
        }

        #tbImgTable + .layui-table-view .layui-table-body tbody > tr > td > .layui-table-cell {
            height: 48px;
            line-height: 48px;
        }

        .tb-img-circle {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            cursor: zoom-in;
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header">视频管理</div>
            <div class="layui-card-body">
                <form class="layui-form toolbar" lay-filter="DataSearch">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label w-auto">视频名称:</label>
                            <div class="layui-input-inline mr0">
                                <input name="name" class="layui-input" type="text" placeholder="请输入视频名称"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn icon-btn" lay-filter="DataSearchSubmit" lay-submit>
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <button id="Clean" class="layui-btn">一键清空</button>
                <span style="color: red">温馨提示:以下资源分类未绑定,请到 资源管理 -> 数据入库 -> 数据绑定后 执行入库</span>
{{--                <button id="CreateAndBind" class="layui-btn">创建分类并绑定</button>--}}
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
                    <input name="title" placeholder="请输入文章标题" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">资源分类:</label>
                <div class="layui-input-block">
                    <select name="type" lay-verType="tips" lay-verify="required" required>
                        <option value="video">视频</option>
                        <option value="article">文章</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">简介:</label>
                <div class="layui-input-block">
                    <textarea name="introduction" class="layui-textarea" id="introduction" lay-verType="tips"
                              lay-verify="required"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">关键字:</label>
                <div class="layui-input-block">
                    <input name="seokey" placeholder="请输入SEO关键字" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">缩略图:</label>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <div class="layui-upload-list">
                            <img class="layui-upload-img" id="thumbnailimg">
                            <p id="thumbnail"></p>
                        </div>
                        <input type="hidden" name="thumbnail">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">资源ID:</label>
                <div class="layui-input-block">
                    <input name="sid" placeholder="请输入资源ID" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">资源分类ID:</label>
                <div class="layui-input-block">
                    <input name="stid" placeholder="请输入资源分类ID" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">资源分类名称:</label>
                <div class="layui-input-block">
                    <input name="stype" placeholder="请输入资源分类名称" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">语言:</label>
                <div class="layui-input-block">
                    <input name="lang" placeholder="请输入语言" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">区域:</label>
                <div class="layui-input-block">
                    <input name="area" placeholder="请输入区域" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">年份:</label>
                <div class="layui-input-block">
                    <input name="year" id="year" placeholder="请输入年份" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">清晰度:</label>
                <div class="layui-input-block">
                    <input name="note" placeholder="请输入清晰度" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">评分:</label>
                <div class="layui-input-block">
                    <input name="score" placeholder="请输入评分" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">演员:</label>
                <div class="layui-input-block">
                    <input name="actor" placeholder="请输入演员" class="layui-input" lay-verType="tips" lay-verify="required"
                           required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">导演:</label>
                <div class="layui-input-block">
                    <input name="director" placeholder="请输入导演" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">来源host:</label>
                <div class="layui-input-block">
                    <input name="shost" placeholder="请输入来源host" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">最后更新时间:</label>
                <div class="layui-input-block">
                    <input name="last" id="last" placeholder="最后更新时间" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">播放地址:</label>
                <div class="layui-input-block">
                    <textarea name="content" class="layui-textarea" id="introduction" lay-verType="tips"
                              lay-verify="required"></textarea>
                </div>
            </div>
            <div class="layui-form-item text-right">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit" lay-submit>提交</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表单弹窗1 -->
    <script type="text/html" id="DataFormView2">
        <form id="DataForm2" lay-filter="DataForm2" class="layui-form model-form layui-row">
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">任务方法:</label>
                <div class="layui-input-block">
                    <select name="type" lay-verType="tips" lay-verify="required" required>
                        @foreach(config('method') as $key=>$name)
                            <option value="{{$key}}">{{$name}}</option>
                        @endforeach
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
                <label class="layui-form-label layui-form-required">父级分类:</label>
                <div class="layui-input-block">
                    <div id="TypePid" class="ew-xmselect-tree"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">现类型:</label>
                <div class="layui-input-block">
                    <input name="name" placeholder="请输入现类型名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">排序:</label>
                <div class="layui-input-block">
                    <input name="sort" placeholder="请输入排序,值越大越靠前" class="layui-input" lay-verType="tips" lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item text-right" style="margin-top: 50px">
                <button class="layui-btn" id="BtnAction" lay-filter="DataFormSubmit2" lay-submit>创建并绑定</button>
                <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
            </div>
        </form>
    </script>
    <!-- 表格操作列 -->
    <script type="text/html" id="tableBar">
        <a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="push"><i class="layui-icon">&#xe674;</i>入库</a>
        <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="edit"><i class="layui-icon">&#xe642;</i>编辑</a>
        <a class="layui-btn layui-btn-sm" lay-event="delete"><i class="layui-icon">&#xe640;</i>删除</a>
    </script>
@endsection
@section('js')
    <script>
        layui.use(['layer', 'laydate', 'admin', 'form', 'table', 'tagsInput','xmSelect','treeTable'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var laydate = layui.laydate;
            var admin = layui.admin;
            var form = layui.form;
            var table = layui.table;
            var xmSelect = layui.xmSelect;
            var treeTable = layui.treeTable;
            var video, action,treeData, insXmSel, metadataXmsel;

            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.notosql',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'title', align: "center", title: '标题'},
                    {
                        field: 'source', align: "center", title: '来源', templet: function (dd) {
                            return dd.source.name
                        }
                    },
                    {
                        field: 'thumbnail', align: "center", title: '缩略图', templet: function (d) {
                            let src = d.thumbnail;
                            return '<img data-index="' + (d.LAY_TABLE_INDEX) + '" src="' + src + '" class="tb-img-circle" tb-img alt=""/>';
                        }
                    },
                    {
                        field: 'type', align: "center", title: '资源分类', templet: function (dd) {
                            let source = {video: "视频", article: "文章"}
                            return source[dd.type]
                        }
                    },
                    {
                        field: 'stype', align: "center", title: '内容分类'
                    },
                    {field: 'last', align: "center", title: '最后更新时间'},
                    {field: 'created_at', align: "center", title: '创建时间'},
                    {title: '操作', width:300,toolbar: '#tableBar', fixed: "right", align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 25,
            });

            $('#Clean').click(function () {
                layer.confirm("确认清空?", function () {
                    layer.load(2)
                    let url = "{{route('qaecmsadmin.notosql',['action'=>'clean'])}}";
                    $.get(url, function (res) {
                        table.reload("DataTable")
                        layer.msg(res.msg)
                        layer.closeAll("loading")
                    })
                })
            })


            // 监听搜索操作
            form.on('submit(DataSearchSubmit)', function (data) {
                let result = JSON.stringify(data.field);
                //执行搜索重载
                table.reload('DataTable', {
                    page: {
                        curr: 1
                    }
                    , where: {
                        params: result
                    }
                }, 'data');

                return false;
            });

            $('#CreateAndBind').click(function () {
                  datatomysqladdorupdate()
            })

            //打开添加或者编辑视频窗口
            function datatomysqladdorupdate() {
                admin.open({
                    type: 1,
                    title: "创建分类并绑定",
                    area: ['500px', '500px'],
                    offset: 'auto',
                    content: $('#DataFormView2').html(),
                    success: function (layero, dIndex) {
                        $(layero).children('.layui-layer-content').css('overflow','visible');
                        let methodtype = $('select[name=type]').val()
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
                            radio: true
                        })
                        $.get('{{route('qaecmsadmin.datatomysql',['action'=>'parsedata'])}}&flag=meta&type=' + methodtype, function (res) {
                            metadataXmsel.update({data: res})
                        })
                        form.render();
                    }
                });

            }

            form.on("submit(DataFormSubmit2)", function (obj) {
                layer.confirm("确认创建并绑定?", function (index) {
                    layer.load(2);
                    let data = obj.field;
                    data.metadata = metadataXmsel.getValue('valueStr')
                    data.typepid = insXmSel.getValue('valueStr');
                    let url = "{{route('qaecmsadmin.notosql',['action'=>"createandbind"],false)}}";
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                    }, 'post')
                })
                return false;
            })

            //打开添加或者编辑视频窗口
            function videoaddorupdate(type, dataobj = null) {
                admin.open({
                    type: 1,
                    title: type == 'add' ? '添加视频' : "编辑视频",
                    area: ['100%', '100%'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar: false,
                    success: function (layero, dIndex) {
                        // $(layero).children('.layui-layer-content').css('overflow','visible');
                        //日期时间渲染
                        laydate.render({
                            elem: '#year',
                            type: 'year',
                        });

                        laydate.render(
                            {
                                elem: '#last',
                                type: "datetime"
                            }
                        )

                        if (type == "update") {
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id: data.id,
                                title: data.title,
                                sid: data.sid,
                                stid: data.stid,
                                type: data.type,
                                stype: data.stype,
                                lang: data.lang,
                                area: data.area,
                                year: data.year,
                                note: data.note,
                                score: data.score,
                                actor: data.actor,
                                director: data.director,
                                shost: data.shost,
                                introduction: data.introduction,
                                seokey: data.seokey,
                                thumbnail: data.thumbnail,
                                content: parse_video_address_decode(data.content),
                                last: data.last,
                            })
                            $('#thumbnailimg').attr('src', data.thumbnail)
                        }
                        form.render();
                        // 标签输入框
                        $('#eDialogStuEditForm input[name="label"]').tagsInput({
                            skin: 'tagsinput-default',
                            autocomplete_url: '../../json/tagsInput.json'
                        });
                    }
                });
            }

            //提交数据
            form.on("submit(DataFormSubmit)", function (obj) {
                layer.confirm("确认提交?", function (index) {
                    layer.load(2);
                    let data = obj.field;
                    data.content = parse_video_address_encode(data.content)
                    let url = "{{route('qaecmsadmin.notosql',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                        switch (action) {
                            case "update":
                                if (res.status == 200) {
                                    video.update({
                                        title: data.title,
                                        type: data.type,
                                        introduction: data.introduction,
                                        seokey: data.seokey,
                                        thumbnail: data.thumbnail,
                                        editor: data.editor,
                                        status: data.status,
                                        vip: data.vip,
                                        integral: data.integral,
                                        last: data.last,
                                    })
                                }
                                break;
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
                    case "push":
                        layer.confirm("确认执行?", function (index) {
                            layer.load(2)
                            let url = "{{route('qaecmsadmin.notosql',['action'=>'push'],false)}}";
                            layer.close(index)
                            admin.req(url, {data: {id: data.id}}, function (res) {
                                layer.closeAll('loading');
                                if (res.status == 200) {
                                    layer.msg(res.msg)
                                    obj.del();
                                }
                                layer.msg(res.msg);
                            }, 'post')
                        })
                        break;
                    case "edit":
                        video = obj,
                            action = "update",
                            videoaddorupdate('update', obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.notosql',['action'=>'delete'],false)}}"
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

            /* 点击图片放大 */
            $(document).off('click.tbImg').on('click.tbImg', '[tb-img]', function () {
                let imgList = table.cache['DataTable'].map(function (d) {
                    return {
                        alt: d.title,
                        src: d.thumbnail
                    }
                });
                layer.photos({photos: {data: imgList, start: $(this).data('index')}, shade: .1, closeBtn: true});
            });
        });
    </script>
@endsection
