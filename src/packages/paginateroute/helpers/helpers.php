<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

if (!function_exists('build_paginate_links'))
{
    function build_paginate_links()
    {
        return \PaginateRoute::resolver();
    }
}
