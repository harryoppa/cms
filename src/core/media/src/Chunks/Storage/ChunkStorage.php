<?php

namespace TVHung\Media\Chunks\Storage;

use TVHung\Media\Chunks\ChunkFile;
use Closure;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Support\Collection;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Local\LocalFilesystemAdapter;
use RuntimeException;
use RvMedia;
use Storage;

class ChunkStorage
{
    const CHUNK_EXTENSION = 'part';

    /**
     * @var AbstractConfig
     */
    protected $config;
    /**
     * The disk that holds the chunk files.
     *
     * @var FilesystemContract|FilesystemAdapter
     */
    protected $disk;
    /**
     * @var Local|LocalFilesystemAdapter
     */
    protected $diskAdapter;
    protected $isLocalDisk;

    /**
     * @var
     */
    protected $usingDeprecatedLaravel;

    /**
     * ChunkStorage constructor.
     */
    public function __construct()
    {
        $this->config = RvMedia::getConfig('chunk');

        $this->usingDeprecatedLaravel = class_exists(LocalFilesystemAdapter::class) === false;
        
        // Cache the storage path
        $this->disk = Storage::disk($this->config['storage']['disk']);

        
        if ($this->usingDeprecatedLaravel === false) {

            // try to get the adapter
            if (!method_exists($this->disk, 'getAdapter')) {
                throw new RuntimeException('FileSystem driver must have an adapter implemented');
            }

            // get the disk adapter
            $this->diskAdapter = $this->disk->getAdapter();

            // check if its local adapter
            $this->isLocalDisk = $this->diskAdapter instanceof LocalFilesystemAdapter;
        } else {
            $driver = $this->driver();

            // try to get the adapter
            if (!method_exists($driver, 'getAdapter')) {
                throw new RuntimeException('FileSystem driver must have an adapter implemented');
            }

            // get the disk adapter
            $this->diskAdapter = $driver->getAdapter();

            // check if its local adapter
            $this->isLocalDisk = $this->diskAdapter instanceof Local;
        }
    }

    /**
     * Returns the driver.
     *
     * @return FilesystemInterface
     */
    public function driver()
    {
        return $this->disk()->getDriver();
    }

    /**
     * @return FilesystemAdapter
     */
    public function disk()
    {
        return $this->disk;
    }

    /**
     * Returns the application instance of the chunk storage.
     *
     * @return ChunkStorage
     */
    public static function storage()
    {
        return app(self::class);
    }

    /**
     * The current path for chunks directory.
     *
     * @return string
     *
     * @throws RuntimeException when the adapter is not local
     */
    public function getDiskPathPrefix()
    {
        if ($this->usingDeprecatedLaravel === true && $this->isLocalDisk) {
            return $this->diskAdapter->getPathPrefix();
        }

        if ($this->isLocalDisk) {
            return $this->disk->path('');
        }

        throw new RuntimeException('The full path is not supported on current disk - local adapter supported only');
    }

    /**
     * Returns the old chunk files.
     *
     * @return Collection<ChunkFile> collection of a ChunkFile objects
     */
    public function oldChunkFiles()
    {
        $files = $this->files();
        // If there are no files, lets return the empty collection
        if ($files->isEmpty()) {
            return $files;
        }

        // Build the timestamp
        $timeToCheck = strtotime($this->config['clear']['timestamp']);
        $collection = new Collection;

        // Filter the collection with files that are not correct chunk file
        // Loop all current files and filter them by the time
        $files->each(function ($file) use ($timeToCheck, $collection) {
            // get the last modified time to check if the chunk is not new
            $modified = $this->disk()->lastModified($file);

            // Delete only old chunk
            if ($modified < $timeToCheck) {
                $collection->push(new ChunkFile($file, $modified, $this));
            }
        });

        return $collection;
    }

    /**
     * Returns an array of files in the chunks directory.
     *
     * @param Closure|null $rejectClosure
     * @return Collection
     * @see FilesystemAdapter::files()
     */
    public function files($rejectClosure = null)
    {
        // We need to filter files we don't support, lets use the collection
        $filesCollection = new Collection($this->disk->files($this->directory(), false));

        return $filesCollection->reject(function ($file) use ($rejectClosure) {
            // Ensure the file ends with allowed extension
            $shouldReject = !preg_match('/.' . self::CHUNK_EXTENSION . '$/', $file);
            if ($shouldReject) {
                return true;
            }

            if (is_callable($rejectClosure)) {
                return $rejectClosure($file);
            }

            return false;
        });
    }

    /**
     * The current chunks directory.
     *
     * @return string
     */
    public function directory()
    {
        return $this->config['storage']['chunks'] . '/';
    }
}
