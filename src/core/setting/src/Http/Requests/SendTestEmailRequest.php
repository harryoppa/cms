<?php

namespace TVHung\Setting\Http\Requests;

use TVHung\Support\Http\Requests\Request;

class SendTestEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
