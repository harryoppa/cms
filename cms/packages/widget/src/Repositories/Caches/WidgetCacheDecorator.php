<?php

namespace TVHung\Widget\Repositories\Caches;

use TVHung\Support\Repositories\Caches\CacheAbstractDecorator;
use TVHung\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetCacheDecorator extends CacheAbstractDecorator implements WidgetInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByTheme($theme)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
