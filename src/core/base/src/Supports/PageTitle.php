<?php

namespace TVHung\Base\Supports;

class PageTitle
{
    protected string $title;

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(bool $full = true): ?string
    {
        $baseTitle = setting('admin_title', config('core.base.general.base_name'));

        if (empty($this->title)) {
            return $baseTitle;
        }

        if (! $full) {
            return $this->title;
        }

        return $this->title . ' | ' . $baseTitle;
    }
}
