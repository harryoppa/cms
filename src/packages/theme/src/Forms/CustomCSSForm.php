<?php

namespace TVHung\Theme\Forms;

use BaseHelper;
use TVHung\Base\Forms\FormAbstract;
use TVHung\Base\Models\BaseModel;
use TVHung\Theme\Http\Requests\CustomCssRequest;
use Illuminate\Support\Facades\File;
use Theme;

class CustomCSSForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $css = null;
        $file = Theme::getStyleIntegrationPath();
        if (File::exists($file)) {
            $css = BaseHelper::getFileData($file, false);
        }

        $this
            ->setupModel(new BaseModel())
            ->setUrl(route('theme.custom-css.post'))
            ->setValidatorClass(CustomCssRequest::class)
            ->add('custom_css', 'textarea', [
                'label' => trans('packages/theme::theme.custom_css'),
                'label_attr' => ['class' => 'control-label'],
                'value' => $css,
                'help_block' => [
                    'text' => trans('packages/theme::theme.custom_css_placeholder'),
                ],
            ]);
    }
}
