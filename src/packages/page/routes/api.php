<?php

Route::group([
    'prefix' => 'api/v1',
    'namespace' => 'TVHung\Page\Http\Controllers\API',
    'middleware' => ['api'],
], static function () {

    // find page by slug
    Route::get('pages/{slug}', [
        'as' => 'api.pages.find-page-by-slug',
        'uses' => 'PageController@findPageBySlug',
    ]);

});
