class BDashboard {
    static loadWidget(el, url, data, callback) {
        TVHung.blockUI({
            target: el,
            iconOnly: true,
            overlayColor: 'none'
        });

        if (typeof data === 'undefined') {
            data = {};
        }

        $.ajax({
            type: 'GET',
            cache: false,
            url: url,
            data: data,
            success: res =>  {
                TVHung.unblockUI(el);
                if (!res.error) {
                    el.html(res.data);
                    if (typeof (callback) !== 'undefined') {
                        callback();
                    }
                    if (el.find('.scroller').length !== 0) {
                        TVHung.callScroll(el.find('.scroller'));
                    }
                    $('.equal-height').equalHeights();

                    BDashboard.initSortable();
                } else {
                    el.html('<div class="dashboard_widget_msg col-12"><p>' + res.message + '</p>');
                }
            },
            error: res =>  {
                TVHung.unblockUI(el);
                TVHung.handleError(res);
            }
        });
    };

    static initSortable() {
        if ($('#list_widgets').length > 0) {
            let el = document.getElementById('list_widgets');
            Sortable.create(el, {
                group: 'widgets', // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
                sort: true, // sorting inside list
                delay: 0, // time in milliseconds to define when the sorting should start
                disabled: false, // Disables the sortable if set to true.
                store: null, // @see Store
                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                handle: '.portlet-title',
                ghostClass: 'sortable-ghost', // Class name for the drop placeholder
                chosenClass: 'sortable-chosen', // Class name for the chosen item
                dataIdAttr: 'data-id',

                forceFallback: false, // ignore the HTML5 DnD behaviour and force the fallback to kick in
                fallbackClass: 'sortable-fallback', // Class name for the cloned DOM Element when using forceFallback
                fallbackOnBody: false,  // Appends the cloned DOM Element into the Document's Body

                scroll: true, // or HTMLElement
                scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
                scrollSpeed: 10, // px

                // dragging ended
                onEnd: () => {
                    let items = [];
                    $.each($('.widget_item'), (index, widget) => {
                        items.push($(widget).prop('id'));
                    });
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: route('dashboard.update_widget_order'),
                        data: {
                            items: items
                        },
                        success: res =>  {
                            if (!res.error) {
                                TVHung.showSuccess(res.message);
                            } else {
                                TVHung.showError(res.message);
                            }
                        },
                        error: data =>  {
                            TVHung.handleError(data);
                        }
                    });
                }
            });
        }
    };

    init() {
        let list_widgets = $('#list_widgets');

        $(document).on('click', '.portlet > .portlet-title .tools > a.remove', event =>  {
            event.preventDefault();
            $('#hide-widget-confirm-bttn').data('id', $(event.currentTarget).closest('.widget_item').prop('id'));
            $('#hide_widget_modal').modal('show');
        });

        list_widgets.on('click', '.page_next, .page_previous', event =>  {
            event.preventDefault();
            BDashboard.loadWidget($(event.currentTarget).closest('.portlet').find('.portlet-body'), $(event.currentTarget).prop('href'));
        });

        list_widgets.on('change', '.number_record .numb', event =>  {
            event.preventDefault();
            let paginate = $('.number_record .numb').val();
            if (!isNaN(paginate)) {
                BDashboard.loadWidget($(event.currentTarget).closest('.portlet').find('.portlet-body'), $(event.currentTarget).closest('.widget_item').attr('data-url'), {paginate: paginate});
            } else {
                TVHung.showError('Please input a number!')
            }

        });

        list_widgets.on('click', '.btn_change_paginate', event =>  {
            event.preventDefault();
            let numb = $('.number_record .numb');
            let paginate = parseInt(numb.val());
            if ($(event.currentTarget).hasClass('btn_up')) {
                paginate += 5;
            }
            if ($(event.currentTarget).hasClass('btn_down')) {
                if (paginate - 5 > 0) {
                    paginate -= 5;
                } else {
                    paginate = 0;
                }
            }
            numb.val(paginate);
            BDashboard.loadWidget($(event.currentTarget).closest('.portlet').find('.portlet-body'), $(event.currentTarget).closest('.widget_item').attr('data-url'), {paginate: paginate});
        });

        $('#hide-widget-confirm-bttn').on('click', event =>  {
            event.preventDefault();
            let name = $(event.currentTarget).data('id');
            $.ajax({
                type: 'GET',
                cache: false,
                url: route('dashboard.hide_widget', {name: name}),
                success: res =>  {
                    if (!res.error) {
                        $('#' + name).fadeOut();
                        TVHung.showSuccess(res.message);
                    } else {
                        TVHung.showError(res.message);
                    }
                    $('#hide_widget_modal').modal('hide');
                    let portlet = $(event.currentTarget).closest('.portlet');

                    if ($(document).hasClass('page-portlet-fullscreen')) {
                        $(document).removeClass('page-portlet-fullscreen');
                    }

                    portlet.find('.portlet-title .fullscreen').tooltip('destroy');
                    portlet.find('.portlet-title .tools > .reload').tooltip('destroy');
                    portlet.find('.portlet-title .tools > .remove').tooltip('destroy');
                    portlet.find('.portlet-title .tools > .config').tooltip('destroy');
                    portlet.find('.portlet-title .tools > .collapse, .portlet > .portlet-title .tools > .expand').tooltip('destroy');

                    portlet.remove();
                },
                error: data =>  {
                    TVHung.handleError(data);
                }
            });
        });

        $(document).on('click', '.portlet:not(.widget-load-has-callback) > .portlet-title .tools > a.reload', event =>  {
            event.preventDefault();
            BDashboard.loadWidget($(event.currentTarget).closest('.portlet').find('.portlet-body'), $(event.currentTarget).closest('.widget_item').attr('data-url'));
        });


        $(document).on('click', '.portlet > .portlet-title .tools > .collapse, .portlet .portlet-title .tools > .expand', event =>  {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let state = $.trim(_self.data('state'));
            if (state === 'expand') {
                _self.closest('.portlet').find('.portlet-body').removeClass('collapse').addClass('expand');
                BDashboard.loadWidget(_self.closest('.portlet').find('.portlet-body'), _self.closest('.widget_item').attr('data-url'));
            } else {
                _self.closest('.portlet').find('.portlet-body').removeClass('expand').addClass('collapse');
            }

            $.ajax({
                type: 'POST',
                cache: false,
                url: route('dashboard.edit_widget_setting_item'),
                data: {
                    name: _self.closest('.widget_item').prop('id'),
                    setting_name: 'state',
                    setting_value: state
                },
                success: () => {
                    if (state === 'collapse') {
                        _self.data('state', 'expand');
                    } else {
                        _self.data('state', 'collapse');
                    }
                },
                error: data =>  {
                    TVHung.handleError(data);
                }
            });
        });

        let manage_widget_modal = $('#manage_widget_modal');
        $(document).on('click', '.manage-widget', event =>  {
            event.preventDefault();
            manage_widget_modal.modal('show');
        });

        manage_widget_modal.on('change', '.swc_wrap input', event =>  {
            $(event.currentTarget).closest('section').find('i').toggleClass('widget_none_color');
        });
    }
}

$(document).ready(() => {
    new BDashboard().init();
    window.BDashboard = BDashboard;
});
