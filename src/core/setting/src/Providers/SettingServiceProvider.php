<?php

namespace TVHung\Setting\Providers;

use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Setting\Facades\SettingFacade;
use TVHung\Setting\Models\Setting as SettingModel;
use TVHung\Setting\Repositories\Caches\SettingCacheDecorator;
use TVHung\Setting\Repositories\Eloquent\SettingRepository;
use TVHung\Setting\Repositories\Interfaces\SettingInterface;
use TVHung\Setting\Supports\SettingsManager;
use TVHung\Setting\Supports\SettingStore;
use EmailHandler;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * This provider is deferred and should be lazy loaded.
     *
     * @var boolean
     */
    protected $defer = true;

    public function register()
    {
        $this->setNamespace('core/setting')
            ->loadAndPublishConfigurations(['general']);

        $this->app->singleton(SettingsManager::class, function (Application $app) {
            return new SettingsManager($app);
        });

        $this->app->singleton(SettingStore::class, function (Application $app) {
            return $app->make(SettingsManager::class)->driver();
        });

        AliasLoader::getInstance()->alias('Setting', SettingFacade::class);

        $this->app->bind(SettingInterface::class, function () {
            return new SettingCacheDecorator(
                new SettingRepository(new SettingModel())
            );
        });

        $this->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-settings',
                    'priority' => 998,
                    'parent_id' => null,
                    'name' => 'core/setting::setting.title',
                    'icon' => 'fa fa-cogs',
                    'url' => route('settings.options'),
                    'permissions' => ['settings.options'],
                ])
                ->registerItem([
                    'id' => 'cms-core-settings-general',
                    'priority' => 1,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'core/base::layouts.setting_general',
                    'icon' => null,
                    'url' => route('settings.options'),
                    'permissions' => ['settings.options'],
                ])
                ->registerItem([
                    'id' => 'cms-core-settings-email',
                    'priority' => 2,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'core/base::layouts.setting_email',
                    'icon' => null,
                    'url' => route('settings.email'),
                    'permissions' => ['settings.email'],
                ])
                ->registerItem([
                    'id' => 'cms-core-settings-media',
                    'priority' => 3,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'core/setting::setting.media.title',
                    'icon' => null,
                    'url' => route('settings.media'),
                    'permissions' => ['settings.media'],
                ]);

            EmailHandler::addTemplateSettings('base', config('core.setting.email', []), 'core');
        });
    }

    /**
     * Which IoC bindings the provider provides.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SettingsManager::class,
            SettingStore::class,
        ];
    }
}
