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
                <button id="AddDataBtn" class="layui-btn">添加视频</button>
                <button id="Release" class="layui-btn">全部发布</button>
                <button id="Clean" class="layui-btn">全部清空</button>
                <button id="SyncImage" class="layui-btn">同步图片</button>
                <div class="dropdown-menu">
                    <button class="layui-btn icon-btn">&nbsp;清空指定来源内容<i class="layui-icon layui-icon-drop"></i></button>
                    <ul class="dropdown-menu-nav cleansource">
                        @foreach($shost as $host)
                            <li host="{{$host}}"><a href="javascript:void(0)">{{$job[$host]??"未知"}}</a></li>
                        @endforeach
                    </ul>
                </div>
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
                <label class="layui-form-label layui-form-required">类型:</label>
                <div class="layui-input-block">
                    <div id="TypePid" class="ew-xmselect-tree"></div>
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
                        <button type="button" class="layui-btn" id="uploadthumbnail">上传图片</button>
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
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">作者:</label>
                <div class="layui-input-block">
                    <input name="editor" placeholder="请输入作者名称" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">状态:</label>
                <div class="layui-input-block">
                    <input type="radio" checked name="status" value="1" title="发布">
                    <input type="radio" name="status" value="2" title="草稿">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">浏览等级:</label>
                <div class="layui-input-block">
                    <select name="vip" lay-verType="tips" lay-verify="required" required>
                        <option value="0">普通会员</option>
                        <option value="1">VIP会员</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">浏览积分:</label>
                <div class="layui-input-block">
                    <input name="integral" placeholder="请输入浏览所需积分,默认为0" class="layui-input" lay-verType="tips"
                           lay-verify="required" required/>
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
        layui.use(['layer', 'xmSelect', 'laydate', 'upload', 'admin', 'form', 'table', 'treeTable', 'tagsInput', 'dropdown'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var laydate = layui.laydate;
            var admin = layui.admin;
            var upload = layui.upload;
            var form = layui.form;
            var dropdown = layui.dropdown;
            var treeTable = layui.treeTable;
            var table = layui.table;
            var xmSelect = layui.xmSelect;
            var treeData;
            var video, action, insXmSel;


            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.video',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'title', width: 150, align: "center", title: '标题'},
                    {
                        field: 'thumbnail', width: 150, align: "center", title: '缩略图', templet: function (d) {
                            let src = d.thumbnail;
                            return '<img data-index="' + (d.LAY_TABLE_INDEX) + '" src="' + src + '" class="tb-img-circle" tb-img alt=""/>';
                        }
                    },
                    {
                        field: 'type', width: 150, align: "center", title: '分类', templet: function (dd) {
                            return dd.type.name;
                        }
                    },
                    {field: 'editor', width: 80, align: "center", title: '作者'},
                    {
                        field: 'status', width: 80, align: "center", title: '状态', templet: function (dd) {
                            return dd.status == 1 ? "发布" : "草稿"
                        }
                    },
                    {
                        field: 'vip', width: 100, align: "center", title: 'VIP', templet: function (dd) {
                            return dd.vip == 0 ? "普通会员" : "VIP会员";
                        }
                    },
                    {field: 'integral', width: 100, align: "center", title: '所需积分'},
                    {field: 'visitors', width: 100, align: "center", title: '浏览数'},
                    {field: 'last', width: 180, align: "center", title: '最后更新时间'},
                    {field: 'created_at', width: 180, align: "center", title: '创建时间'},
                    {field: 'updated_at', width: 180, align: "center", title: '更新时间'},
                    {title: '操作', width: 250, toolbar: '#tableBar', fixed: "right", align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 10,
            });

            // 添加视频
            $('#AddDataBtn').click(function () {
                action = 'add';
                videoaddorupdate('add');
            });

            $('#Release').click(function () {
                layer.confirm("确认发布?", function () {
                    layer.load(2)
                    let url = "{{route('qaecmsadmin.video',['action'=>'release'])}}";
                    $.get(url, function (res) {
                        table.reload("DataTable")
                        layer.msg(res.msg)
                        layer.closeAll("loading")
                    })
                })
            })

            $('#SyncImage').click(function () {
                layer.confirm("确认同步所有图片?", function () {
                    let url = "{{route('qaecmsadmin.annex',['action'=>'sync'])}}&type=video";
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
            })

            $('#Clean').click(function () {
                layer.confirm("确认清空?", function () {
                    layer.load(2)
                    let url = "{{route('qaecmsadmin.video',['action'=>'clean'])}}";
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
            //获取视频分类
            $.get('{{route('qaecmsadmin.type',['action'=>'list'])}}&type=video', function (res) {
                for (let i = 0; i < res.data.length; i++) {
                    res.data[i].title = res.data[i].name;
                    res.data[i].id = res.data[i].id;
                    res.data[i].spread = true;
                }
                treeData = treeTable.pidToChildren(res.data, 'id', 'pid');
            });

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
                        //渲染下拉
                        insXmSel = xmSelect.render({
                            el: '#TypePid',
                            height: '250px',
                            data: treeData,
                            initValue: dataobj ? [dataobj.data.type] : ([]),
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
                        //缩略图上传
                        var uploadInst = upload.render({
                            elem: '#uploadthumbnail'
                            , url: "{{route('qaecmsadmin.annex',['action'=>'add'],false)}}",//改成您自己的上传接口
                            field: "file[]",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }
                            , before: function (obj) {
                                //预读本地文件示例，不支持ie8
                                obj.preview(function (index, file, result) {
                                    $('#thumbnailimg').attr('src', result); //图片链接（base64）
                                });
                            }
                            , done: function (res) {
                                //如果上传失败
                                if (res.errno > 0) {
                                    return layer.msg('上传失败');
                                }
                                form.val('DataForm', {
                                    thumbnail: (res.data)[0]
                                })
                            }
                            , error: function () {
                                //演示失败状态，并实现重传
                                var thumbnail = $('#thumbnail');
                                thumbnail.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs img-reload">重试</a>');
                                thumbnail.find('.img-reload').on('click', function () {
                                    uploadInst.upload();
                                });
                            }
                        });

                        if (type == "update") {
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id: data.id,
                                title: data.title,
                                sid: data.sid,
                                stid: data.stid,
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
                                editor: data.editor,
                                content: parse_video_address_decode(data.content),
                                status: data.status,
                                vip: data.vip,
                                integral: data.integral,
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
                    data.type = insXmSel.getValue('valueStr');
                    if (data.type == "") {
                        layer.msg('类型不能为空')
                        return false;
                    }
                    let url = "{{route('qaecmsadmin.video',['_time'=>time()],false)}}&action=" + action;
                    admin.req(url, {data: data}, function (res) {
                        layer.closeAll('loading');
                        layer.msg(res.msg)
                        switch (action) {
                            case "add":
                                if (res.status == 200) {
                                    table.reload('DataTable')
                                }
                                break;
                            case "update":
                                let type = $('[name=type] option:selected').text();
                                if (res.status == 200) {
                                    video.update({
                                        title: data.title,
                                        type: {id: data.type, name: type},
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
                    case "edit":
                        video = obj,
                            action = "update",
                            videoaddorupdate('update', obj)
                        break;
                    case "delete":
                        layer.confirm("确认删除?", function (index) {
                            layer.load(2);
                            let url = "{{route('qaecmsadmin.video',['action'=>'delete'],false)}}"
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

            $('.cleansource>li').click(function () {
                let __this = this;
                layer.confirm("确认清除当前来源内容?", function (index) {
                    layer.load(2);
                    let host = $(__this).attr('host');
                    let url = "{{route('qaecmsadmin.video',['action'=>'clean'])}}&host=" + host;
                    $.get(url, function (res) {
                        table.reload("DataTable")
                        layer.msg(res.msg)
                        layer.closeAll("loading")
                        $(__this).remove();
                    })
                })
            })
        });
    </script>
@endsection
