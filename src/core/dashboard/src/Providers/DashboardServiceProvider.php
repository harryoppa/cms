<?php

namespace TVHung\Dashboard\Providers;

use TVHung\Base\Supports\Helper;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Dashboard\Models\DashboardWidget;
use TVHung\Dashboard\Models\DashboardWidgetSetting;
use TVHung\Dashboard\Repositories\Caches\DashboardWidgetCacheDecorator;
use TVHung\Dashboard\Repositories\Caches\DashboardWidgetSettingCacheDecorator;
use TVHung\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use TVHung\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use TVHung\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use TVHung\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetCacheDecorator(
                new DashboardWidgetRepository(new DashboardWidget)
            );
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingCacheDecorator(
                new DashboardWidgetSettingRepository(new DashboardWidgetSetting)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('core/dashboard')
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-dashboard',
                    'priority'    => 0,
                    'parent_id'   => null,
                    'name'        => 'core/base::layouts.dashboard',
                    'icon'        => 'fa fa-home',
                    'url'         => route('dashboard.index'),
                    'permissions' => [],
                ]);
        });
    }
}
