<?php

namespace TVHung\Base\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:log:clear', 'Clear log files')]
class ClearLogCommand extends Command
{
    public function handle(Filesystem $filesystem): int
    {
        $logPath = storage_path('logs');

        if ($filesystem->isDirectory($logPath)) {
            foreach ($filesystem->allFiles($logPath) as $file) {
                $filesystem->delete($file->getPathname());
            }
        }

        $this->info('Clear log files successfully!');

        return self::SUCCESS;
    }
}
