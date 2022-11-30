<?php

namespace TVHung\Theme\Http\Controllers\API;

use Illuminate\Http\Request;
use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use SlugHelper;
use Arr;
use ThemeOption;
use RvMedia;
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

    /**
     * get theme options all or by keys
     *
     * @queryParam keys string optional keys to get options separated by comma. Example: site_title,site_description,logo
     *
     * @group Theme
     *
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function getThemeOption(Request $request): BaseHttpResponse
    {
        $keys = $request->input('keys', null);

        if (!$keys) {
            $fields = ThemeOption::getFields();

            $options = [];

            foreach ($fields as $group => $items) {
                $options[$group] = [];

                foreach (array_keys($items) as $item) {
                    // match regex $item with logo|image|icon|favicon|banner|thumbnail|avatar|photo|cover|background|bg
                    if (preg_match('/(logo|image|icon|favicon|banner|thumbnail|avatar|photo|cover|background|bg)/i', $item)) {
                        $options[$group][$item] = RvMedia::getImageUrl(ThemeOption::getOption($item));
                    } else {
                        $options[$group][$item] = ThemeOption::getOption($item);
                    }
                }
            }
            return $this->response->setData($options);
        }

        $keys = explode(',', $keys);

        $options = [];
        foreach ($keys as $key) {
            $options[$key] = theme_option($key);
        }

        return $this->response->setData($options);
    }
}
