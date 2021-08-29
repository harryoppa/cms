<?php

namespace TVHung\Dashboard\Repositories\Caches;

use TVHung\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use TVHung\Support\Repositories\Caches\CacheAbstractDecorator;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
