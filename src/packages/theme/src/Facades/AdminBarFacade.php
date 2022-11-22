<?php

namespace TVHung\Theme\Facades;

use TVHung\Theme\Supports\AdminBar;
use Illuminate\Support\Facades\Facade;

class AdminBarFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return AdminBar::class;
    }
}
