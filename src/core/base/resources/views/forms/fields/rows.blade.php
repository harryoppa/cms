@php
    $col = ceil(12 / count($fields));
@endphp
<div class="{{ $className }}">
    @foreach ($fields as $field)
        <div class="col-md-{{ $col }}">
            {!! $field->render() !!}
        </div>
    @endforeach
</div>