<script type="text/javascript">
    var TvhungVariables = TvhungVariables || {};

    @if (Auth::check())
        TvhungVariables.languages = {
            tables: {!! json_encode(trans('core/base::tables'), JSON_HEX_APOS) !!},
            notices_msg: {!! json_encode(trans('core/base::notices'), JSON_HEX_APOS) !!},
            pagination: {!! json_encode(trans('pagination'), JSON_HEX_APOS) !!},
            system: {
                'character_remain': '{{ trans('core/base::forms.character_remain') }}'
            },
        };
        TvhungVariables.authorized = "{{ setting('membership_authorization_at') && now()->diffInDays(Carbon\Carbon::createFromFormat('Y-m-d H:i:s', setting('membership_authorization_at'))) <= 7 ? 1 : 0 }}";
    @else
        TvhungVariables.languages = {
            notices_msg: {!! json_encode(trans('core/base::notices'), JSON_HEX_APOS) !!},
        };
    @endif
</script>

@push('footer')
    @if (session()->has('success_msg') || session()->has('error_msg') || (isset($errors) && $errors->count() > 0) || isset($error_msg))
        <script type="text/javascript">
            $(document).ready(function () {
                @if (session()->has('success_msg'))
                TVHung.showSuccess('{{ session('success_msg') }}');
                @endif
                @if (session()->has('error_msg'))
                TVHung.showError('{{ session('error_msg') }}');
                @endif
                @if (isset($error_msg))
                TVHung.showError('{{ $error_msg }}');
                @endif
                @if (isset($errors))
                @foreach ($errors->all() as $error)
                TVHung.showError('{{ $error }}');
                @endforeach
                @endif
            });
        </script>
    @endif
@endpush
