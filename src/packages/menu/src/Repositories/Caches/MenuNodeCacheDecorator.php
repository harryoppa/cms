<?php

namespace TVHung\Menu\Repositories\Caches;

use TVHung\Menu\Repositories\Interfaces\MenuNodeInterface;
use TVHung\Support\Repositories\Caches\CacheAbstractDecorator;

class MenuNodeCacheDecorator extends CacheAbstractDecorator implements MenuNodeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByMenuId($menuId, $parentId, $select = ['*'], array $with = ['child'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
