<?php

namespace TVHung\Setting\Http\Requests;

use Assets;
use TVHung\Support\Http\Requests\Request;
use DateTimeZone;
use Illuminate\Validation\Rule;

class SettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'admin_email' => 'nullable|array',
            'default_admin_theme' => Rule::in(array_keys(Assets::getThemes())),
            'time_zone' => Rule::in(DateTimeZone::listIdentifiers()),
        ];
    }
}
