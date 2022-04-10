<?php
use Illuminate\Support\Facades\Artisan;

if (!function_exists('sanitize_output'))
{
    function sanitize_output($buffer) {

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
    
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
    
        $buffer = preg_replace($search, $replace, $buffer);
    
        return $buffer;
    }
}

if (!function_exists('clear_page_cache'))
{
    function clear_page_cache($slug = '', $recursive = false): int
    {
        return Artisan::call('page-cache:clear', ['--recursive' => $recursive, 'slug' => $slug]);
    }
}

if (!function_exists('is_page_cache_enabled'))
{
    function is_page_cache_enabled(): bool
    {
        return env('ENABLE_PAGE_CACHE', false);
    }
}