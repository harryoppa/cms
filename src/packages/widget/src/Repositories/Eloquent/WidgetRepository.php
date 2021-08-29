<?php

namespace TVHung\Widget\Repositories\Eloquent;

use TVHung\Support\Repositories\Eloquent\RepositoriesAbstract;
use TVHung\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetRepository extends RepositoriesAbstract implements WidgetInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByTheme($theme)
    {
        $data = $this->model->where('theme', $theme)->get();
        $this->resetModel();

        return $data;
    }
}
