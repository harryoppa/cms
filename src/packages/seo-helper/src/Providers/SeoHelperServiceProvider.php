<?php

namespace TVHung\SeoHelper\Providers;

use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\SeoHelper\Contracts\SeoHelperContract;
use TVHung\SeoHelper\Contracts\SeoMetaContract;
use TVHung\SeoHelper\Contracts\SeoOpenGraphContract;
use TVHung\SeoHelper\Contracts\SeoTwitterContract;
use TVHung\SeoHelper\SeoHelper;
use TVHung\SeoHelper\SeoMeta;
use TVHung\SeoHelper\SeoOpenGraph;
use TVHung\SeoHelper\SeoTwitter;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);
    }

    public function boot()
    {
        $this->setNamespace('packages/seo-helper')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
