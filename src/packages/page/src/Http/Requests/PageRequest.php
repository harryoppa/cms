<?php

namespace TVHung\Page\Http\Requests;

use TVHung\Base\Enums\BaseStatusEnum;
use TVHung\Page\Supports\Template;
use TVHung\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'content' => 'required',
            'template' => Rule::in(array_keys(Template::getPageTemplates())),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
