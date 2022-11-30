'use strict';
$(document).ready(function () {
    $(document).on('click', '.btn-trigger-cleanup', event => {
        event.preventDefault();
        $('#cleanup-modal').modal('show');
    });

    $(document).on('click', '#cleanup-submit-action', event => {
        event.preventDefault();
        event.stopPropagation();
        const _self = $(event.currentTarget);

        _self.addClass('button-loading');

        const $form = $('#form-cleanup-database');

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            success: res => {
                if (!res.error) {
                    TVHung.showSuccess(res.message);
                } else {
                    TVHung.showError(res.message);
                }

                _self.removeClass('button-loading');
                $('#cleanup-modal').modal('hide');
            },
            error: res => {
                _self.removeClass('button-loading');
                TVHung.handleError(res);
            }
        });

    });
})
