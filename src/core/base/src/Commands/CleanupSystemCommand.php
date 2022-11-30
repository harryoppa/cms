<?php

namespace TVHung\Base\Commands;

use TVHung\Base\Services\CleanDatabaseService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:system:cleanup', 'All the preloaded data will be deleted from the database except few mandatory record that is essential for running the software properly.')]
class CleanupSystemCommand extends Command
{
    public function handle(CleanDatabaseService $cleanDatabaseService): int
    {
        try {
            $this->info('Starting cleanup...');

            with(new TwoColumnDetail($this->getOutput()))->render(
                'Begin empty database',
                '<fg=yellow;options=bold>RUNNING</>'
            );
            $startTime = microtime(true);

            $cleanDatabaseService->execute();

            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

            with(new TwoColumnDetail($this->getOutput()))->render(
                'Empty database finished',
                '<fg=gray>' . $runTime . ' ms</> <fg=green;options=bold>DONE</>'
            );

            $this->info('Cleaned database...');
        } catch (Exception $exception) {
            $this->comment('Error!');
            $this->comment($exception->getMessage());
        }

        return self::SUCCESS;
    }
}
