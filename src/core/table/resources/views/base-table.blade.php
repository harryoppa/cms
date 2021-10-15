<div class="table-wrapper">
    @if ($table->isHasFilter())
        <div class="table-configuration-wrap" @if (request()->has('filter_table_id')) style="display: block;" @endif>
            <span class="configuration-close-btn btn-show-table-options"><i class="fa fa-times"></i></span>
            {!! $table->renderFilter() !!}
        </div>
    @endif
    <div class="portlet light bordered portlet-no-padding">
        <div class="portlet-title">
            <div class="caption">
                <div class="wrapper-action">
                    @if ($actions)
                        <div class="btn-group">
                            <a class="btn btn-secondary dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ trans('core/table::table.bulk_actions') }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($actions as $action)
                                    <li>
                                        {!! $action !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($table->isHasFilter())
                        <button class="btn btn-primary btn-show-table-options">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
                              </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive @if ($actions) table-has-actions @endif @if ($table->isHasFilter()) table-has-filter @endif">
                @section('main-table')
                    {!! $dataTable->table(compact('id', 'class'), false) !!}
                @show
            </div>
        </div>
    </div>
</div>
@include('core/table::modal')

@push('footer')
    {!! $dataTable->scripts() !!}
@endpush
