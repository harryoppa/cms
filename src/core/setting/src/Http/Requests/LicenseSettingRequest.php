<?php

namespace TVHung\Setting\Http\Requests;

use TVHung\Support\Http\Requests\Request;

class LicenseSettingRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'purchase_code'           => 'required',
            'buyer'                   => 'required|alpha_dash',
            'license_rules_agreement' => 'accepted:1',
        ];
    }
}
