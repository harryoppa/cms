<?php

namespace TVHung\Menu\Http\Requests;

use TVHung\Base\Enums\BaseStatusEnum;
use TVHung\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class MenuRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:120',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
