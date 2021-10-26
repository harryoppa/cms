@extends('core/base::layouts.base')

@section ('page')
    {{-- @include('core/base::layouts.partials.svg-icon') --}}

    <div class="page-wrapper">

        
        <div class="clearfix"></div>
        <div class="page-container">
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar navbar-collapse collapse">
                    <div class="page-logo">
                        @if (setting('admin_logo') || config('core.base.general.logo'))
                            <a href="{{ route('dashboard.index') }}">
                                <img src="{{ setting('admin_logo') ? RvMedia::getImageUrl(setting('admin_logo')) : url(config('core.base.general.logo')) }}" alt="logo" class="logo-default" />
                            </a>
                        @endif
            
                        {{-- @auth
                            <div class="menu-toggler sidebar-toggler">
                                <span></span>
                            </div>
                        @endauth --}}
                    </div>
                    <div class="sidebar">
                        <div class="sidebar-content">
                            <ul class="page-sidebar-menu page-header-fixed {{ session()->get('sidebar-menu-toggle') ? 'page-sidebar-menu-closed' : '' }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                                @include('core/base::layouts.partials.sidebar')
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-content-wrapper">
                <div class="page-content @if (Route::currentRouteName() == 'media.index') rv-media-integrate-wrapper @endif" style="min-height: 100vh">
                    @include('core/base::layouts.partials.top-header')
                    <div class="page-content-inside">
                        {!! Breadcrumbs::render('main', page_title()->getTitle(false)) !!}
                        <div class="clearfix"></div>
                        <div id="main">
                            @yield('content')
                        </div>
                    </div>

                    @include('core/base::layouts.partials.footer')
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@stop

@section('javascript')
    @include('core/media::partials.media')
@endsection

@push('footer')
    @routes
@endpush
