<?php

namespace TVHung\Base\Providers;

use App\Http\Middleware\VerifyCsrfToken;
use TVHung\Base\Exceptions\Handler;
use TVHung\Base\Facades\MacroableModelsFacade;
use TVHung\Base\Http\Middleware\CoreMiddleware;
use TVHung\Base\Http\Middleware\DisableInDemoModeMiddleware;
use TVHung\Base\Http\Middleware\HttpsProtocolMiddleware;
use TVHung\Base\Http\Middleware\LocaleMiddleware;
use TVHung\Base\Models\MetaBox as MetaBoxModel;
use TVHung\Base\Repositories\Caches\MetaBoxCacheDecorator;
use TVHung\Base\Repositories\Eloquent\MetaBoxRepository;
use TVHung\Base\Repositories\Interfaces\MetaBoxInterface;
use TVHung\Base\Supports\BreadcrumbsManager;
use TVHung\Base\Supports\CustomResourceRegistrar;
use TVHung\Base\Supports\Helper;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use TVHung\Setting\Providers\SettingServiceProvider;
use TVHung\Setting\Supports\SettingStore;
use TVHung\Support\Http\Middleware\BaseMiddleware;
use DateTimeZone;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Arr;
use MetaBox;
use URL;

class BaseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->setNamespace('core/base')
            ->loadAndPublishConfigurations(['general']);

        $this->app->register(SettingServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $this->app->singleton(BreadcrumbsManager::class, BreadcrumbsManager::class);

        $this->app->bind(MetaBoxInterface::class, function () {
            return new MetaBoxCacheDecorator(new MetaBoxRepository(new MetaBoxModel));
        });

        $this->app->make('config')->set([
            'session.cookie'                   => 'tvhung_session',
            'ziggy.except'                     => ['debugbar.*'],
            'app.debug_blacklist'              => [
                '_ENV'    => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_SERVER' => [
                    'APP_KEY',
                    'ADMIN_DIR',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'REDIS_PASSWORD',
                    'MAIL_PASSWORD',
                    'PUSHER_APP_KEY',
                    'PUSHER_APP_SECRET',
                ],
                '_POST'   => [
                    'password',
                ],
            ],
            'datatables-buttons.pdf_generator' => 'excel',
            'excel.exports.csv.use_bom'        => true,
        ]);
    }

    public function boot()
    {
        $this->setNamespace('core/base')
            ->loadAndPublishConfigurations(['permissions', 'assets'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', LocaleMiddleware::class);
        $router->pushMiddlewareToGroup('web', HttpsProtocolMiddleware::class);
        $router->aliasMiddleware('preventDemo', DisableInDemoModeMiddleware::class);
        $router->middlewareGroup('core', [CoreMiddleware::class]);

        if ($this->app->environment('demo')) {
            $this->app->instance(VerifyCsrfToken::class, new BaseMiddleware);
        }

        $this->app->booted(function () {
            do_action(BASE_ACTION_INIT);
            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 2);

            $config = $this->app->make('config');
            $setting = $this->app->make(SettingStore::class);
            $timezone = $setting->get('time_zone', $config->get('app.timezone'));
            $locale = $setting->get('locale', $config->get('core.base.general.locale', $config->get('app.locale')));

            $config->set([
                'app.locale'   => $locale,
                'app.timezone' => $timezone,
            ]);

            $this->app->setLocale($locale);

            if (in_array($timezone, DateTimeZone::listIdentifiers())) {
                date_default_timezone_set($timezone);
            }
        });

        Event::listen(RouteMatched::class, function () {
            $this->registerDefaultMenus();
        });

        AliasLoader::getInstance()->alias('MacroableModels', MacroableModelsFacade::class);

        Paginator::useBootstrap();

        $forceUrl = $this->app->make('config')->get('core.base.general.force_root_url');
        if (!empty($forceUrl)) {
            URL::forceRootUrl($forceUrl);
        }

        $forceSchema = $this->app->make('config')->get('core.base.general.force_schema');
        if (!empty($forceSchema)) {
            URL::forceScheme($forceSchema);
        }

        $this->configureIni();
        $this->createMacroQueries();

        $config = $this->app->make('config');

        $config->set(['purifier.settings.default' => $config->get('core.base.general.purifier')]);
    }

    /**
     * Add default dashboard menu for core
     */
    public function registerDefaultMenus()
    {
        dashboard_menu()
            ->registerItem([
                'id'          => 'cms-core-platform-administration',
                'priority'    => 999,
                'parent_id'   => null,
                'name'        => 'core/base::layouts.platform_admin',
                'icon'        => 'fa fa-user-shield',
                'url'         => null,
                'permissions' => ['users.index'],
            ])
            ->registerItem([
                'id'          => 'cms-core-system-information',
                'priority'    => 5,
                'parent_id'   => 'cms-core-platform-administration',
                'name'        => 'core/base::system.info.title',
                'icon'        => null,
                'url'         => route('system.info'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ])
            ->registerItem([
                'id'          => 'cms-core-system-cache',
                'priority'    => 6,
                'parent_id'   => 'cms-core-platform-administration',
                'name'        => 'core/base::cache.cache_management',
                'icon'        => null,
                'url'         => route('system.cache'),
                'permissions' => [ACL_ROLE_SUPER_USER],
            ]);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function configureIni()
    {
        $currentLimit = ini_get('memory_limit');
        $currentLimitInt = Helper::convertHrToBytes($currentLimit);

        $memoryLimit = $this->app->make('config')->get('core.base.general.memory_limit');

        // Define memory limits.
        if (!$memoryLimit) {
            if (false === Helper::isIniValueChangeable('memory_limit')) {
                $memoryLimit = $currentLimit;
            } else {
                $memoryLimit = '64M';
            }
        }

        // Set memory limits.
        $limitInt = Helper::convertHrToBytes($memoryLimit);
        if (-1 !== $currentLimitInt && (-1 === $limitInt || $limitInt > $currentLimitInt)) {
            ini_set('memory_limit', $memoryLimit);
        }
    }

    protected function createMacroQueries()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });

            return $this;
        });
    }

    /**
     * @return array|string[]
     */
    public function provides(): array
    {
        return [BreadcrumbsManager::class];
    }
}
