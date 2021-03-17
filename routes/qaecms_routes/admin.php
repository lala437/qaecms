<?php


Route::namespace('Admin')->name('qaecmsadmin.')->prefix(config('qaecms.admin_path'))->group(function () {
    //登录
    Route::match(['get', 'post'], 'login', 'LoginController@login')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    //首页
    Route::get('index', 'AdminController@Index')->name('index')->middleware(['adminauth']);
    Route::get('menus', 'AdminController@Menus')->name('menus')->middleware(['adminauth']);
    Route::get('workspace', 'AdminController@WorkSpace')->name('workspace')->middleware(['adminauth']);
    Route::get('themeset', 'AdminController@ThemeSet')->name('themeset')->middleware(['adminauth']);

    //系统管理
    Route::match(['get', 'post'], 'webconfig', 'AdminController@WebConfig')->name('webconfig')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'seoconfig', 'AdminController@SeoConfig')->name('seoconfig')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'otherconfig', 'AdminController@OtherConfig')->name('otherconfig')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'nav', 'AdminController@Nav')->name('nav')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'carousel', 'AdminController@Carousel')->name('carousel')->middleware(['adminauth']);

    //内容管理
    Route::match(['get', 'post'], 'type', 'AdminController@Type')->name('type')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'article', 'AdminController@Article')->name('article')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'video', 'AdminController@Video')->name('video')->middleware(['adminauth']);

    //附件管理
    Route::match(['get', 'post'], 'annex', 'AdminController@Annex')->name('annex')->middleware(['adminauth']);

    //任务列表
    Route::match(['get', 'post'], 'job', 'AdminController@Job')->name('job')->middleware(['adminauth']);

    //数据入库
    Route::match(['get', 'post'], 'datatomysql', 'AdminController@DataToMysql')->name('datatomysql')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'notosql', 'AdminController@NoToSqlData')->name('notosql')->middleware(['adminauth']);

    //用户管理
    Route::match(['get', 'post'], 'user', 'AdminController@User')->name('user')->middleware(['adminauth']);

    //商品管理
    Route::match(['get', 'post'], 'shop', 'AdminController@Shop')->name('shop')->middleware(['adminauth']);

    //支付管理
    Route::match(['get', 'post'], 'order', 'AdminController@Order')->name('order')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'payconfig', 'AdminController@PayConfig')->name('payconfig')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'pay', 'AdminController@Pay')->name('pay')->middleware(['adminauth']);

    //搜索管理
    Route::match(['get', 'post'], 'searchconfig', 'AdminController@SearchConfig')->name('searchconfig')->middleware(['adminauth']);

    //友情链接
    Route::match(['get', 'post'], 'link', 'AdminController@Link')->name('link')->middleware(['adminauth']);

    //缓存设置
    Route::match(['get', 'post'], 'cache', 'AdminController@Cache')->name('cache')->middleware(['adminauth']);

    //播放器设置
    Route::match(['get', 'post'], 'player', 'AdminController@Player')->name('player')->middleware(['adminauth']);

    //广告设置
    Route::match(['get', 'post'], 'ad', 'AdminController@Ad')->name('ad')->middleware(['adminauth']);

    //任务管理
    Route::match(['get','post'],'task','AdminController@Task')->name('task')->middleware(['adminauth']);

    //单页设置
    Route::match(['get','post'],'singlepage','AdminController@SinglePage')->name('singlepage')->middleware(['adminauth']);

    //留言板管理
    Route::match(['get','post'],'commentconfig','AdminController@CommentConfig')->name('commentconfig')->middleware(['adminauth']);
    Route::match(['get','post'],'comment','AdminController@Comment')->name('comment')->middleware(['adminauth']);

    //获取视频信息
    Route::post('getvideoinfo','AdminController@GetVideoInfo')->name('getvideoinfo')->middleware(['adminauth']);
});
