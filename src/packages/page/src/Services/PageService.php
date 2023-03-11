<?php

namespace TVHung\Page\Services;

use BaseHelper;
use TVHung\Base\Enums\BaseStatusEnum;
use TVHung\Page\Models\Page;
use TVHung\Page\Repositories\Interfaces\PageInterface;
use TVHung\SeoHelper\SeoOpenGraph;
use Eloquent;
use Html;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use SeoHelper;
use Theme;

class PageService
{
    /**
     * @param Eloquent|Builder $slug
     * @return array|Eloquent
     */
    public function handleFrontRoutes($slug)
    {
        if (!$slug instanceof Eloquent) {
            return $slug;
        }

        $condition = [
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        if ($slug->reference_type !== Page::class) {
            return $slug;
        }

        $page = app(PageInterface::class)->getFirstBy($condition, ['*'], ['slugable']);

        if (empty($page)) {
            abort(404);
        }

        $meta = new SeoOpenGraph();
        if ($page->image) {
            $meta->setImage(RvMedia::getImageUrl($page->image));
        }

        if (!BaseHelper::isHomepage($page->id)) {
            SeoHelper::setTitle($page->name)
                ->setDescription($page->description);

            $meta->setTitle($page->name);
            $meta->setDescription($page->description);
        } else {
            $siteTitle = theme_option('seo_title') ? theme_option('seo_title') : theme_option('site_title');
            $seoDescription = theme_option('seo_description');

            SeoHelper::setTitle($siteTitle)
                ->setDescription($seoDescription);

            $meta->setTitle($siteTitle);
            $meta->setDescription($seoDescription);
        }

        $meta->setUrl($page->url);
        $meta->setType('article');

        SeoHelper::setSeoOpenGraph($meta);

        if ($page->template) {
            Theme::uses(Theme::getThemeName())
                ->layout($page->template);
        }

        if (function_exists('admin_bar')) {
            admin_bar()
                ->registerLink(trans('packages/page::pages.edit_this_page'), route('pages.edit', $page->id), 'pages.edit');
        }

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PAGE_MODULE_SCREEN_NAME, $page);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($page->name, $page->url);

        // Theme::asset()->add('ckeditor-content-styles', 'vendor/core/core/base/libraries/ckeditor/content-styles.css');

        // $page->content = Html::tag('div', (string)$page->content, ['class' => 'ck-content'])->toHtml();

        return [
            'view' => 'page',
            'default_view' => 'packages/page::themes.page',
            'data' => compact('page'),
            'slug' => $page->slug,
        ];
    }
}
