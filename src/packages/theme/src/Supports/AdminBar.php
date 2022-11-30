<?php

namespace TVHung\Theme\Supports;

use Illuminate\Support\Facades\Auth;

class AdminBar
{
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var bool
     */
    protected $isDisplay = true;

    /**
     * @var array
     */
    protected $noGroupLinks = [];

    /**
     * AdminBar constructor.
     */
    public function __construct()
    {
        $this->groups = [
            'appearance' => [
                'link' => 'javascript:;',
                'title' => trans('packages/theme::theme.appearance'),
                'items' => [],
            ],
            'add-new' => [
                'link' => 'javascript:;',
                'title' => trans('packages/theme::theme.add_new'),
                'items' => [],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function isDisplay(): bool
    {
        return $this->isDisplay;
    }

    /**
     * @param bool $isDisplay
     * @return $this
     */
    public function setIsDisplay(bool $isDisplay = true): self
    {
        $this->isDisplay = $isDisplay;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return array
     */
    public function getLinksNoGroup(): array
    {
        return $this->noGroupLinks;
    }

    /**
     * @param string $slug
     * @param string $title
     * @param string $link
     * @return $this
     */
    public function registerGroup(string $slug, string $title, string $link = 'javascript:;'): self
    {
        if (isset($this->groups[$slug])) {
            $this->groups[$slug]['items'][$title] = $link;

            return $this;
        }

        $this->groups[$slug] = [
            'title' => $title,
            'link' => $link,
            'items' => [],
        ];

        return $this;
    }

    /**
     * @param string $title
     * @param string $url
     * @param null $group
     * @param string|null $permission
     * @return $this
     */
    public function registerLink(string $title, string $url, $group = null, string $permission = null): self
    {
        if ($group === null || !isset($this->groups[$group])) {
            $this->noGroupLinks[] = [
                'link' => $url,
                'title' => $title,
                'permission' => $permission,
            ];
        } else {
            $this->groups[$group]['items'][$title] = [
                'link' => $url,
                'title' => $title,
                'permission' => $permission,
            ];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        if (!Auth::check()) {
            return '';
        }

        $this->registerLink(trans('core/base::layouts.dashboard'), route('dashboard.index'), 'appearance', 'dashboard.index');
        $this->registerLink(trans('core/acl::users.users'), route('users.create'), 'add-new', 'users.create');
        $this->registerLink(trans('core/setting::setting.title'), route('settings.options'), 'appearance', 'settings.options');

        return view('packages/theme::admin-bar')->render();
    }
}
