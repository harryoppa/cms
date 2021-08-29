<?php

namespace TVHung\Table\Http\Controllers;

use App\Http\Controllers\Controller;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use TVHung\Table\Http\Requests\BulkChangeRequest;
use TVHung\Table\Http\Requests\FilterRequest;
use TVHung\Table\TableBuilder;
use Exception;
use Form;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class TableController extends Controller
{

    /**
     * @var TableBuilder
     */
    protected $tableBuilder;

    /**
     * TableController constructor.
     * @param TableBuilder $tableBuilder
     */
    public function __construct(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * @param BulkChangeRequest $request
     * @return array|mixed
     * @throws Throwable
     */
    public function getDataForBulkChanges(BulkChangeRequest $request)
    {
        $object = $this->tableBuilder->create($request->input('class'));

        $data = $object->getValueInput(null, null, 'text');
        if (!$request->input('key')) {
            return $data;
        }

        $column = Arr::get($object->getBulkChanges(), $request->input('key'));
        if (empty($column)) {
            return $data;
        }

        $labelClass = 'control-label';
        if (!empty($column) && Str::contains(Arr::get($column, 'validate'), 'required')) {
            $labelClass .= ' required';
        }

        $label = '';
        if (!empty($column['title'])) {
            $label = Form::label($column['title'], null, ['class' => $labelClass])->toHtml();
        }

        if (isset($column['callback']) && method_exists($object, $column['callback'])) {
            $data = $object->getValueInput(
                $column['title'],
                null,
                $column['type'],
                call_user_func([$object, $column['callback']])
            );
        } else {
            $data = $object->getValueInput($column['title'], null, $column['type'], Arr::get($column, 'choices', []));
        }

        $data['html'] = $label . $data['html'];

        return $data;
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws BindingResolutionException
     */
    public function postSaveBulkChange(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/table::table.please_select_record'));
        }

        $inputKey = $request->input('key');
        $inputValue = $request->input('value');

        $object = $this->tableBuilder->create($request->input('class'));
        $columns = $object->getBulkChanges();

        if (!empty($columns[$inputKey]['validate'])) {
            $validator = Validator::make($request->input(), [
                'value' => $columns[$inputKey]['validate'],
            ]);

            if ($validator->fails()) {
                return $response
                    ->setError()
                    ->setMessage($validator->messages()->first());
            }
        }

        try {
            $object->saveBulkChanges($ids, $inputKey, $inputValue);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }

        return $response->setMessage(trans('core/table::table.save_bulk_change_success'));
    }

    /**
     * @param FilterRequest $request
     * @return array|mixed
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function getFilterInput(FilterRequest $request)
    {
        $object = $this->tableBuilder->create($request->input('class'));

        $data = $object->getValueInput(null, null, 'text');
        if (!$request->input('key')) {
            return $data;
        }

        $column = Arr::get($object->getFilters(), $request->input('key'));
        if (empty($column)) {
            return $data;
        }

        $value = $request->input('value');
        $choices = Arr::get($column, 'choices', []);

        if (isset($column['callback']) && method_exists($object, $column['callback'])) {
            $choices = call_user_func_array([$object, $column['callback']], [$value]);
        }

        return $object->getValueInput(
            null,
            $value,
            $column['type'],
            $choices
        );
    }
}
