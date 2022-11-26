<?php

namespace TVHung\Page\Repositories\Caches;

use TVHung\Page\Repositories\Interfaces\PageInterface;
use TVHung\Support\Repositories\Caches\CacheAbstractDecorator;

class PageCacheDecorator extends CacheAbstractDecorator implements PageInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function whereIn(array $array, array $select = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getSearch($query, int $limit = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAllPages(bool $active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
