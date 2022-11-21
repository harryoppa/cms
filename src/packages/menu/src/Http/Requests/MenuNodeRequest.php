<?php

namespace TVHung\Menu\Http\Requests;

use TVHung\Support\Http\Requests\Request;

class MenuNodeRequest extends Request
{
    public function rules(): array
    {
        return [
            'data.menu_id' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'data.menu_id' => trans('packages/menu::menu.menu_id'),
        ];
    }
}
