<?php

namespace TVHung\Base\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:publish:assets', 'Publish assets (CSS, JS, Images...)')]
class PublishAssetsCommand extends Command
{
    public function handle(): int
    {
        $this->info('Publishing core, packages, plugins assets...');
        $this->call('vendor:publish', ['--tag' => 'cms-public', '--force' => true]);

        if (defined('THEME_MODULE_SCREEN_NAME')) {
            $this->info('Publishing theme assets...');
            $this->call('cms:theme:assets:publish');
        }

        $this->info('Published assets successfully!');

        return self::SUCCESS;
    }
}
