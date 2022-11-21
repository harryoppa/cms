<?php

namespace TVHung\Menu\Models;

use TVHung\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Request;

class MenuNode extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'menu_nodes';

    /**
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'parent_id',
        'reference_id',
        'reference_type',
        'url',
        'icon_font',
        'title',
        'css_class',
        'target',
        'has_child',
        'position',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuNode::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(MenuNode::class, 'parent_id')->orderBy('position');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo()->with(['slugable']);
    }

    /**
     * @param string $value
     * @return string
     */
    public function getUrlAttribute($value): ?string
    {
        if ($value) {
            return apply_filters(MENU_FILTER_NODE_URL, $value);
        }

        if (!$this->reference_type) {
            return '/';
        }

        if (!$this->reference) {
            return '/';
        }

        return (string)$this->reference->url;
    }

    /**
     * @param string $value
     */
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = $value;
    }

    /**
     * @param string $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = str_replace('&amp;', '&', $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if (!$this->reference_type || !$this->reference) {
            return $value;
        }

        return $this->reference->name;
    }

    /**
     * @return bool
     */
    public function getActiveAttribute()
    {
        return rtrim(url($this->url), '/') == rtrim(Request::url(), '/');
    }

    /**
     * @return bool
     * @deprecated
     */
    public function hasChild()
    {
        return $this->has_child;
    }

    /**
     * @return $this
     * @deprecated
     */
    public function getRelated()
    {
        return $this;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getNameAttribute()
    {
        return $this->title;
    }
}
