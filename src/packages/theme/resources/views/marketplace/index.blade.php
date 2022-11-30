@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')

    <div class="card ml-5 mr-5 " id="marketplace">
        <marketplace-themes></marketplace-themes>
    </div>
@stop

@push('header')
    <script>
        window.trans = {{ Js::from([
            'theme' => trans('packages/theme::marketplace'),
            'base' => trans('core/base::marketplace')
        ]) }};

        window.marketplace = {
            'list': '{{ route('theme.marketplace.ajax.list') }}',
            'detail': '{{ route('theme.marketplace.ajax.detail', [":id"]) }}',
            'install': '{{ route('theme.marketplace.ajax.install', [":id"]) }}'
        };
    </script>
@endpush
