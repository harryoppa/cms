@include('core/base::forms.partials.custom-select')

@once
    @push('footer')
        <script>
            "use strict";
            $('#' + '{{ Arr::get($selectAttributes, 'id') }}').select2({
                minimumInputLength: 2,

                templateResult: function(data) {
                    console.log('data', data)
                    if (!data.image) {
                        return $('<span>'+ data.text +'</span>');
                    }

                    return $('<span class="d-flex align-items-center"><img src="'+data.image+'" style="width:30px;height:30px;margin-right: 10px;object-fit:cover;" class="avatar" />'+ data.text +'</span>')
                },

                ajax: {
                    url: '{{ Arr::get($selectAttributes, 'data-url') }}',
                    quietMillis: 500,
                    data: function (params) {
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function (data) {
                        let results = data.data.map((item) => {
                            return {
                                id: item['id'],
                                text: item['name'],
                                image: item.image || item.avatar
                            };
                        });
                        return {
                            results: results
                        };
                    }
                }
            });
        </script>
    @endpush
@endonce
