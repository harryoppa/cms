<?php

namespace TVHung\Base\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:install', 'Install CMS')]
class InstallCommand extends Command
{
    use ConfirmableTrait;

    public function handle(): int
    {
        $this->info('Starting installation...');

        $this->call('migrate:fresh');

        if ($this->confirmToProceed('Do you want to add a new super user?', true)) {
            $this->call('cms:user:create');
        }

        $this->info('Activating all plugins...');
        $this->call('cms:plugin:activate:all');

        if ($this->confirmToProceed('Do you want to install sample data?', true)) {
            $this->call('db:seed');
        }

        $this->info('Publishing assets...');
        $this->call('cms:publish:assets');

        $this->info('Publishing lang...');
        $this->call('vendor:publish', ['--tag' => 'cms-lang']);

        $this->info('Install CMS successfully!');

        return self::SUCCESS;
    }
}
