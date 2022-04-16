<?php

namespace TVHung\Theme\Providers;

use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Theme\Commands\ThemeActivateCommand;
use TVHung\Theme\Commands\ThemeAssetsPublishCommand;
use TVHung\Theme\Commands\ThemeAssetsRemoveCommand;
use TVHung\Theme\Commands\ThemeRemoveCommand;
use TVHung\Theme\Commands\ThemeRenameCommand;
use TVHung\Theme\Contracts\Theme as ThemeContract;
use TVHung\Theme\Http\Middleware\AdminBarMiddleware;
use TVHung\Theme\Supports\ThemeSupport;
use TVHung\Theme\Theme;
use File;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Theme as ThemeFacade;

class ThemeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', AdminBarMiddleware::class);

        $this->app->bind(ThemeContract::class, Theme::class);

        $this->commands([
            ThemeActivateCommand::class,
            ThemeRemoveCommand::class,
            ThemeAssetsPublishCommand::class,
            ThemeAssetsRemoveCommand::class,
            ThemeRenameCommand::class,
        ]);
    }

    public function boot()
    {
        $this->setNamespace('packages/theme')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-appearance',
                    'priority'    => 996,
                    'parent_id'   => null,
                    'name'        => 'packages/theme::theme.appearance',
                    'icon'        => 'fa fa-paint-brush',
                    'url'         => '#',
                    'permissions' => [],
                ]);

                if ($this->app['config']->get('packages.theme.general.display_theme_manager_in_admin_panel', true)) {
                    dashboard_menu()
                        ->registerItem([
                            'id'          => 'cms-core-theme',
                            'priority'    => 1,
                            'parent_id'   => 'cms-core-appearance',
                            'name'        => 'packages/theme::theme.name',
                            'icon'        => null,
                            'url'         => route('theme.index'),
                            'permissions' => ['theme.index'],
                        ]);
                }
    
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-theme',
                        'priority'    => 1,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.name',
                        'icon'        => null,
                        'url'         => route('theme.index'),
                        'permissions' => ['theme.index'],
                    ])
                    ->registerItem([
                        'id'          => 'cms-core-theme-option',
                        'priority'    => 4,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.theme_options',
                        'icon'        => null,
                        'url'         => route('theme.options'),
                        'permissions' => ['theme.options'],
                    ])
                    ->registerItem([
                        'id'          => 'cms-core-appearance-custom-css',
                        'priority'    => 5,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.custom_css',
                        'icon'        => null,
                        'url'         => route('theme.custom-css'),
                        'permissions' => ['theme.custom-css'],
                    ]);

            if (config('packages.theme.general.enable_custom_js')) {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-core-appearance-custom-js',
                        'priority'    => 6,
                        'parent_id'   => 'cms-core-appearance',
                        'name'        => 'packages/theme::theme.custom_js',
                        'icon'        => null,
                        'url'         => route('theme.custom-js'),
                        'permissions' => ['theme.custom-js'],
                    ]);
            }

            admin_bar()
                ->registerLink(trans('packages/theme::theme.name'), route('theme.index'), 'appearance')
                ->registerLink(trans('packages/theme::theme.theme_options'), route('theme.options'), 'appearance');
        });

        $this->app->booted(function () {
            $file = public_path(ThemeFacade::path() . '/css/style.integration.css');
            if (File::exists($file)) {
                ThemeFacade::asset()
                    ->container('after_header')
                    ->usePath()
                    ->add('theme-style-integration-css', 'css/style.integration.css', [], [], filectime($file));
            }

            if (config('packages.theme.general.enable_custom_js')) {
                if (setting('custom_header_js')) {
                    add_filter(THEME_FRONT_HEADER, function ($html) {
                        return $html . ThemeSupport::getCustomJS('header');
                    }, 15);
                }

                if (setting('custom_body_js')) {
                    add_filter(THEME_FRONT_BODY, function ($html) {
                        return $html . ThemeSupport::getCustomJS('body');
                    }, 15);
                }

                if (setting('custom_footer_js')) {
                    add_filter(THEME_FRONT_FOOTER, function ($html) {
                        return $html . ThemeSupport::getCustomJS('footer');
                    }, 15);
                }
            }

            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(ThemeManagementServiceProvider::class);
    }
}
