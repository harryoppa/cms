<?php

namespace TVHung\ACL\Repositories\Caches;

use TVHung\ACL\Repositories\Interfaces\RoleInterface;
use TVHung\Support\Repositories\Caches\CacheAbstractDecorator;

class RoleCacheDecorator extends CacheAbstractDecorator implements RoleInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
