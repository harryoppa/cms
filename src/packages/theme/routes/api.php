<?php

Route::group([
    'prefix' => 'api/v1',
    'namespace' => 'TVHung\Theme\Http\Controllers\API',
    'middleware' => ['api'],
], static function () {

    Route::get('theme/slug/{slug}', [
        'as' => 'api.theme.slug',
        'uses' => 'ThemeController@getSlugView',
    ]);
});
