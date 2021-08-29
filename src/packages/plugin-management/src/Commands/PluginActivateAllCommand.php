<?php

namespace TVHung\PluginManagement\Commands;

use TVHung\PluginManagement\Services\PluginService;
use File;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class PluginActivateAllCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:activate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate all plugins in /plugins directory';

    /**
     * @var PluginService
     */
    protected $pluginService;

    /**
     * PluginActivateCommand constructor.
     * @param PluginService $pluginService
     */
    public function __construct(PluginService $pluginService)
    {
        parent::__construct();

        $this->pluginService = $pluginService;
    }

    /**
     * @return boolean
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $pluginPath = public_path('vendor/core/plugins');

        if (!File::isDirectory($pluginPath)) {
            File::makeDirectory($pluginPath, 0755, true);
        }

        foreach (scan_folder(plugin_path()) as $plugin) {
            $this->pluginService->activate($plugin);
        }

        $this->info('Activated successfully!');

        return 0;
    }
}
