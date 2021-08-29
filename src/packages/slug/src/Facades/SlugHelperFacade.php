<?php

namespace TVHung\Slug\Facades;

use TVHung\Slug\SlugHelper;
use Illuminate\Support\Facades\Facade;

class SlugHelperFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SlugHelper::class;
    }
}
