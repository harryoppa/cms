<?php

namespace TVHung\Media\Commands;

use TVHung\Media\Chunks\ChunkFile;
use TVHung\Media\Chunks\Storage\ChunkStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('cms:media:chunks:clear', 'Clears the chunks upload directory. Deletes only .part objects.')]
class ClearChunksCommand extends Command
{
    public function handle(ChunkStorage $storage): int
    {
        $verbose = OutputInterface::VERBOSITY_VERBOSE;

        // Try to get the old chunk files
        $oldFiles = $storage->oldChunkFiles();

        if ($oldFiles->isEmpty()) {
            $this->warn('Chunks: no old files');

            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d chunk files', $oldFiles->count()), $verbose);
        $deleted = 0;

        /**
         * @var ChunkFile $file
         */
        foreach ($oldFiles as $file) {
            $this->comment('> ' . $file, $verbose);

            if ($file->delete()) {
                ++$deleted;
            } else {
                $this->error('> chunk not deleted: ' . $file);
            }
        }

        $this->info('Chunks: cleared ' . $deleted . ' ' . Str::plural('file', $deleted));

        return self::SUCCESS;
    }
}
