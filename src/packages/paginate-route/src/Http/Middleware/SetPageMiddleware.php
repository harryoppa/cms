<?php

namespace TVHung\PaginateRoute\Http\Middleware;

use Closure;
use Illuminate\Pagination\Paginator;
use Route;

class SetPageMiddleware
{
    /**
     * Set the current page based on the page route parameter before the route's action is executed.
     *
     * @return \Illuminate\Http\Request
     */
    public function handle($request, Closure $next)
    {
        Paginator::currentPageResolver(function () {
            return app('paginateroute')->currentPage();
        });

        return $next($request);
    }
}
