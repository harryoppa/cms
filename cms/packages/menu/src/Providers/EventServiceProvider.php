<?php

namespace TVHung\Menu\Providers;

use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Menu\Listeners\DeleteMenuNodeListener;
use TVHung\Menu\Listeners\UpdateMenuNodeUrlListener;
use TVHung\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedSlugEvent::class    => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
