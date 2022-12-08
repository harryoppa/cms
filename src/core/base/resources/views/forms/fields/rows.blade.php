@php
    $col = $col ?: (!empty($fields) ? ceil(12 / count($fields)) : 2);
@endphp
<div class="{{ $className }}">
    @foreach ($fields as $field)
        <div class="col-md-{{ $col }}">
            {!! $field->render() !!}
        </div>
    @endforeach
</div>