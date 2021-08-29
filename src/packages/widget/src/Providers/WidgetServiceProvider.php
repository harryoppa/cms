<?php

namespace TVHung\Widget\Providers;

use TVHung\Base\Supports\Helper;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Widget\Factories\WidgetFactory;
use TVHung\Widget\Misc\LaravelApplicationWrapper;
use TVHung\Widget\Models\Widget;
use TVHung\Widget\Repositories\Caches\WidgetCacheDecorator;
use TVHung\Widget\Repositories\Eloquent\WidgetRepository;
use TVHung\Widget\Repositories\Interfaces\WidgetInterface;
use TVHung\Widget\WidgetGroupCollection;
use TVHung\Widget\Widgets\Text;
use Illuminate\Support\Facades\Event;
use File;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Theme;
use WidgetGroup;

class WidgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WidgetInterface::class, function () {
            return new WidgetCacheDecorator(new WidgetRepository(new Widget));
        });

        $this->app->bind('TVHung.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper);
        });

        $this->app->singleton('TVHung.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper);
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setNamespace('packages/widget')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->booted(function () {

            WidgetGroup::setGroup([
                'id'          => 'primary_sidebar',
                'name'        => trans('packages/widget::widget.primary_sidebar_name'),
                'description' => trans('packages/widget::widget.primary_sidebar_description'),
            ]);

            register_widget(Text::class);

            $widgetPath = theme_path(Theme::getThemeName() . '/widgets');
            $widgets = scan_folder($widgetPath);
            if (!empty($widgets) && is_array($widgets)) {
                foreach ($widgets as $widget) {
                    $registration = $widgetPath . '/' . $widget . '/registration.php';
                    if (File::exists($registration)) {
                        File::requireOnce($registration);
                    }
                }
            }
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-widget',
                    'priority'    => 3,
                    'parent_id'   => 'cms-core-appearance',
                    'name'        => 'packages/widget::widget.name',
                    'icon'        => null,
                    'url'         => route('widgets.index'),
                    'permissions' => ['widgets.index'],
                ]);

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('packages/widget::widget.name'), route('widgets.index'), 'appearance');
            }
        });
    }
}
