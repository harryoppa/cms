<?php

namespace TVHung\Setting\Models;

use TVHung\Base\Models\BaseModel;

class Setting extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];
}
