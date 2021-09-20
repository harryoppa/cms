<?php

namespace TVHung\Table\Providers;

use TVHung\Base\Supports\Helper;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('core/table')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();
    }
}
