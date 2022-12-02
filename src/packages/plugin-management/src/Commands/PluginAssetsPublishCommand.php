<?php

namespace TVHung\PluginManagement\Commands;

use TVHung\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand('cms:plugin:assets:publish', 'Publish assets for a plugin')]
class PluginAssetsPublishCommand extends Command
{
    public function handle(PluginService $pluginService): int
    {
        if (! preg_match('/^[a-z0-9\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');

            return self::FAILURE;
        }

        $plugin = strtolower($this->argument('name'));
        $result = $pluginService->publishAssets($plugin);

        if ($result['error']) {
            $this->error($result['message']);

            return self::FAILURE;
        }

        $this->info($result['message']);

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The plugin that you want to publish assets');
    }
}
