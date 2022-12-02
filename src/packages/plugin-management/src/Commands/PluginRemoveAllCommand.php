<?php

namespace TVHung\PluginManagement\Commands;

use BaseHelper;
use TVHung\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:plugin:remove:all', 'Remove all plugins in /plugins directory')]
class PluginRemoveAllCommand extends Command
{
    use ConfirmableTrait;

    public function handle(PluginService $pluginService): int
    {
        if (! $this->confirmToProceed('Are you sure you want to remove ALL plugins?', true)) {
            return self::FAILURE;
        }

        foreach (BaseHelper::scanFolder(plugin_path()) as $plugin) {
            $pluginService->remove($plugin);
        }

        $this->info('Removed successfully!');

        return self::SUCCESS;
    }
}
