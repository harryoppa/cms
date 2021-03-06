<?php

namespace TVHung\PageCache;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Cache
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container|null
     */
    protected $container = null;

    /**
     * The directory in which to store the cached pages.
     *
     * @var string|null
     */
    protected $cachePath = null;

    /**
     * Constructor.
     *
     * @var \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Sets the container instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Sets the directory in which to store the cached pages.
     *
     * @param  string  $path
     * @return void
     */
    public function setCachePath($path)
    {
        $this->cachePath = rtrim($path, '\/');
    }

    /**
     * Gets the path to the cache directory.
     *
     * @param  string  ...$paths
     * @return string
     *
     * @throws \Exception
     */
    public function getCachePath()
    {
        $base = $this->cachePath ? $this->cachePath : $this->getDefaultCachePath();

        if (is_null($base)) {
            throw new Exception('Cache path not set.');
        }

        return $this->join(array_merge([$base], func_get_args()));
    }

    /**
     * Join the given paths together by the system's separator.
     *
     * @param  string[] $paths
     * @return string
     */
    protected function join(array $paths)
    {
        $trimmed = array_map(function ($path) {
            return trim($path, '/');
        }, $paths);

        return $this->matchRelativity(
            $paths[0], implode('/', array_filter($trimmed))
        );
    }

    /**
     * Makes the target path absolute if the source path is also absolute.
     *
     * @param  string  $source
     * @param  string  $target
     * @return string
     */
    protected function matchRelativity($source, $target)
    {
        return $source[0] == '/' ? '/'.$target : $target;
    }

    /**
     * Caches the given response if we determine that it should be cache.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return $this
     */
    public function cacheIfNeeded(Request $request, Response $response)
    {
        if ($this->shouldCache($request, $response)) {
            $this->cache($request, $response);
        }

        return $this;
    }

    /**
     * Determines whether the given request/response pair should be cached.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    public function shouldCache(Request $request, Response $response)
    {
        return $request->isMethod('GET') && $response->getStatusCode() == 200;
    }

    /**
     * Cache the response to a file.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function cache(Request $request, Response $response)
    {
        $paths = $this->getDirectoryAndFileNames($request, $response);

        foreach ($paths as $index => $pathData) {
            list($path, $file) = $pathData;

            $this->files->makeDirectory($path, 0775, true, true);
            $fileName = $this->join([$path, $file]);

            if ($this->files->exists($fileName)) continue;

            $this->files->put(
                $fileName,
                $index === 0 ? sanitize_output($response->getContent()) : sanitize_output('
                    <!DOCTYPE html>
                    <html>
                      <head>
                        <meta http-equiv="refresh" content="0; url=\''.$request->getRequestUri().'\'" />
                      </head>
                    </html>
                '),
                true
            );
        }


    }

    /**
     * Remove the cached file for the given slug.
     *
     * @param  string  $slug
     * @return bool
     */
    public function forget($slug)
    {
        $deletedHtml = $this->files->delete($this->getCachePath($slug.'.html'));
        $deletedJson = $this->files->delete($this->getCachePath($slug.'.json'));

        return $deletedHtml || $deletedJson;
    }

    /**
     * Clear the full cache directory, or a subdirectory.
     *
     * @param  string|null
     * @return bool
     */
    public function clear($path = null)
    {
        return $this->files->deleteDirectory($this->getCachePath($path), true);
    }

    /**
     * Get the names of the directory and file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return array
     */
    protected function getDirectoryAndFileNames($request, $response)
    {
        $segments = explode('/', ltrim($request->getPathInfo(), '/'));

        $filename = $this->aliasFilename(array_pop($segments));
        $extension = $this->guessFileExtension($response);

        $file = "{$filename}.{$extension}";

        $locale = '';

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $locale = '/' . \Language::getCurrentLocale();
        }

        return [
            [$this->getCachePath(implode('/', $segments)), $file],
            [$this->getCachePath(implode('/', $segments)) . $locale, $file]
        ];
    }

    /**
     * Alias the filename if necessary.
     *
     * @param  string  $filename
     * @return string
     */
    protected function aliasFilename($filename)
    {
        return $filename ?: 'pc__index__pc';
    }

    /**
     * Get the default path to the cache directory.
     *
     * @return string|null
     */
    protected function getDefaultCachePath()
    {
        if ($this->container && $this->container->bound('path.public')) {
            return $this->container->make('path.public').'/page-cache';
        }
    }

    /**
     * Guess the correct file extension for the given response.
     *
     * Currently, only JSON and HTML are supported.
     *
     * @return string
     */
    protected function guessFileExtension($response)
    {
        if ($response instanceof JsonResponse) {
            return 'json';
        }

        return 'html';
    }

}
