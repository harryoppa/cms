<?php

namespace TVHung\SeoHelper\Providers;

use TVHung\Base\Events\CreatedContentEvent;
use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Base\Events\UpdatedContentEvent;
use TVHung\SeoHelper\Listeners\CreatedContentListener;
use TVHung\SeoHelper\Listeners\DeletedContentListener;
use TVHung\SeoHelper\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
