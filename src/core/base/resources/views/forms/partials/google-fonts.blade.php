<div class="ui-select-wrapper">
    @php
        Arr::set($selectAttributes, 'class', Arr::get($selectAttributes, 'class') . ' ui-select');
    @endphp
    <select name="{{ $name }}" class='form-control select2_google_fonts_picker'>
        @php
            $field['options'] = config('core.base.general.google_fonts', []);

            $customGoogleFonts = config('core.base.general.custom_google_fonts');

            if ($customGoogleFonts) {
                $field['options'] = array_merge($field['options'], explode(',', $customGoogleFonts));
            }
        @endphp
        @foreach (['' => __('-- Select --')] + array_combine($field['options'], $field['options']) as $key => $value)
            <option value='{{ $key }}' @if ($key == $selected) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
    <svg class="svg-next-icon svg-next-icon-size-16">
        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron"></use>
    </svg>
</div>

@once
    @push('footer')
        <link href="{{ BaseHelper::getGoogleFontsURL() }}/css?family={{ implode('|', array_map('urlencode', array_filter($field['options']))) }}&display=swap" rel="stylesheet" type="text/css">
    @endpush
@endonce

