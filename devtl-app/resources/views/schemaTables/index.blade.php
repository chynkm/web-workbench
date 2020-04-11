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

<div id="table_error_display_div">
</div>

<div id="table_listing">
    <div class="row">
        @forelse ($schemaTables as $schemaTable)
        <div class="col-md-4 mt-2">
            <button type="button"
                class="btn btn-outline-primary btn-block table_button"
                title="{{ $schemaTable->name }}"
                data-route_get_columns="{{ route('schemaTables.columns', ['schemaTable' => $schemaTable->id]) }}"
                data-route_save_table="{{ route('schemaTables.update', ['schemaTable' => $schemaTable->id]) }}"
                data-route_save_columns="{{ route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]) }}"
                data-name="{{ $schemaTable->name }}"
                data-engine="{{ $schemaTable->engine }}"
                data-collation="{{ $schemaTable->collation }}">
                {{ substr($schemaTable->name, 0, 40) }}
            </button>
        </div>
        @endforeach
    </div>
</div>

<div id="table_column_listing" class="d-none">
    <div class="row">
        <div class="col-md-3 border sidebar_tables_listing">
            @include('schemaTables.sideBarColumn', compact('schemaTables'))
        </div>
        <div class="col-md-9">
            <div class="row table_detail">
                <div class="card rounded-0 h-100">
                    <div class="card-body">
                        <div class="card-title">
                            <form id="create_schema_table_form" class="w-100" onsubmit="return false;">
                                @csrf
                                <div class="form-inline">
                                    <input type="text" class="form-control col-md-6 mr-2 table_name" id="table_name" name="name" placeholder="@lang('form.enter_table_name')">
                                    @include('schemaTables.engine')
                                    @include('schemaTables.collation')
                                </div>
                            </form>
                        </div>
                        <form id="create_schema_table_column_form" class="w-100" onsubmit="return false;">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th class="th_sort_column"><span class="oi oi-sort-ascending"></span></th>
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
                                            <th class="th_sort_column" title="@lang('form.delete')"><span class="oi oi-trash"></span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_detail_tbody">
                                        @include('schemaTableColumns.exampleRow', ['schemaTableColumn' => null])
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary" id="create_schema_table_button">
                            @lang('form.save')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_confirm_modal" tabindex="-1" role="dialog" aria-labelledby="createSchemaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center"><span class="oi oi-warning text-danger warning_sign"></span> @lang('form.delete_item')</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('form.cancel')</button>
                <button type="button" class="btn btn-danger text-white" id="delete_ok">@lang('form.delete')</button>
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
