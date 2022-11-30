<?php

namespace TVHung\Theme\Http\Requests;

use TVHung\Support\Http\Requests\Request;

class CustomHtmlRequest extends Request
{
    public function rules(): array
    {
        return [
            'header_html' => 'max:2500',
            'body_html' => 'max:2500',
            'footer_html' => 'max:2500',
        ];
    }
}
