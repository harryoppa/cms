<?php

namespace TVHung\Slug\Http\Requests;

use TVHung\Support\Http\Requests\Request;

class SlugRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => 'required',
            'slug_id' => 'required',
        ];
    }
}
