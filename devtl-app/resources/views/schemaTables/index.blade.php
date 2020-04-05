@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1>{{ $pageTitle }}</h1>
    <button type="button"
        class="btn btn-primary"
        id="create_table_btn"
        data-route_save_table="{{ route('schemaTables.store', ['schema' => $schema->id]) }}"
        data-engine="{{ config('env.first_table_engine') }}"
        data-collation="{{ config('env.first_table_collation') }}">
        @lang('form.create_table')
    </button>
</div>

<div id="table_listing">
    <div class="row">
        @forelse ($schemaTables as $schemaTable)
        <div class="col-md-3 mt-2">
            <button type="button"
                class="btn btn-outline-primary btn-block table_button"
                data-route_get_columns="{{ route('schemaTables.columns', ['schemaTable' => $schemaTable->id]) }}"
                data-route_save_table="{{ route('schemaTables.update', ['schemaTable' => $schemaTable->id]) }}"
                data-route_save_columns="{{ route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]) }}"
                data-name="{{ $schemaTable->name }}"
                data-engine="{{ $schemaTable->engine }}"
                data-collation="{{ $schemaTable->collation }}">
                {{ $schemaTable->name }}
            </button>
        </div>
        @endforeach
    </div>
</div>

<div id="table_column_listing" class="d-none">
    <div class="row">
        <div class="col-md-3 border tables_listing_div">
            <div class="row">
                @forelse ($schemaTables as $schemaTable)
                <div class="col-md-12 mt-2">
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-block table_button"
                        data-route_get_columns="{{ route('schemaTables.columns', ['schemaTable' => $schemaTable->id]) }}"
                        data-route_save_table="{{ route('schemaTables.update', ['schemaTable' => $schemaTable->id]) }}"
                        data-route_save_columns="{{ route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]) }}"
                        data-name="{{ $schemaTable->name }}"
                        data-engine="{{ $schemaTable->engine }}"
                        data-collation="{{ $schemaTable->collation }}">
                        {{ $schemaTable->name }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-9">
            <div class="row table_detail">
                <div class="card rounded-0 h-100">
                    <div class="card-body">
                        <div class="card-title">
                            <form id="create_schema_table_form" class="w-100">
                                @csrf
                                <div class="form-inline">
                                    <input type="text" class="form-control col-md-6 mr-2 table_name" id="table_name" name="schema_table['name']" placeholder="@lang('form.enter_table_name')">
                                    @include('schemaTables.engine')
                                    @include('schemaTables.collation')
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="th_column_name">@lang('form.column_name')</th>
                                        <th class="th_type">@lang('form.type')</th>
                                        <th class="th_two_letter">@lang('form.length')</th>
                                        <th class="th_two_letter">@lang('form.null')</th>
                                        <th class="th_two_letter" title="@lang('form.un')">UN</th>
                                        <th class="th_two_letter" title="@lang('form.uq')">UQ</th>
                                        <th class="th_two_letter" title="@lang('form.ai')">AI</th>
                                        <th class="th_two_letter" title="@lang('form.pk')">PK</th>
                                        <th class="th_two_letter" title="@lang('form.zf')">ZF</th>
                                        <th class="th_default">@lang('form.default')</th>
                                        <th class="th_comment">@lang('form.comment')</th>
                                    </tr>
                                </thead>
                                <form id="create_schema_table_column_form" class="w-100">
                                    @csrf
                                    <tbody id="table_detail_tbody">
                                        @include('schemaTableColumns.exampleRow', ['schemaTableColumn' => null])
                                    </tbody>
                                </form>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="create_schema_table_button"
                            data-loading-text="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> @lang('form.saving')">
                            @lang('form.save')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<table id="column_example_row" class="d-none">
    <tbody>
        @include('schemaTableColumns.exampleRow', ['schemaTableColumn' => null])
    </tbody>
</table>
@endsection
