<div class="top-menu">
    <ul class="nav navbar-nav float-start">
        <li class="dropdown p-0">
            <a id="sidebar-toggler" class="dropdown-toggle dropdown-header-name sidebar-toggler" style="padding-right: 15px; padding-left: 20px;" href="javascript:;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </a>
        </li>
    </ul>

    <ul class="nav navbar-nav float-end">
        @auth
            @if (BaseHelper::getAdminPrefix() != '')
                <li class="dropdown">
                    <a class="dropdown-toggle dropdown-header-name pe-2" href="{{ url('/') }}" target="_blank">
                        <i class="fa fa-globe"></i>
                        <span class="d-none d-sm-inline">
                            {{ trans('core/base::layouts.view_website') }}
                        </span>
                    </a>
                </li>
            @endif
            @if (Auth::check())
                {!! apply_filters(BASE_FILTER_TOP_HEADER_LAYOUT, null) !!}
            @endif

            @if (isset($themes) && is_array($themes) && count($themes) > 1 && setting('enable_change_admin_theme') != false)
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-inline d-sm-none"><i class="fas fa-palette"></i></span>
                        <span class="d-none d-sm-inline">{{ trans('core/base::layouts.theme') }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right">

                        @foreach ($themes as $name => $file)
                            @if ($activeTheme === $name)
                                <li class="active"><a href="{{ route('admin.theme', [$name]) }}">{{ Str::studly($name) }}</a></li>
                            @else
                                <li><a href="{{ route('admin.theme', [$name]) }}">{{ Str::studly($name) }}</a></li>
                            @endif
                        @endforeach

                    </ul>
                </li>
            @endif

            <li class="dropdown dropdown-user">
                <div class="dropdown-toggle dropdown-header-name dropdown-user-name" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center cursor-pointer">
                        <div class="mr-3 text-right">
                            <span class="username font-weight-bold"> {{ Auth::user()->name }} </span>
                            <small class="rolename d-block">Manager</small>
                        </div>
                        <div class="position-relative">
                            <img alt="{{ Auth::user()->name }}" class="avatar rounded-circle" src="{{ Auth::user()->avatar_url }}" />
                        </div>
                    </div>
                </div>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('users.profile.view', Auth::id()) }}"><i class="icon-user"></i> {{ trans('core/base::layouts.profile') }}</a></li>
                    <li><a href="{{ route('access.logout') }}" class="btn-logout"><i class="icon-key"></i> {{ trans('core/base::layouts.logout') }}</a></li>
                </ul>
            </li>
        @endauth
    </ul>
</div>
