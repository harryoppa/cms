<?php

namespace TVHung\Sitemap\Providers;

use TVHung\Base\Events\CreatedContentEvent;
use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Base\Events\UpdatedContentEvent;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Sitemap\Sitemap;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('packages/sitemap')
            ->loadAndPublishConfigurations(['config'])
            ->loadAndPublishViews()
            ->publishAssets();

        Event::listen(CreatedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });

        Event::listen(UpdatedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });

        Event::listen(DeletedContentEvent::class, function () {
            cache()->forget('cache_site_map_key');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sitemap', function ($app) {
            $config = config('packages.sitemap.config');

            return new Sitemap(
                $config,
                $app['Illuminate\Cache\Repository'],
                $app['config'],
                $app['files'],
                $app['Illuminate\Contracts\Routing\ResponseFactory'],
                $app['view']
            );
        });

        $this->app->alias('sitemap', Sitemap::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sitemap', Sitemap::class];
    }
}
