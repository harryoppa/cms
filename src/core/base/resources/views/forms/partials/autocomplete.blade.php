@include('core/base::forms.partials.custom-select')

@push('footer')
    <script>
        "use strict";
        $('#' + '{{ Arr::get($selectAttributes, 'id') }}').select2({
            minimumInputLength: 2,

            templateResult: function(data) {
                if (!data.image) {
                    return $('<span>'+ data.text +'</span>');
                }

                return $('<span class="d-flex align-items-center"><img src="'+data.image+'" style="width:30px;height:30px;margin-right: 10px;object-fit:cover;" class="avatar" />'+ data.text +'</span>')
            },

            // templateSelection: function(data) {
            //     console.log('data', data)
            //     if (!data.image) {
            //         return $('<span>'+ data.text +'</span>');
            //     }

            //     return $('<span class="d-flex align-items-center"><img src="'+data.image+'" style="width:30px;height:30px;margin-right: 10px;object-fit:cover;" class="avatar" />'+ data.text +'</span>')
            // },

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
                        return Object.assign({
                            id: item['id'],
                            text: item['name'],
                            image: item.imageUrl || item.avatar_url,
                        }, item);
                    });
                    return {
                        results: results
                    };
                }
            }
        });
    </script>
@endpush
