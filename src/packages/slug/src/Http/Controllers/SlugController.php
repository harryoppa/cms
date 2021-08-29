<?php

namespace TVHung\Slug\Http\Controllers;

use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Setting\Supports\SettingStore;
use TVHung\Slug\Http\Requests\SlugRequest;
use TVHung\Slug\Repositories\Interfaces\SlugInterface;
use TVHung\Slug\Services\SlugService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Menu;

class SlugController extends BaseController
{
    /**
     * @var SlugInterface
     */
    protected $slugRepository;

    /**
     * @var SlugService
     */
    protected $slugService;

    /**
     * SlugController constructor.
     * @param SlugInterface $slugRepository
     * @param SlugService $slugService
     */
    public function __construct(SlugInterface $slugRepository, SlugService $slugService)
    {
        $this->slugRepository = $slugRepository;
        $this->slugService = $slugService;
    }

    /**
     * @param SlugRequest $request
     * @return int|string
     */
    public function store(SlugRequest $request)
    {
        return $this->slugService->create(
            $request->input('name'),
            $request->input('slug_id'),
            $request->input('model')
        );
    }

    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('packages/slug::slug.settings.title'));

        return view('packages/slug::settings');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     */
    public function postSettings(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        foreach ($request->except(['_token']) as $settingKey => $settingValue) {
            if (Str::contains($settingKey, '-model-key')) {
                continue;
            }

            if ($settingStore->get($settingKey) !== (string)$settingValue) {
                $this->slugRepository->update(
                    ['reference_type' => $request->input($settingKey . '-model-key')],
                    ['prefix' => (string)$settingValue]
                );

                Menu::clearCacheMenuItems();
            }

            $settingStore->set($settingKey, (string)$settingValue);
        }

        $settingStore->save();

        return $response
            ->setPreviousUrl(route('slug.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
