<?php

namespace TVHung\Base\Providers;

use TVHung\Base\Commands\ClearLogCommand;
use TVHung\Base\Commands\InstallCommand;
use TVHung\Base\Commands\PublishAssetsCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ClearLogCommand::class,
            InstallCommand::class,
            PublishAssetsCommand::class,
        ]);
    }
}
