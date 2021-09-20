<?php

namespace TVHung\ACL\Providers;

use TVHung\ACL\Http\Middleware\Authenticate;
use TVHung\ACL\Http\Middleware\RedirectIfAuthenticated;
use TVHung\ACL\Models\Activation;
use TVHung\ACL\Models\Role;
use TVHung\ACL\Models\User;
use TVHung\ACL\Repositories\Caches\RoleCacheDecorator;
use TVHung\ACL\Repositories\Eloquent\ActivationRepository;
use TVHung\ACL\Repositories\Eloquent\RoleRepository;
use TVHung\ACL\Repositories\Eloquent\UserRepository;
use TVHung\ACL\Repositories\Interfaces\ActivationInterface;
use TVHung\ACL\Repositories\Interfaces\RoleInterface;
use TVHung\ACL\Repositories\Interfaces\UserInterface;
use TVHung\Base\Traits\LoadAndPublishDataTrait;
use EmailHandler;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $this->loadHelpers();

        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);

        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User);
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation);
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleCacheDecorator(new RoleRepository(new Role));
        });
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this
            ->setNamespace('core/acl')
            ->loadAndPublishConfigurations(['general', 'permissions', 'email'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes(['web'])
            ->loadMigrations();

        $this->garbageCollect();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-role-permission',
                    'priority'    => 2,
                    'parent_id'   => 'cms-core-platform-administration',
                    'name'        => 'core/acl::permissions.role_permission',
                    'icon'        => null,
                    'url'         => route('roles.index'),
                    'permissions' => ['roles.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-user',
                    'priority'    => 3,
                    'parent_id'   => 'cms-core-platform-administration',
                    'name'        => 'core/acl::users.users',
                    'icon'        => null,
                    'url'         => route('users.index'),
                    'permissions' => ['users.index'],
                ]);
        });

        $this->app->booted(function () {
            config()->set(['auth.providers.users.model' => User::class]);

            EmailHandler::addTemplateSettings('acl', config('core.acl.email', []), 'core');

            $this->app->register(HookServiceProvider::class);
        });
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function garbageCollect()
    {
        $config = $this->app->make('config')->get('core.acl.general');

        $this->sweep($this->app->make(ActivationInterface::class), $config['activations']['lottery']);
    }

    /**
     * Sweep expired codes.
     *
     * @param mixed $repository
     * @param array $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param array $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
