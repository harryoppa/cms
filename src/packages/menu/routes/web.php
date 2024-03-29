<?php

Route::group(['namespace' => 'TVHung\Menu\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'menus', 'as' => 'menus.'], function () {
            Route::resource('', 'MenuController')->parameters(['' => 'menu']);

            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'MenuController@deletes',
                'permission' => 'menus.destroy',
            ]);

            Route::get('ajax/get-node', [
                'as' => 'get-node',
                'uses' => 'MenuController@getNode',
                'permission' => 'menus.index',
            ]);
        });
    });
});
