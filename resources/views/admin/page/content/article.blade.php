@extends('admin.layout.layout')
@section('title','文章列表')
@section('css')
    <style>
        .layui-upload-img{width: 92px; height: 92px; margin: 0 10px 10px 0;}
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
            <div class="layui-card-header">文章管理</div>
            <div class="layui-card-body">
                <form class="layui-form toolbar" lay-filter="DataSearch">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label w-auto">文章名称:</label>
                            <div class="layui-input-inline mr0">
                                <input name="name" class="layui-input" type="text" placeholder="输入文章名称"/>
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
                <button id="AddDataBtn" class="layui-btn">添加文章</button>
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
            <div class="layui-col-md12">
                <div class="layui-form-item">
                    <label class="layui-form-label layui-form-required">标题:</label>
                    <div class="layui-input-block">
                        <input name="title" placeholder="请输入文章标题" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
                        <textarea name="introduction" class="layui-textarea" id="introduction" lay-verType="tips" lay-verify="required"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label layui-form-required">关键字:</label>
                    <div class="layui-input-block">
                        <input name="seokey" placeholder="请输入SEO关键字" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">内容:</label>
                <div class="layui-input-block">
                    <div id="content"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label layui-form-required">作者:</label>
                <div class="layui-input-block">
                    <input name="editor" placeholder="请输入作者名称" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
                    <input name="integral" placeholder="请输入浏览所需积分,默认为0" value="0" class="layui-input" lay-verType="tips" lay-verify="required" required/>
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
        layui.use(['layer', 'xmSelect','upload','admin','wangEditor','form','table','treeTable', 'tagsInput'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var admin = layui.admin;
            var upload = layui.upload;
            var wangEditor = layui.wangEditor;
            var form = layui.form;
            var treeTable = layui.treeTable;
            var table = layui.table;
            var xmSelect = layui.xmSelect;
            var treeData;
            var article,action,editor,insXmSel;


            // 渲染表格
            var insTb = table.render({
                elem: '#DataTable',
                url: "{{route('qaecmsadmin.article',['action'=>'list'])}}",
                page: true,
                cellMinWidth: 100,
                cols: [[
                    {field: 'title', width: 150, align: "center", title: '标题'},
                    {field: 'thumbnail', width: 150, align: "center", title: '缩略图',templet: function (d) {
                            let src = d.thumbnail;
                            return '<img data-index="' + (d.LAY_TABLE_INDEX) + '" src="' + src + '" class="tb-img-circle" tb-img alt=""/>';
                        }},
                    {field: 'type', width: 150, align: "center", title: '分类',templet:function (dd) {
                            return dd.type.name;
                        }},
                    {field: 'editor', width: 80, align: "center", title: '作者'},
                    {field: 'status', width: 80, align: "center", title: '状态',templet:function (dd) {
                            return dd.status==1?"发布":"草稿"
                        }},
                    {field: 'vip', width: 100, align: "center", title: 'VIP',templet:function (dd) {
                            return dd.vip==0?"普通会员":"VIP会员";
                        }},
                    {field: 'integral', width: 100, align: "center", title: '所需积分'},
                    {field: 'visitors', width: 100, align: "center", title: '浏览数'},
                    {field: 'created_at', width: 180, align: "center", title: '创建时间'},
                    {field: 'updated_at', width: 180, align: "center", title: '更新时间'},
                    {title: '操作', width: 250, toolbar: '#tableBar',fixed:"right", align: "center"}
                ]],
                size: 'lg',
                limits: [10, 15, 20, 25, 50, 100],
                limit: 10,
            });

            // 添加文章
            $('#AddDataBtn').click(function () {
                action = 'add';
                articleaddorupdate('add');
            });

            // 监听搜索操作
            form.on('submit(DataSearchSubmit)', function (data) {
                let  result = JSON.stringify(data.field);
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
            //获取文章分类
            $.get('{{route('qaecmsadmin.type',['action'=>'list'])}}&type=article', function (res) {
                for (let i = 0; i < res.data.length; i++) {
                    res.data[i].title = res.data[i].name;
                    res.data[i].id = res.data[i].id;
                    res.data[i].spread = true;
                }
                treeData = treeTable.pidToChildren(res.data, 'id', 'pid');
            });

           //打开添加或者编辑文章窗口
            function articleaddorupdate(type,dataobj=null) {
                admin.open({
                    type: 1,
                    title: type=='add'?'添加文章':"编辑文章",
                    area: ['100%','100%'],
                    offset: 'auto',
                    content: $('#DataFormView').html(),
                    scrollbar :false,
                    success: function (layero, dIndex) {
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
                            ,url: "{{route('qaecmsadmin.annex',['action'=>'add'],false)}}" ,//改成您自己的上传接口
                            field:"file[]",
                            headers:{
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }
                            ,before: function(obj){
                                //预读本地文件示例，不支持ie8
                                obj.preview(function(index, file, result){
                                    $('#thumbnailimg').attr('src', result); //图片链接（base64）
                                });
                            }
                            ,done: function(res){
                                //如果上传失败
                                if(res.errno > 0){
                                    return layer.msg('上传失败');
                                }
                                form.val('DataForm',{
                                    thumbnail:(res.data)[0]
                                })
                            }
                            ,error: function(){
                                //演示失败状态，并实现重传
                                var thumbnail = $('#thumbnail');
                                thumbnail.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs img-reload">重试</a>');
                                thumbnail.find('.img-reload').on('click', function(){
                                    uploadInst.upload();
                                });
                            }
                        });

                        //创建富文本编辑器
                        editor = new wangEditor('#content');
                        editor.customConfig.uploadImgServer = "{{route('qaecmsadmin.annex',['action'=>'add'],false)}}";
                        editor.customConfig.uploadFileName = 'file[]';
                        editor.customConfig.pasteFilterStyle = false;
                        editor.customConfig.uploadImgMaxLength = 5;
                        editor.customConfig.zIndex = 1
                        editor.customConfig.uploadImgHeaders = {
                            'Accept': 'text/x-json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        }
                        editor.customConfig.uploadImgHooks = {
                            // 上传超时
                            timeout: function (xhr, editor) {
                                layer.msg('上传超时！')
                            },
                        };
                        editor.customConfig.customAlert = function (info) {
                            layer.msg(info);
                        };
                        editor.create();
                        if(type=="update"){
                            let data = dataobj.data;
                            form.val("DataForm", {
                                id:data.id,
                                title: data.title,
                                type: data.type,
                                introduction:data.introduction,
                                seokey:data.seokey,
                                thumbnail:data.thumbnail,
                                editor:data.editor,
                                status:data.status,
                                vip:data.vip,
                                integral:data.integral
                            })
                            $('#thumbnailimg').attr('src',data.thumbnail)
                            editor.txt.html(data.content)
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
                    data.type = insXmSel.getValue('valueStr');
                    if(data.type==""){
                        layer.msg('类型不能为空')
                        return false;
                    }
                    data.content = editor.txt.html();
                    let url = "{{route('qaecmsadmin.article',['_time'=>time()],false)}}&action=" + action;
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
                                  let type = $('[name=type] option:selected').text();
                                  if (res.status == 200) {
                                      article.update({
                                          title: data.title,
                                          type: {id:data.type,name:type},
                                          introduction:data.introduction,
                                          seokey:data.seokey,
                                          thumbnail:data.thumbnail,
                                          editor:data.editor,
                                          status:data.status,
                                          vip:data.vip,
                                          integral:data.integral
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
                       article = obj,
                       action = "update",
                       articleaddorupdate('update',obj)
                   break;
                   case "delete":
                       layer.confirm("确认删除?", function (index) {
                           layer.load(2);
                           let url = "{{route('qaecmsadmin.article',['action'=>'delete'],false)}}"
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
                        alt: d.title,
                        src: d.thumbnail
                    }
                });
                layer.photos({photos: {data: imgList, start: $(this).data('index')}, shade: .1, closeBtn: true});
            });


        });
    </script>
@endsection
