<?php

namespace TVHung\Media\Commands;

use TVHung\Media\Repositories\Interfaces\MediaFileInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RvMedia;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:media:thumbnail:delete', 'Delete thumbnails for all images')]
class DeleteThumbnailCommand extends Command
{
    public function handle(MediaFileInterface $fileRepository): int
    {
        $files = $fileRepository->allBy([], [], ['url', 'mime_type', 'folder_id']);

        $this->info('Processing ' . $files->count() . ' ' . Str::plural('file', $files->count()) . '...');

        $errors = [];

        foreach ($files as $file) {
            if (!$file->canGenerateThumbnails()) {
                continue;
            }

            $this->info('Processing ' . $file->url);

            try {
                RvMedia::deleteThumbnails($file);
            } catch (Exception $exception) {
                $errors[] = $file->url;
                $this->error($exception->getMessage());
            }
        }

        $this->info('Thumbnails deleted');

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
