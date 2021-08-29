<?php

namespace TVHung\Base\Providers;

use TVHung\Base\Events\BeforeEditContentEvent;
use TVHung\Base\Events\CreatedContentEvent;
use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Base\Events\SendMailEvent;
use TVHung\Base\Events\UpdatedContentEvent;
use TVHung\Base\Listeners\BeforeEditContentListener;
use TVHung\Base\Listeners\CreatedContentListener;
use TVHung\Base\Listeners\DeletedContentListener;
use TVHung\Base\Listeners\SendMailListener;
use TVHung\Base\Listeners\UpdatedContentListener;
use Illuminate\Support\Facades\Event;
use File;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SendMailEvent::class          => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class    => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class    => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class    => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        Event::listen(['cache:cleared'], function () {
            File::delete([storage_path('cache_keys.json'), storage_path('settings.json')]);
        });
    }
}
