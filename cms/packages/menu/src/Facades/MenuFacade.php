<?php

namespace TVHung\Menu\Facades;

use TVHung\Menu\Menu;
use Illuminate\Support\Facades\Facade;

class MenuFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Menu::class;
    }
}
