<?php

namespace TVHung\Menu\Http\Controllers;

use TVHung\Base\Events\BeforeEditContentEvent;
use TVHung\Base\Events\CreatedContentEvent;
use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Base\Events\UpdatedContentEvent;
use TVHung\Base\Forms\FormBuilder;
use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Menu\Forms\MenuForm;
use TVHung\Menu\Http\Requests\MenuNodeRequest;
use TVHung\Menu\Http\Requests\MenuRequest;
use TVHung\Menu\Models\Menu as MenuModel;
use TVHung\Menu\Repositories\Eloquent\MenuRepository;
use TVHung\Menu\Repositories\Interfaces\MenuInterface;
use TVHung\Menu\Repositories\Interfaces\MenuLocationInterface;
use TVHung\Menu\Repositories\Interfaces\MenuNodeInterface;
use TVHung\Menu\Tables\MenuTable;
use TVHung\Support\Services\Cache\Cache;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Menu;
use stdClass;
use Throwable;

class MenuController extends BaseController
{
    /**
     * @var MenuInterface
     */
    protected $menuRepository;

    /**
     * @var MenuNodeInterface
     */
    protected $menuNodeRepository;

    /**
     * @var MenuLocationInterface
     */
    protected $menuLocationRepository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * MenuController constructor.
     * @param MenuInterface $menuRepository
     * @param MenuNodeInterface $menuNodeRepository
     * @param MenuLocationInterface $menuLocationRepository
     * @param CacheManager $cache
     */
    public function __construct(
        MenuInterface $menuRepository,
        MenuNodeInterface $menuNodeRepository,
        MenuLocationInterface $menuLocationRepository,
        CacheManager $cache
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->menuLocationRepository = $menuLocationRepository;
        $this->cache = new Cache($cache, MenuRepository::class);
    }

    /**
     * @param MenuTable $table
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(MenuTable $table)
    {
        page_title()->setTitle(trans('packages/menu::menu.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/menu::menu.create'));

        return $formBuilder->create(MenuForm::class)->renderForm();
    }

    /**
     * @param MenuRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function store(MenuRequest $request, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->getModel();

        $menu->fill($request->input());
        $menu->slug = $this->menuRepository->createSlug($request->input('name'));
        $menu = $this->menuRepository->createOrUpdate($menu);

        $this->cache->flush();

        event(new CreatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setNextUrl(route('menus.edit', $menu->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param MenuModel $menu
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    protected function saveMenuLocations(MenuModel $menu, Request $request): bool
    {
        $locations = $request->input('locations', []);

        $this->menuLocationRepository->deleteBy([
            'menu_id' => $menu->id,
            ['location', 'NOT_IN', $locations],
        ]);

        foreach ($locations as $location) {
            $menuLocation = $this->menuLocationRepository->firstOrCreate([
                'menu_id' => $menu->id,
                'location' => $location,
            ]);

            event(new CreatedContentEvent(MENU_LOCATION_MODULE_SCREEN_NAME, $request, $menuLocation));
        }

        return true;
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        page_title()->setTitle(trans('packages/menu::menu.edit'));

        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass();
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = $this->menuRepository->findOrFail($id);
        }

        event(new BeforeEditContentEvent($request, $menu));

        return $formBuilder->create(MenuForm::class, ['model' => $menu])->renderForm();
    }

    /**
     * @param MenuRequest $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function update(MenuRequest $request, $id, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->firstOrNew(compact('id'));

        $menu->fill($request->input());
        $this->menuRepository->createOrUpdate($menu);
        event(new UpdatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        $deletedNodes = ltrim($request->input('deleted_nodes', ''));
        if ($deletedNodes) {
            $deletedNodes = explode(' ', ltrim($request->input('deleted_nodes', '')));
            $this->menuNodeRepository->deleteBy([
                ['id', 'IN', $deletedNodes],
                ['menu_id', '=', $menu->id],
            ]);
        }

        $menuNodes = Menu::recursiveSaveMenu(json_decode($request->input('menu_nodes'), true), $menu->id, 0);

        $request->merge(['menu_nodes', json_encode($menuNodes)]);

        $this->cache->flush();

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);

            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);
            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param MenuNodeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getNode(MenuNodeRequest $request, BaseHttpResponse $response)
    {
        $data = (array)$request->input('data', []);

        $row = $this->menuNodeRepository->getModel();
        $row->fill($data);
        $row = Menu::getReferenceMenuNode($data, $row);
        $row->save();

        event(new CreatedContentEvent(MENU_NODE_MODULE_SCREEN_NAME, $request, $row));

        $html = view('packages/menu::partials.node', compact('row'))->render();

        return $response
            ->setData(compact('html'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
}
