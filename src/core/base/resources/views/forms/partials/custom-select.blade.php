<div class="ui-select-wrapper form-group">
    @php
        Arr::set($selectAttributes, 'class', Arr::get($selectAttributes, 'class') . ' ui-select');
    @endphp
    {!! Form::select($name, $list, $selected, $selectAttributes, $optionsAttributes, $optgroupsAttributes) !!}
    @if (!isset($selectAttributes['multiple']))
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="svg-next-icon svg-next-icon-size-16 ml-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
    </svg>
    @else
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="svg-next-icon svg-next-icon-size-16 ml-2" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
    </svg>
    @endif
</div>
