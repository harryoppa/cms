<?php

namespace TVHung\Theme\Providers;

use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Theme\Commands\ThemeActivateCommand;
use TVHung\Theme\Commands\ThemeAssetsPublishCommand;
use TVHung\Theme\Commands\ThemeAssetsRemoveCommand;
use TVHung\Theme\Commands\ThemeOptionCheckMissingCommand;
use TVHung\Theme\Commands\ThemeRemoveCommand;
use TVHung\Theme\Commands\ThemeRenameCommand;
use TVHung\Theme\Contracts\Theme as ThemeContract;
use TVHung\Theme\Theme;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->setNamespace('packages/theme')
            ->loadHelpers();

        $this->app->bind(ThemeContract::class, Theme::class);

        $this->commands([
            ThemeActivateCommand::class,
            ThemeRemoveCommand::class,
            ThemeAssetsPublishCommand::class,
            ThemeOptionCheckMissingCommand::class,
            ThemeAssetsRemoveCommand::class,
            ThemeRenameCommand::class,
        ]);
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-appearance',
                    'priority' => 996,
                    'parent_id' => null,
                    'name' => 'packages/theme::theme.appearance',
                    'icon' => 'fa fa-paint-brush',
                    'url' => '#',
                    'permissions' => [],
                ]);

            if ($this->app['config']->get('packages.theme.general.display_theme_manager_in_admin_panel', true)) {
                dashboard_menu()
                    ->registerItem([
                        'id' => 'cms-core-theme',
                        'priority' => 1,
                        'parent_id' => 'cms-core-appearance',
                        'name' => 'packages/theme::theme.name',
                        'icon' => null,
                        'url' => route('theme.index'),
                        'permissions' => ['theme.index'],
                    ]);
            }

            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-theme-option',
                    'priority' => 4,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/theme::theme.theme_options',
                    'icon' => null,
                    'url' => route('theme.options'),
                    'permissions' => ['theme.options'],
                ])
                ->registerItem([
                    'id' => 'cms-core-appearance-custom-css',
                    'priority' => 5,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/theme::theme.custom_css',
                    'icon' => null,
                    'url' => route('theme.custom-css'),
                    'permissions' => ['theme.custom-css'],
                ]);

            if (config('packages.theme.general.enable_custom_js')) {
                dashboard_menu()
                    ->registerItem([
                        'id' => 'cms-core-appearance-custom-js',
                        'priority' => 6,
                        'parent_id' => 'cms-core-appearance',
                        'name' => 'packages/theme::theme.custom_js',
                        'icon' => null,
                        'url' => route('theme.custom-js'),
                        'permissions' => ['theme.custom-js'],
                    ]);
            }

            if (config('packages.theme.general.enable_custom_html')) {
                dashboard_menu()
                    ->registerItem([
                        'id' => 'cms-core-appearance-custom-html',
                        'priority' => 6,
                        'parent_id' => 'cms-core-appearance',
                        'name' => 'packages/theme::theme.custom_html',
                        'icon' => null,
                        'url' => route('theme.custom-html'),
                        'permissions' => ['theme.custom-html'],
                    ]);
            }

            admin_bar()
                ->registerLink(trans('packages/theme::theme.name'), route('theme.index'), 'appearance', 'theme.index')
                ->registerLink(trans('packages/theme::theme.theme_options'), route('theme.options'), 'appearance', 'theme.options');
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(ThemeManagementServiceProvider::class);
    }
}
