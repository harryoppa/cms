<?php

namespace TVHung\Theme\Http\Controllers;

use Assets;
use TVHung\Base\Supports\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MarketplaceController extends Controller
{
    public function index()
    {
        page_title()->setTitle(trans('packages/theme::marketplace.marketplace'));

        Assets::addScriptsDirectly('vendor/core/packages/theme/js/marketplace.js');

        return view('packages/theme::marketplace.index');
    }

    /**
     * Ajax get list
     */
    public function listThemes(Request $request, MarketplaceService $server)
    {
        $request->merge([
            'type' => 'theme',
            'per_page' => 12,
        ]);

        return $server->callApi('get', '/products', $request->all());
    }

    public function theme($id, MarketplaceService $server)
    {
        return $server->callApi('get', '/products/' . $id);
    }

    public function installNewTheme($id, MarketplaceService $server)
    {
        return $server->callApi('get', '/products/' . $id . '/download');
    }
}
