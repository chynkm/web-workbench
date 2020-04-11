@extends('layouts.app')

@section('content')
<h1 class="mb-4">{{ $pageTitle }}</h1>

<div class="row schema_list">
    @forelse ($schemas as $schema)
    <div class="col-md-4 mb-4">
        <a href="{{ route('schemaTables.index', ['schema' => $schema->id]) }}">
            <div class="card border-primary text-center">
                <div class="card-body">
                    <h4 class="card-title">{{ $schema->name }}</h4>
                </div>
            </div>
        </a>
    </div>
    @endforeach
    <div class="col-md-4 mb-4">
        <a href="#" data-toggle="modal" data-target="#create_schema_modal" class="new_schema_href">
            <div class="card text-center new_schema">
                <div class="card-body">
                    <h4 class="card-title text-muted">@lang('form.create_schema')</h4>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="modal fade" id="create_schema_modal" tabindex="-1" role="dialog" aria-labelledby="createSchemaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('form.create_new_schema')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create_schema_form" action="{{ route('schemas.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">@lang('form.name_your_schema')</label>
                        <input name="name" id="name" type="text" class="form-control form-control-lg" placeholder="@lang('form.schema_name')">
                        <span class="invalid-feedback d-none"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-block btn-primary mt-2"
                        type="submit"
                        id="create_schema_button"
                        data-loading-text="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> @lang('form.processing_request')">
                        @lang('form.create_schema')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


