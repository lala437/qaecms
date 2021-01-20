<?php


Route::namespace('Index')->name('qaecmsindex.')->middleware(['webswitch','template', 'vip','qaecms-cache'])->group(function () {
    //用户首页
    Route::match(['post', 'get'], '/', 'IndexController@Index')->name('index');
    Route::get('list/{type}/{class}-{cat}-{page}-{limit}.html', 'IndexController@List')->name('list');
    Route::get('detail/{type}/{id}.html', 'IndexController@Detail')->name('detail');
    Route::get('play/{id}.html', 'IndexController@Play')->name('play');
    Route::get('search/{type}/{wd?}.html', 'IndexController@Search')->name('search');
    Route::get('searchcomplete/{type}','IndexController@SearchComplete')->name('searchcomplete');
    Route::match(['get','post'],'comments','IndexController@Comments')->name('comments');
});

Route::namespace('Index')->prefix(config('qaecms.user_path'))->middleware(['webswitch'])->name('qaecmsindex.')->group(function () {

    //登录
    Route::match(['post', 'get'], 'login', 'LoginController@login')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    //注册
    Route::match(['post', 'get'], 'userreg', 'RegisterController@register')->name('reg');

    //用户中心
    Route::match(['post', 'get'], 'user', 'IndexController@User')->name('user')->middleware(['auth']);

    //商品购买
    Route::match(['post', 'get'], 'pay', 'IndexController@Pay')->name('pay')->middleware(['auth','payswitch']);
});

//通知地址
Route::post('notify', 'Index\IndexController@Notify')->name('notify');
Route::get('return', 'Index\IndexController@Return')->name('return');






