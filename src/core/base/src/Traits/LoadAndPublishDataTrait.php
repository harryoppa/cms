<?php

namespace TVHung\Base\Traits;

use TVHung\Base\Supports\Helper;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * @mixin ServiceProvider
 */
trait LoadAndPublishDataTrait
{
    /**
     * @var string | null
     */
    protected ?string $namespace = null;

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = ltrim(rtrim($namespace, '/'), '/');

        $this->app['config']->set(['core.base.general.plugin_namespaces.' . File::basename($this->getPath()) => $namespace]);

        return $this;
    }

    protected function getPath(string $path = null): string
    {
        $reflection = new ReflectionClass($this);

        $modulePath = str_replace('/src/Providers', '', File::dirname($reflection->getFilename()));

        return $modulePath . ($path ? '/' . ltrim($path, '/') : '');
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     * @throws Exception
     */
    public function loadAndPublishConfigurations(array|string $fileNames): self
    {
        if (! is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->mergeConfigFrom($this->getConfigFilePath($fileName), $this->getDotedNamespace() . '.' . $fileName);

            if ($this->app->runningInConsole()) {
                $this->publishes([
                    $this->getConfigFilePath($fileName) => config_path($this->getDashedNamespace() . '/' . $fileName . '.php'),
                ], 'cms-config');
            }
        }

        return $this;
    }

    /**
     * Get path of the give file name in the given module
     * @param string $file
     * @return string
     * @throws Exception
     */
    protected function getConfigFilePath($file): string
    {
        return $this->getPath('config/' . $file . '.php');
    }

    /**
     * @return string
     */
    protected function getDashedNamespace(): string
    {
        return str_replace('.', '/', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }

    /**
     * Publish the given configuration file name (without extension) and the given module
     * @param array|string $fileNames
     * @return $this
     */
    public function loadRoutes(array|string $fileNames = ['web']): self
    {
        if (!is_array($fileNames)) {
            $fileNames = [$fileNames];
        }

        foreach ($fileNames as $fileName) {
            $this->loadRoutesFrom($this->getRouteFilePath($fileName));
        }

        return $this;
    }

    /**
     * @param string $file
     * @return string
     */
    protected function getRouteFilePath(string $file): string
    {
        return $this->getPath('routes/' . $file . '.php');
    }

    /**
     * @return $this
     */
    public function loadAndPublishViews(): self
    {
        $this->loadViewsFrom($this->getViewsPath(), $this->getDashedNamespace());
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [$this->getViewsPath() => resource_path('views/vendor/' . $this->getDashedNamespace())],
                'cms-views'
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getViewsPath(): string
    {
        return $this->getPath('/resources/views/');
    }

    /**
     * @return $this
     */
    public function loadAndPublishTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationsPath(), $this->getDashedNamespace());
        $this->publishes([$this->getTranslationsPath() => lang_path('vendor/' . $this->getDashedNamespace())],
            'cms-lang');

        return $this;
    }

    /**
     * @return string
     */
    protected function getTranslationsPath(): string
    {
        return $this->getPath('/resources/lang/');
    }

    /**
     * @return $this
     */
    public function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationsPath());

        return $this;
    }

    /**
     * @return string
     */
    protected function getMigrationsPath(): string
    {
        return $this->getPath('/database/migrations/');
    }

    /**
     * @param string|null $path
     * @return $this
     */
    public function publishAssets($path = null): self
    {
        if ($this->app->runningInConsole()) {
            if (empty($path)) {
                $path = 'vendor/core/' . $this->getDashedNamespace();
            }
            $this->publishes([$this->getAssetsPath() => public_path($path)], 'cms-public');
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getAssetsPath(): string
    {
        return $this->getPath('public/');
    }


    public function loadHelpers(): self
    {
        Helper::autoload($this->getPath('/helpers'));

        return $this;
    }
}
