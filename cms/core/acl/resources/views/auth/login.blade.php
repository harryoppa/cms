@extends('core/acl::auth.master')

@section('content')
    <p>{{ trans('core/acl::auth.sign_in_below') }}:</p>

    {!! Form::open(['route' => 'access.login', 'class' => 'login-form']) !!}
        <div class="form-group" id="emailGroup">
            <label>{{ trans('core/acl::auth.login.username') }}</label>
            {!! Form::text('username', request()->input('email', old('username'), ['class' => 'form-control', 'placeholder' => trans('core/acl::auth.login.username')]) !!}
        </div>

        <div class="form-group" id="passwordGroup">
            <label>{{ trans('core/acl::auth.login.password') }}</label>
            {!! Form::input('password', 'password', request()->input('email'), ['class' => 'form-control', 'placeholder' => trans('core/acl::auth.login.password')]) !!}
        </div>

        <div>
            <label>
                {!! Form::checkbox('remember', '1', true, ['class' => 'hrv-checkbox']) !!} {{ trans('core/acl::auth.login.remember') }}
            </label>
        </div>
        <br>

        <button type="submit" class="btn btn-block login-button">
            <span class="signin">{{ trans('core/acl::auth.login.login') }}</span>
        </button>
        <div class="clearfix"></div>

        <br>
        <p><a class="lost-pass-link" href="{{ route('access.password.request') }}" title="{{ trans('core/acl::auth.forgot_password.title') }}">{{ trans('core/acl::auth.lost_your_password') }}</a></p>

        {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \TVHung\ACL\Models\User::class) !!}

    {!! Form::close() !!}
@stop
