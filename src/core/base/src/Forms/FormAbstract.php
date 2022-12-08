<?php

namespace TVHung\Base\Forms;

use Assets;
use TVHung\Base\Forms\Fields\AutocompleteField;
use TVHung\Base\Forms\Fields\ColorField;
use TVHung\Base\Forms\Fields\CustomRadioField;
use TVHung\Base\Forms\Fields\CustomSelectField;
use TVHung\Base\Forms\Fields\DateField;
use TVHung\Base\Forms\Fields\EditorField;
use TVHung\Base\Forms\Fields\HtmlField;
use TVHung\Base\Forms\Fields\MediaFileField;
use TVHung\Base\Forms\Fields\MediaImageField;
use TVHung\Base\Forms\Fields\MediaImagesField;
use TVHung\Base\Forms\Fields\OnOffField;
use TVHung\Base\Forms\Fields\RepeaterField;
use TVHung\Base\Forms\Fields\TimeField;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsValidator;
use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;

abstract class FormAbstract extends Form
{
    protected array $options = [];

    protected string $title = '';

    protected string $validatorClass = '';

    protected array $metaBoxes = [];

    protected string $actionButtons = '';

    protected string $breakFieldPoint = '';

    protected bool $useInlineJs = false;

    protected string $wrapperClass = 'form-body';

    /**
     * @var string
     */
    protected string $template = 'core/base::forms.form';

    /**
     * @var array
     */
    protected array $hiddenFields = [];

