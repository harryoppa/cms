@if (theme_option('favicon'))
    <link rel="shortcut icon" href="{{ RvMedia::getImageUrl(theme_option('favicon')) }}">
@endif

@if (env('SHOW_PROFILE'))
<!--


    
_                           _       _       _                                          
| |_ _ __ __ _ _ __   __   _(_)_ __ | |__   | |__  _   _ _ __   __ _     _ __ ___   ___ 
| __| '__/ _` | '_ \  \ \ / | | '_ \| '_ \  | '_ \| | | | '_ \ / _` |   | '_ ` _ \ / _ \
| |_| | | (_| | | | |  \ V /| | | | | | | | | | | | |_| | | | | (_| |  _| | | | | |  __/
 \__|_|  \__,_|_| |_|   \_/ |_|_| |_|_| |_| |_| |_|\__,_|_| |_|\__, | (_|_| |_| |_|\___|
                                                          |___/                    



                                                        -->
@endif

{!! SeoHelper::render() !!}

{!! Theme::asset()->styles() !!}
{!! Theme::asset()->container('after_header')->styles() !!}
{!! Theme::asset()->container('header')->scripts() !!}

{!! apply_filters(THEME_FRONT_HEADER, null) !!}
