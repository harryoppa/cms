<?php

namespace TVHung\Theme\Forms;

use TVHung\Base\Forms\FormAbstract;
use TVHung\Base\Models\BaseModel;
use TVHung\Theme\Http\Requests\CustomJsRequest;

class CustomJSForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new BaseModel)
            ->setUrl(route('theme.custom-js.post'))
            ->setValidatorClass(CustomJsRequest::class)
            ->add('header_js', 'textarea', [
                'label'      => trans('packages/theme::theme.custom_header_js'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => setting('custom_header_js'),
            ])
            ->add('body_js', 'textarea', [
                'label'      => trans('packages/theme::theme.custom_body_js'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => setting('custom_body_js'),
            ])
            ->add('footer_js', 'textarea', [
                'label'      => trans('packages/theme::theme.custom_footer_js'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => setting('custom_footer_js'),
            ]);
    }
}