    public function __construct()
    {
        $this->setMethod('POST');
        $this->setFormOption('template', $this->template);
        $this->setFormOption('id', strtolower(Str::slug(Str::snake(get_class($this)))));
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMetaBoxes(): array
    {
        uasort($this->metaBoxes, function ($before, $after) {
            if (Arr::get($before, 'priority', 0) > Arr::get($after, 'priority', 0)) {
                return 1;
            } elseif (Arr::get($before, 'priority', 0) < Arr::get($after, 'priority', 0)) {
                return -1;
            }

            return 0;
        });

        return $this->metaBoxes;
    }

    public function getMetaBox(string $name): string
    {
        if (!Arr::get($this->metaBoxes, $name)) {
            return '';
        }

        $metaBox = $this->metaBoxes[$name];

        return view('core/base::forms.partials.meta-box', compact('metaBox'))->render();
    }

    public function addMetaBoxes(array|string $boxes): self
    {
        if (!is_array($boxes)) {
            $boxes = [$boxes];
        }

        $this->metaBoxes = array_merge($this->metaBoxes, $boxes);

        return $this;
    }

    /**
     * @return array
     */
    public function getHiddenFields(): array
    {
        return $this->hiddenFields;
    }

    public function removeMetaBox(string $name): self
    {
        Arr::forget($this->metaBoxes, $name);

        return $this;
    }

    public function getActionButtons(): string
    {
        if ($this->actionButtons === '') {
            return view('core/base::forms.partials.form-actions')->render();
        }

        return $this->actionButtons;
    }

    public function setActionButtons(string $actionButtons): self
    {
        $this->actionButtons = $actionButtons;

        return $this;
    }

    public function removeActionButtons(): self
    {
        $this->actionButtons = '';

        return $this;
    }

    public function getBreakFieldPoint(): string
    {
        return $this->breakFieldPoint;
    }

    public function setBreakFieldPoint(string $breakFieldPoint): self
    {
        $this->breakFieldPoint = $breakFieldPoint;

        return $this;
    }

    public function isUseInlineJs(): bool
    {
        return $this->useInlineJs;
    }

    public function setUseInlineJs(bool $useInlineJs): self
    {
        $this->useInlineJs = $useInlineJs;

        return $this;
    }

    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    public function setWrapperClass(string $wrapperClass): self
    {
        $this->wrapperClass = $wrapperClass;

        return $this;
    }

    public function withCustomFields(): self
    {
        $customFields = [
            'customSelect' => CustomSelectField::class,
            'editor'       => EditorField::class,
            'onOff'        => OnOffField::class,
            'customRadio'  => CustomRadioField::class,
            'mediaImage'   => MediaImageField::class,
            'mediaImages'  => MediaImagesField::class,
            'mediaFile'    => MediaFileField::class,
            'customColor'  => ColorField::class,
            'time'         => TimeField::class,
            'date'         => DateField::class,
            'autocomplete' => AutocompleteField::class,
            'html'         => HtmlField::class,
            'repeater'     => RepeaterField::class,
        ];

        foreach ($customFields as $key => $field) {
            $this->addCustomField($key, $field);
        }

        return apply_filters('form_custom_fields', $this, $this->formHelper);
    }

    /**
     * @param string $name
     * @param string $class
     * @return $this
     */
    public function addCustomField($name, $class): self
    {
        if (!$this->formHelper->hasCustomField($name)) {
            parent::addCustomField($name, $class);
        }

        return $this;
    }

    public function hasTabs(): self
    {
        $this->setFormOption('template', 'core/base::forms.form-tabs');

        return $this;
    }

    public function hasMainFields(): int
    {
        if (!$this->breakFieldPoint) {
            return count($this->fields);
        }

        $mainFields = [];

        /**
         * @var FormField $field
         */
        foreach ($this->fields as $field) {
            if ($field->getName() == $this->breakFieldPoint) {
                break;
            }

            $mainFields[] = $field;
        }

        return count($mainFields);
    }

    public function disableFields(): self
    {
        parent::disableFields();

        return $this;
    }

    public function renderForm(array $options = [], $showStart = true, $showFields = true, $showEnd = true): string
    {
        Assets::addScripts(['form-validation', 'are-you-sure']);

        $class = $this->getFormOption('class');
        $this->setFormOption('class', $class . ' dirty-check');

        apply_filters(BASE_FILTER_BEFORE_RENDER_FORM, $this, $this->getModel());

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }

    public function renderValidatorJs(): string
    {
        $element = null;
        if ($this->getFormOption('id')) {
            $element = '#' . $this->getFormOption('id');
        } elseif ($this->getFormOption('class')) {
            $element = '.' . $this->getFormOption('class');
        }

        return JsValidator::formRequest($this->getValidatorClass(), $element);
    }

    public function getValidatorClass(): string
    {
        return $this->validatorClass;
    }

    public function setValidatorClass(string $validatorClass): self
    {
        $this->validatorClass = $validatorClass;

        return $this;
    }

    public function setModel($model): self
    {
        $this->model = $model;

        $this->rebuildForm();

        return $this;
    }

    protected function setupModel($model): self
    {
        if (!$this->model) {
            $this->model = $model;
            $this->setupNamedModel();
        }

        return $this;
    }

    public function setFormOptions(array $formOptions): self
    {
        parent::setFormOptions($formOptions);

        if (isset($formOptions['template'])) {
            $this->template = $formOptions['template'];
        }

        return $this;
    }

    public function add($name, $type = 'text', array $options = [], $modify = false): self
    {
        $options['attr'][] = 'v-pre';

        parent::add($name, $type, $options, $modify);

        return $this;
    }

    /**
     * @param array $values
     * @param false $reset
     * @return $this
     */
    public function withHiddenValues(array $values = [], bool $reset = false): self
    {
        if ($reset) {
            $this->hiddenFields = $values;
        } else {
            $this->hiddenFields = array_merge($this->hiddenFields, $values);
        }

        return $this;
    }

    /**
     * @param \Closure $closure
     * @param string $className
     * @return $this
     */
    public function addRow(\Closure $closure, string $className = 'row', ?int $col = null): self
    {
        $last = count($this->fields);
        $closure($this);

        $slice = array_splice($this->fields, $last);

        $this->add(Str::random(5), 'html', [
            'html'  => view('core/base::forms.fields.rows', [
                'fields' => $slice, 
                'className' => $className, 
                'col' => $col
            ])->render()
        ]);

        return $this;
    }
}
