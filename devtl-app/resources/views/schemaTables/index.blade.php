@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1>{{ $pageTitle }}</h1>
    <a role="button" class="btn btn-primary text-white" href="{{ route('schemaTables.create', ['schema' => $schema->id]) }}">@lang('form.create_table')</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col-1">#</th>
                <th scope="col-9">@lang('form.table_name')</th>
                <th scope="col-1">@lang('form.no_of_columns')</th>
                <th scope="col-1">@lang('form.action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schemaTables as $schemaTable)
            <tr class="table_row">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $schemaTable->name }}</td>
                <td>{{ $schemaTable->schemaTableColumns->count() }}</td>
                <td><a role="button"
                        class="btn btn-primary btn-sm"
                        href="{{ route('schemaTables.edit', ['schemaTable' => $schemaTable->id]) }}">
                            <span class="oi oi-pencil"></span>
                    </a>
                    <button type="button"
                        class="btn btn-danger btn-sm delete_column_button"
                        data-href="{{ route('schemaTables.delete', ['schemaTable' => $schemaTable->id]) }}"
                        data-item="schema_table"
                        data-toggle="modal"
                        data-target="#delete_confirm_modal">
                            <span class="oi oi-x"></span>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">@lang('form.no_tables')</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
