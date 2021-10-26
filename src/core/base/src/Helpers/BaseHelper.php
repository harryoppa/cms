<?php

namespace TVHung\Base\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;

class BaseHelper
{
    /**
     * @param Carbon $timestamp
     * @param string $format
     * @return string
     */
    public function formatTime(Carbon $timestamp, ?string $format = 'j M Y H:i')
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);

        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public function formatDate(?string $date, ?string $format = null)
    {
        if (empty($format)) {
            $format = config('core.base.general.date_format.date');
        }

        if (empty($date)) {
            return $date;
        }
        
        return format_time(Carbon::parse($date), $format);
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public function formatDateTime(?string $date, string $format = null)
    {
        if (empty($format)) {
            $format = config('core.base.general.date_format.date_time');
        }

        if (empty($date)) {
            return $date;
        }

        return format_time(Carbon::parse($date), $format);
    }

    /**
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public function humanFilesize(int $bytes, int $precision = 2)
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }

    /**
     * @param string $file
     * @param bool $convertToArray
     * @return array|bool|mixed|null
     */
    public function getFileData($file, $convertToArray = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($convertToArray) {
                return json_decode($file, true);
            }

            return $file;
        }

        if (!$convertToArray) {
            return null;
        }

        return [];
    }

    /**
     * @param string $path
     * @param string|array $data
     * @param bool $json
     * @return bool|mixed
     */
    public function saveFileData($path, $data, $json = true)
    {
        try {
            if ($json) {
                $data = $this->jsonEncodePrettify($data);
            }

            if (!File::isDirectory(File::dirname($path))) {
                File::makeDirectory(File::dirname($path), 493, true);
            }

            File::put($path, $data);

            return true;
        } catch (Exception $exception) {
            info($exception->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @return string
     */
    public function jsonEncodePrettify($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $path
     * @param array $ignoreFiles
     * @return array
     */
    public function scanFolder($path, array $ignoreFiles = [])
    {
        try {
            if (File::isDirectory($path)) {
                $data = array_diff(scandir($path), array_merge(['.', '..', '.DS_Store'], $ignoreFiles));
                natsort($data);
                return $data;
            }

            return [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string
     */
    public function getAdminPrefix(): string
    {
        return config('core.base.general.admin_dir');
    }

    /**
     * @return string
     */
    public function getAdminMasterLayoutTemplate(): string
    {
        return apply_filters('base_filter_admin_master_layout_template', 'core/base::layouts.master');
    }

    /**
     * @return string
     */
    public function siteLanguageDirection()
    {
        return apply_filters(BASE_FILTER_SITE_LANGUAGE_DIRECTION, setting('locale_direction', 'ltr'));
    }

    /**
     * @return string
     */
    public function adminLanguageDirection()
    {
        $direction = session('admin_locale_direction', setting('admin_locale_direction', 'ltr'));

        return apply_filters(BASE_FILTER_SITE_LANGUAGE_DIRECTION, $direction);
    }

    /**
     * @return mixed
     */
    public function getHomepageId()
    {
        return theme_option('homepage_id', setting('show_on_front'));
    }

    /**
     * @param int $pageId
     * @return bool
     */
    public function isHomepage($pageId = null)
    {
        $homepageId = $this->getHomepageId();

        return $pageId && $homepageId && $pageId == $homepageId;
    }

    /**
     * @param Builder $query
     * @param string $table
     * @return bool
     */
    public function isJoined($query, $table): bool
    {
        $joins = $query->getQuery()->joins;

        if ($joins == null) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getRichEditor(): string
    {
        return setting('rich_editor', config('core.base.general.editor.primary'));
    }

    /**
     * @param array $array
     * @param $pairs
     * @param $key
     * @param string $position
     * @return array
     */
    public function arrayInsertTo(array $array, $pairs, $key, $position = 'after'): array
    {
        return array_insert($array, $pairs, $key, $position);
    }
}
