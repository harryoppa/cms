<?php

namespace TVHung\PaginateRoute\Providers;

use TVHung\Base\Supports\Helper;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;
use PaginateRoute;
use Illuminate\Pagination\Paginator;

class PaginateRouteServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app->singleton('paginateroute', 'TVHung\PaginateRoute\PaginateRoute');
    }


    public function boot()
    {
        PaginateRoute::registerMacros();

        Paginator::defaultView('packages/paginateroute::bootstrap-pagination');

        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('resources/lang/vendor/paginateroute'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/../config/paginateroute.php' => config_path('paginateroute.php'),
        ], 'config');

        $this->setNamespace('packages/paginateroute')
            ->loadAndPublishViews();
    }
}
