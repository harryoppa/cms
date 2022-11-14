<?php

namespace TVHung\Api\Facades;

use TVHung\Api\Supports\ApiHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \TVHung\Api\Supports\ApiHelper
 */
class ApiHelperFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ApiHelper::class;
    }
}
