<?php

namespace TVHung\Theme\Supports;

use Illuminate\Support\Arr;

class Youtube
{
    /**
     * @param string $url
     * @return string
     */
    public static function getYoutubeVideoEmbedURL(string $url): string
    {
        $url = rtrim($url, '/');

        if (str_contains($url, 'watch?v=')) {
            $url = str_replace('watch?v=', 'embed/', $url);
        } else {
            $exploded = explode('/', $url);

            if (count($exploded) > 1) {
                $url = 'https://www.youtube.com/embed/' . Arr::last($exploded);
            }
        }

        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function getYoutubeWatchURL(string $url): string
    {
        $url = rtrim($url, '/');

        if (str_contains($url, 'embed/')) {
            $url = str_replace('embed/', 'watch?v=', $url);
        } else {
            $exploded = explode('/', $url);

            if (count($exploded) > 1) {
                $url = 'https://www.youtube.com/watch?v=' . Arr::last($exploded);
            }
        }

        return $url;
    }

    /**
     * @param string $url
     * @return null|string
     */
    public static function getYoutubeVideoID(string $url): ?string
    {
        $regExp = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/';

        preg_match($regExp, $url, $matches);

        if ($matches && strlen($matches[7]) == 11) {
            return $matches[7];
        }

        return null;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function isYoutubeURL(string $url): bool
    {
        $regExp = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';

        return preg_match($regExp, $url);
    }
}
