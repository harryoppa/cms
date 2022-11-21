<?php

namespace TVHung\Menu\Models;

use TVHung\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuLocation extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'menu_locations';

    /**
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'location',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id')->withDefault();
    }
}
