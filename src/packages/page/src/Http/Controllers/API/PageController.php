<?php

namespace TVHung\Page\Http\Controllers\API;

use TVHung\Base\Enums\BaseStatusEnum;
use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Page\Http\Resources\PageResource;
use TVHung\Page\Models\Page;
use TVHung\Page\Repositories\Interfaces\PageInterface;
use SlugHelper;

class PageController extends BaseController
{
    public function __construct(
        protected BaseHttpResponse $response,
        protected PageInterface $pageRepository,
    ){}

    /**
     * find page by slug
     *
     * @urlParam slug string required The slug of the page. Example: about-us
     *
     * @group Page
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource|BaseHttpResponse
     */
    public function findPageBySlug(string $slug)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Page::class));

        if (!$slug) {
            return $this->response->setError()->setCode(404)->setMessage(__('Page not found!'));
        }

        $page = $this->pageRepository->getFirstBy([
            'id' => $slug->reference_id,
            'status' => BaseStatusEnum::PUBLISHED
        ]);

        if (!$page) {
            return $this->response->setError()->setCode(404)->setMessage(__('Page not found!'));
        }

        return $this->response
            ->setData(new PageResource($page))
            ->toApiResponse();
    }

}
