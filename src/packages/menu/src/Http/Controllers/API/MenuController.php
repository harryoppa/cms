<?php

namespace TVHung\Menu\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TVHung\Base\Http\Controllers\BaseController;
use Menu;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Menu\Http\Resources\MenuResource;

class MenuController extends BaseController
{
    public function __construct(
        protected BaseHttpResponse $response,
    ){}

    /**
     * Get menu and menu nodes by location
     *
     * @urlParam location required The location of the menu. Example: main-menu
     *
     * @group Menu
     *
     * @param string $location
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function getMenuByLocation(string $location, Request $request)
    {
        $menus = Menu::getMenuLocation($location, $request->input());


        return $this->response->setData(
            $menus->map(function($menu) {
                return MenuResource::make($menu['menu']);
            })
        )->toApiResponse();
    }
}
