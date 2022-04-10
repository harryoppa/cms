<?php

namespace TVHung\PageCache\Providers;

use Illuminate\Support\ServiceProvider;
use TVHung\PageCache\Console\ClearCache;
use TVHung\PageCache\Cache;
use TVHung\PageCache\Http\Middleware\CacheResponse;
use TVHung\Base\Supports\Helper;

class PageCacheServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->commands(ClearCache::class);

        $this->app->singleton(Cache::class, function () {
            $instance = new Cache($this->app->make('files'));

            return $instance->setContainer($this->app);
        });
    }

    public function boot()
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('page-cache', CacheResponse::class);

        $this->app->booted(function() {
            if (is_page_cache_enabled()) {
                add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'clearCache'], 127, 3);
                add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'clearCache'], 127, 3);
            }
        });
    }

    public function clearCache($screen, $request, $object)
    {
        try {
            clear_page_cache();
        } catch(\Exception $exception) {

        }
    }
}
