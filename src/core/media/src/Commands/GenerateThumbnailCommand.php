<?php

namespace TVHung\Media\Commands;

use TVHung\Media\Repositories\Interfaces\MediaFileInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RvMedia;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:media:thumbnail:generate', 'Generate thumbnails for images')]
class GenerateThumbnailCommand extends Command
{
    public function handle(MediaFileInterface $fileRepository): int
    {
        $this->info('Starting to generate thumbnails...');

        $files = $fileRepository->allBy([], [], ['url', 'mime_type', 'folder_id']);

        $this->info('Processing ' . $files->count() . ' ' . Str::plural('file', $files->count()) . '...');

        $errors = [];

        foreach ($files as $file) {
            try {
                RvMedia::generateThumbnails($file);
            } catch (Exception $exception) {
                $errors[] = $file->url;
                $this->error($exception->getMessage());
            }
        }

        $this->info('Generated media thumbnails successfully!');

        $errors = array_unique($errors);

        $errors = array_map(function ($item) {
            return [$item];
        }, $errors);

        if ($errors) {
            $this->info('We are unable to regenerate thumbnail for these files:');

            $this->table(['File directory'], $errors);

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
