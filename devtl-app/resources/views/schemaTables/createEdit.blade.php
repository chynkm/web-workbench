@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 id="table_title">{{ $pageTitle }}</h1>
    <a role="button" class="btn btn-primary text-white" href="{{ route('schemaTables.index', ['schema' => isset($schema) ? $schema->id : $schemaTable->schema_id]) }}">@lang('form.tables_listing')</a>
</div>

<div id="table_error_display_div">
</div>

<div id="table_column_listing" class="mb-4">
    <div class="table_detail">
        <div class="card rounded-0 h-100">
            <div class="card-body">
                <div class="card-title">
                    <form
                        id="create_schema_table_form"
                        class="w-100"
                        data-engine="{{ isset($schemaTable) ? $schemaTable->engine : config('env.first_table_engine') }}"
                        data-collation="{{ isset($schemaTable) ? $schemaTable->collation : config('env.first_table_collation') }}"
                        action="{{ isset($schema) ? route('schemaTables.store', ['schema' => $schema->id]) : route('schemaTables.update', ['schemaTable' => $schemaTable->id]) }}"
                        onsubmit="return false;">
                        @csrf
                        <div class="form-inline">
                            <input type="text" class="form-control col-md-7 mr-2 table_name" id="table_name" name="name" placeholder="@lang('form.enter_table_name')" value="{{ isset($schemaTable) ? $schemaTable->name : null }}">
                            @include('schemaTables.engine')
                            @include('schemaTables.collation')
                        </div>
                    </form>
                </div>
                <form
                    id="create_schema_table_column_form"
                    class="w-100"
                    @if(isset($schemaTable))
                    action="{{ route('schemaTables.updateColumns', ['schemaTable' => $schemaTable->id]) }}"
                    @endif
                    onsubmit="return false;">
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
                                @include('schemaTables.columns', ['schemaTableColumns' => $schemaTableColumns ?? []] )
                                @include('schemaTableColumns.exampleRow', ['schemaTableColumn' => null])
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-2">
    <div class="card-header">
        @lang('form.foreign_keys')
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="th_column_name">@lang('form.column_name')</th>
                        <th class="th_two_letter">@lang('form.referenced_table')</th>
                        <th class="th_two_letter">@lang('form.referenced_column')</th>
                        <th class="th_sort_column" title="@lang('form.delete')"><span class="oi oi-trash"></span></th>
                    </tr>
                </thead>
                <tbody id="foreign_key_tbody">
                    @foreach($relationships as $relationship)
                    @include('relationships.exampleRelationshipRow', compact('schemaTableColumns', 'schemaTables', 'relationship'))
                    @endforeach
                    @include('relationships.exampleRelationshipRow', ['schemaTableColumns' => $schemaTableColumns ?? [], 'schemaTables' => $schemaTables, 'relationship' => null] )
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-primary" id="create_schema_table_button">
            @lang('form.save')
        </button>
    </div>
</div>

<table id="column_example_row" class="d-none">
    <tbody>
        @include('schemaTableColumns.exampleRow', ['schemaTableColumn' => null])
    </tbody>
</table>
@endsection
