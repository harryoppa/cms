<?php

namespace TVHung\PluginManagement\Providers;

use TVHung\PluginManagement\Commands\ClearCompiledCommand;
use TVHung\PluginManagement\Commands\IlluminateClearCompiledCommand as OverrideIlluminateClearCompiledCommand;
use TVHung\PluginManagement\Commands\PackageDiscoverCommand;
use TVHung\PluginManagement\Commands\PluginActivateAllCommand;
use TVHung\PluginManagement\Commands\PluginActivateCommand;
use TVHung\PluginManagement\Commands\PluginAssetsPublishCommand;
use TVHung\PluginManagement\Commands\PluginDeactivateAllCommand;
use TVHung\PluginManagement\Commands\PluginDeactivateCommand;
use TVHung\PluginManagement\Commands\PluginDiscoverCommand;
use TVHung\PluginManagement\Commands\PluginListCommand;
use TVHung\PluginManagement\Commands\PluginRemoveAllCommand;
use TVHung\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand as IlluminateClearCompiledCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand as IlluminatePackageDiscoverCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend(IlluminatePackageDiscoverCommand::class, function () {
            return $this->app->make(PackageDiscoverCommand::class);
        });

        $this->app->extend(IlluminateClearCompiledCommand::class, function () {
            return $this->app->make(OverrideIlluminateClearCompiledCommand::class);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
                ClearCompiledCommand::class,
                PluginDiscoverCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateCommand::class,
            PluginDeactivateAllCommand::class,
            PluginRemoveCommand::class,
            PluginRemoveAllCommand::class,
            PluginListCommand::class,
        ]);
    }
}
