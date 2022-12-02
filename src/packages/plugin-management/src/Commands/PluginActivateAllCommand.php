<?php

namespace TVHung\PluginManagement\Commands;

use BaseHelper;
use TVHung\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:plugin:activate:all', 'Activate all plugins in /plugins directory')]
class PluginActivateAllCommand extends Command
{
    public function handle(PluginService $pluginService): int
    {
        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $pluginService->activate($plugin);
        }

        $this->info('Activated successfully!');

        return self::SUCCESS;
    }
}
