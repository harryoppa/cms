<?php

namespace TVHung\PageCache\Console;

use TVHung\PageCache\Cache;
use Illuminate\Console\Command;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page-cache:clear {slug? : URL slug of page/directory to delete} {--recursive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear (all or part of) the page cache.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $cache = $this->laravel->make(Cache::class);
        $recursive = $this->option('recursive');
        $slug = $this->argument('slug');

        if (!$slug) {
            $this->clear($cache);
        } else if ($recursive) {
            $this->clear($cache, $slug);
        } else {
            $this->forget($cache, $slug);
        }
    }

    /**
     * Remove the cached file for the given slug.
     *
     * @param  \Silber\PageCache\Cache  $cache
     * @param  string  $slug
     * @return void
     */
    public function forget(Cache $cache, $slug)
    {
        if ($cache->forget($slug)) {
            $this->info("Page cache cleared for \"{$slug}\"");
        } else {
            $this->info("No page cache found for \"{$slug}\"");
        }
    }

    /**
     * Clear the full page cache.
     *
     * @param  \Silber\PageCache\Cache  $cache
     * @param  string|null  $path
     * @return void
     */
    public function clear(Cache $cache, $path = null)
    {
        if ($cache->clear($path)) {
            $this->info('Page cache cleared at '.$cache->getCachePath($path));
        } else {
            $this->warn('Page cache not cleared at '.$cache->getCachePath($path));
        }
    }
}
