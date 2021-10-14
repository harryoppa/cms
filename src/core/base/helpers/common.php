<?php

use TVHung\Base\Facades\DashboardMenuFacade;
use TVHung\Base\Facades\PageTitleFacade;
use TVHung\Base\Supports\Editor;
use TVHung\Base\Supports\PageTitle;

if (!function_exists('anchor_link')) {
    /**
     * @param string $link
     * @param string $name
     * @param array $options
     * @return string
     * @throws Throwable
     * @deprecated
     */
    function anchor_link(?string $link, ?string $name, array $options = []): string
    {
        return Html::link($link, $name, $options);
    }
}

if (!function_exists('language_flag')) {
    /**
     * @param string $flag
     * @param string $name
     * @return string
     */
    function language_flag(string $flag, ?string $name = null): string
    {
        return Html::image(asset(BASE_LANGUAGE_FLAG_PATH . $flag . '.svg'), $name, ['title' => $name, 'width' => 16]);
    }
}

if (!function_exists('render_editor')) {
    /**
     * @param string $name
     * @param string|null $value
     * @param bool $withShortCode
     * @param array $attributes
     * @return string
     * @throws Throwable
     */
    function render_editor(string $name, ?string $value = null, $withShortCode = false, array $attributes = []): string
    {
        return (new Editor)->render($name, $value, $withShortCode, $attributes);
    }
}

if (!function_exists('is_in_admin')) {
    /**
     * @param bool $force
     * @return bool
     */
    function is_in_admin($force = false): bool
    {
        $prefix = BaseHelper::getAdminPrefix();

        $segments = array_slice(request()->segments(), 0, count(explode('/', $prefix)));

        $isInAdmin = implode('/', $segments) === $prefix;

        return $force ? $isInAdmin : apply_filters(IS_IN_ADMIN_FILTER, $isInAdmin);
    }
}

if (!function_exists('page_title')) {
    /**
     * @return PageTitle
     */
    function page_title()
    {
        return PageTitleFacade::getFacadeRoot();
    }
}

if (!function_exists('dashboard_menu')) {
    /**
     * @return \TVHung\Base\Supports\DashboardMenu
     */
    function dashboard_menu()
    {
        return DashboardMenuFacade::getFacadeRoot();
    }
}

if (!function_exists('get_cms_version')) {
    /**
     * @return string
     */
    function get_cms_version(): string
    {
        try {
            return trim(get_file_data(core_path('VERSION'), false));
        } catch (Exception $exception) {
            return '5.20';
        }
    }
}

if (!function_exists('platform_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function platform_path($path = null): string
    {
        return base_path('platform/' . $path);
    }
}

if (!function_exists('source_path')) {
    
    /**
     * @param string|null $path
     * @return string
     */
    function source_path($path = null): string
    {
        return base_path('vendor/tvhung/cms/src/' . $path);
    }
}

if (!function_exists('core_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function core_path($path = null): string
    {
        return source_path('core/' . $path);
    }
}

if (!function_exists('package_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function package_path($path = null): string
    {
        return source_path('packages/' . $path);
    }
}

if (!function_exists('array_insert'))
{
    /**
     * Insert an array into another array before/after a certain key
     *
     * @param array $array The initial array
     * @param array $pairs The array to insert
     * @param string $key The certain key
     * @param string $position Wether to insert the array before or after the key
     * @return array
     */
    function array_insert( $array, $pairs, $key, $position = 'after' ) {
        $key_pos = array_search( $key, array_keys( $array ) );

        if ( 'after' == $position )
            $key_pos++;

        if ( false !== $key_pos ) {
            $result = array_slice( $array, 0, $key_pos );
            $result = array_merge( $result, $pairs );
            $result = array_merge( $result, array_slice( $array, $key_pos ) );
        }
        else {
            $result = array_merge( $array, $pairs );
        }

        return $result;
    }
}

if (!function_exists('str_contains_all'))
{
    function str_contains_all($haystack, array $needles) {
        foreach ($needles as $needle) {
            if (!str_contains($haystack, $needle)) {
                return false;
            }
        }
        return true;
    }
}