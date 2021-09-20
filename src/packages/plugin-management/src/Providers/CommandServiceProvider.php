<?php

namespace TVHung\PluginManagement\Providers;

use TVHung\PluginManagement\Commands\PluginActivateAllCommand;
use TVHung\PluginManagement\Commands\PluginDeactivateAllCommand;
use TVHung\PluginManagement\Commands\PluginActivateCommand;
use TVHung\PluginManagement\Commands\PluginAssetsPublishCommand;
use TVHung\PluginManagement\Commands\PluginDeactivateCommand;
use TVHung\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateAllCommand::class,
        ]);
    }
}
