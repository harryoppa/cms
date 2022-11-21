<?php

namespace TVHung\Slug\Models;

use TVHung\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slug extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slugs';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'reference_type',
        'reference_id',
        'prefix',
    ];

    public function reference(): BelongsTo
    {
        return $this->morphTo();
    }
}
