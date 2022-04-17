<?php

namespace TVHung\Support\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Concerns\InteractsWithInput;

/**
 * @mixin InteractsWithInput
 */
abstract class Request extends FormRequest
{
    protected $inputMasks = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (!empty($this->inputMasks)) {
            $this->merge(array_reduce($this->inputMasks, function($obj, $name) {
                $obj[$name] = str_replace(',', '', $this->input($name) ?? 0);
    
                return $obj;
            }, []));
        }
    }
}
