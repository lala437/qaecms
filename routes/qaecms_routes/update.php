<?php
Route::namespace('Admin')->name('qaecmsadmin.')->prefix(config('qaecms.admin_path'))->group(function () {
    Route::match(['get', 'post'], 'system_update', 'UpdateController@system_update')->name('update')->middleware(['adminauth']);
    Route::match(['get', 'post'], 'check_version', 'UpdateController@check_version')->name('checkversion')->middleware(['adminauth']);
});
