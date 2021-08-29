<?php

namespace TVHung\Page\Http\Controllers;

use TVHung\Base\Events\BeforeEditContentEvent;
use TVHung\Base\Events\CreatedContentEvent;
use TVHung\Base\Events\DeletedContentEvent;
use TVHung\Base\Events\UpdatedContentEvent;
use TVHung\Base\Forms\FormBuilder;
use TVHung\Base\Http\Controllers\BaseController;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Base\Traits\HasDeleteManyItemsTrait;
use TVHung\Page\Forms\PageForm;
use TVHung\Page\Http\Requests\PageRequest;
use TVHung\Page\Repositories\Interfaces\PageInterface;
use TVHung\Page\Tables\PageTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class PageController extends BaseController
{

    use HasDeleteManyItemsTrait;

    /**
     * @var PageInterface
     */
    protected $pageRepository;

    /**
     * PageController constructor.
     * @param PageInterface $pageRepository
     */
    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param PageTable $dataTable
     * @return JsonResponse|View
     *
     * @throws Throwable
     */
    public function index(PageTable $dataTable)
    {
        page_title()->setTitle(trans('packages/page::pages.menu_name'));

        return $dataTable->renderTable();
    }

    /**
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/page::pages.create'));

        return $formBuilder->create(PageForm::class)->renderForm();
    }

    /**
     * @param PageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => Auth::id(),
        ]));

        event(new CreatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

        return $response->setPreviousUrl(route('pages.index'))
            ->setNextUrl(route('pages.edit', $page->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $page = $this->pageRepository->findOrFail($id);

        page_title()->setTitle(trans('packages/page::pages.edit') . ' "' . $page->name . '"');

        event(new BeforeEditContentEvent($request, $page));

        return $formBuilder->create(PageForm::class, ['model' => $page])->renderForm();
    }

    /**
     * @param $id
     * @param PageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->findOrFail($id);
        $page->fill($request->input());

        $page = $this->pageRepository->createOrUpdate($page);

        event(new UpdatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

        return $response
            ->setPreviousUrl(route('pages.index'))
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
            $page = $this->pageRepository->findOrFail($id);
            $this->pageRepository->delete($page);

            event(new DeletedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

            return $response->setMessage(trans('packages/page::pages.deleted'));
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
        return $this->executeDeleteItems($request, $response, $this->pageRepository, PAGE_MODULE_SCREEN_NAME);
    }
}
