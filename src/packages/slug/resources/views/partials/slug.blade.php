@if (empty($object))
    <div class="form-group mb-3 @if ($errors->has('slug')) has-error @endif">
        {!! Form::permalink('slug', old('slug'), 0, $prefix, [], true, get_class($object)) !!}
        {!! Form::error('slug', $errors) !!}
    </div>
@else
    <div class="form-group mb-3 @if ($errors->has('slug')) has-error @endif">
        {!! Form::permalink('slug', $object->slug, $object->slug_id, $prefix, SlugHelper::canPreview(get_class($object)) && $object->status != \TVHung\Base\Enums\BaseStatusEnum::PUBLISHED, [], true, get_class($object)) !!}
        {!! Form::error('slug', $errors) !!}
    </div>
@endif
<input type="hidden" name="model" value="{{ get_class($object) }}">
