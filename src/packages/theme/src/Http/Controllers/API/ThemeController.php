<?php

namespace TVHung\Theme\Http\Controllers\API;

use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use SlugHelper;
use Arr;
use TVHung\Theme\Http\Resources\SlugViewResource;

class ThemeController extends BaseController
{
    public function __construct(
        protected BaseHttpResponse $response,
    ){}

    /**
     * get content from slug without prefix
     *
     * @urlParam slug string required
     *
     * @group Theme
     *
     * @param string $slug
     * @return BaseHttpResponse
     */
    public function getSlugView(string $slug): BaseHttpResponse
    {
        $slug = SlugHelper::getSlug($slug, '');

        if (!$slug) {
            return $this->response->setError()->setCode(404)->setMessage('Not found');
        }

        $result = apply_filters(BASE_FILTER_PUBLIC_SINGLE_DATA, $slug);

        if (!empty($result) && is_array($result)) {
            $data = Arr::first($result['data']);

            return $this->response->setData(SlugViewResource::make($data));
        }

        return $this->response->setError()->setCode(404)->setMessage('Not found');
    }
}
