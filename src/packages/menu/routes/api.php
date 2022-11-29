<?php

Route::group([
    'prefix' => 'api/v1',
    'namespace' => 'TVHung\Menu\Http\Controllers\API',
    'middleware' => ['api'],
], static function () {

    // get menu by location
    Route::get('menus/{location}', [
        'as' => 'api.menus.get-menu-by-location',
        'uses' => 'MenuController@getMenuByLocation',
    ]);
});
