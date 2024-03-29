<script>
    jQuery(document).ready(function () {
        'use strict';

        function findValueByName(name, list, defaultValue) {
            for (var i = 0; i < list.length; i++) {
                if (list[i].name === name) {
                    return list[i].value;
                }
            }

            return defaultValue;
        }
        
        $("{{ $validator['selector'] }}").each(function () {
            let $form = $(this);
            $form.validate({
                errorElement: 'span',
                errorClass: 'invalid-feedback',

                submitHandler: function(form) {
                    if ($form.valid())
                    {
                        if ($form.hasClass('no-submit-handler')) {
                            return true;
                        }
                        var s = findValueByName('submit', $form.serializeArray(), 'apply');

                        $form.off('submit');
                        setTimeout(() => {
                            $form.find('[type="submit"][value="'+s+'"]').click()
                        }, 5);
                    }
                    return false; // prevent normal form posting
                },

                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length ||
                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-valid').addClass('is-invalid');
                },

                @if (isset($validator['ignore']) && is_string($validator['ignore']))
                    ignore: "{{ $validator['ignore'] }}",
                @endif


                unhighlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },

                success: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },

                focusInvalid: false,
                @if (config('core.js-validation.js-validation.focus_on_error'))
                invalidHandler: function (form, validator) {

                    if (!validator.numberOfInvalids())
                        return;

                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top
                    }, {{  config('core.js-validation.js-validation.duration_animate') }});
                    $(validator.errorList[0].element).focus();

                },
                @endif

                rules: {!! json_encode($validator['rules']) !!}
            });
        });
    });
</script>
