<?php

namespace TVHung\PaginateRoute\Facades;

use Illuminate\Support\Facades\Facade;

class PaginateRouteFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paginateroute';
    }
}
