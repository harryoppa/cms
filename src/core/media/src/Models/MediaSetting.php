<?php

namespace TVHung\Media\Models;

use TVHung\Base\Models\BaseModel;

class MediaSetting extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'media_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];
}
