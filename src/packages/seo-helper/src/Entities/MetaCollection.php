<?php

namespace TVHung\SeoHelper\Entities;

use TVHung\SeoHelper\Bases\MetaCollection as BaseMetaCollection;
use TVHung\SeoHelper\Exceptions\InvalidArgumentException;
use TVHung\SeoHelper\Helpers\Meta;

class MetaCollection extends BaseMetaCollection
{
    /**
     * Ignored tags, they have dedicated class.
     *
     * @var array
     */
    protected $ignored = [
        'description',
    ];

    /**
     * Add a meta to collection.
     *
     * @param $item
     * @return MetaCollection
     * @throws InvalidArgumentException
     */
    public function add($item)
    {
        $meta = Meta::make($item['name'], $item['content']);

        if ($meta->isValid() && !$this->isIgnored($item['name'])) {
            $this->put($meta->key(), $meta);
        }

        return $this;
    }
}
