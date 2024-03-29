<?php

namespace TVHung\Theme\Http\Controllers;

use BaseHelper;
use TVHung\Page\Models\Page;
use TVHung\Page\Services\PageService;
use TVHung\Theme\Events\RenderingHomePageEvent;
use TVHung\Theme\Events\RenderingSingleEvent;
use TVHung\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Response;
use SeoHelper;
use SiteMapManager;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @param string|null $key
     * @return RedirectResponse|Response
     */
    public function getView(?string $key = null)
    {
        if (empty($key)) {
            return $this->getIndex();
        }

        $slug = SlugHelper::getSlug($key, '');

        if (!$slug) {
            abort(404);
        }

        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            if ($slug->reference_type == Page::class && BaseHelper::isHomepage($slug->reference_id)) {
                return redirect()->route('public.index');
            }
        }

        $result = apply_filters(BASE_FILTER_PUBLIC_SINGLE_DATA, $slug);

        if (isset($result['slug']) && $result['slug'] !== $key) {
            return redirect()->route('public.single', $result['slug']);
        }

        event(new RenderingSingleEvent($slug));

        if (!empty($result) && is_array($result)) {
            return Theme::scope($result['view'], $result['data'], Arr::get($result, 'default_view'))->render();
        }

        abort(404);
    }

    /**
     * @return \Illuminate\Http\Response|Response
     */
    public function getIndex()
    {
        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            $homepageId = BaseHelper::getHomepageId();
            if ($homepageId) {
                $slug = SlugHelper::getSlug(null, SlugHelper::getPrefix(Page::class), Page::class, $homepageId);

                if ($slug) {
                    $data = (new PageService())->handleFrontRoutes($slug);

                    event(new RenderingSingleEvent($slug));

                    return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
                }
            }
        }

        SeoHelper::setTitle(theme_option('site_title'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'));

        event(RenderingHomePageEvent::class);

        return Theme::scope('index')->render();
    }

    /**
     * @return Response|string
     */
    public function getSiteMap()
    {
        event(RenderingSiteMapEvent::class);

        // show your site map (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return SiteMapManager::render();
    }
}
