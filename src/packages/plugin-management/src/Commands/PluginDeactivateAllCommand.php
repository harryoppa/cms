<?php

namespace TVHung\PluginManagement\Commands;

use BaseHelper;
use TVHung\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:plugin:deactivate:all', 'Deactivate all plugins in /plugins directory')]
class PluginDeactivateAllCommand extends Command
{
    public function handle(PluginService $pluginService): int
    {
        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $pluginService->deactivate($plugin);
        }

        $this->info('Deactivated successfully!');

        return self::SUCCESS;
    }
}
