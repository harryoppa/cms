<?php

namespace TVHung\ACL\Providers;

use TVHung\ACL\Events\RoleAssignmentEvent;
use TVHung\ACL\Events\RoleUpdateEvent;
use TVHung\ACL\Listeners\LoginListener;
use TVHung\ACL\Listeners\RoleAssignmentListener;
use TVHung\ACL\Listeners\RoleUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RoleUpdateEvent::class     => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class               => [
            LoginListener::class,
        ],
    ];
}
