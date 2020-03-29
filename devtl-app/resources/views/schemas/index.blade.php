@extends('layouts.app')

@section('content')
<h1 class="bd-title" id="content">{{ $pageTitle }}</h1>

<div class="row">
    @forelse ($schemas as $schema)
    <div class="col-md-4 mt-4">
        <div class="card border-primary text-center">
            <div class="card-body">
                <h4 class="card-title">{{ $schema->name }}</h4>
            </div>
        </div>
    </div>
    @endforeach
    <div class="col-md-4 mt-4">
        <a href="#" data-toggle="modal" data-target="#createSchema">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">@lang('form.create_schema')</h4>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="modal fade" id="createSchema" tabindex="-1" role="dialog" aria-labelledby="createSchemaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h1 class="text-center">@lang('form.create_new_schema')</h1>
                <form id="create_schema_form" class="mt-3" action="{{ route('schemas.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="col-form-label">@lang('form.name_your_schema')</label>
                        <input name="name" id="name" type="text" class="form-control form-control-lg" placeholder="@lang('form.schema_name')">
                        <span class="invalid-feedback d-none"></span>
                    </div>
                    <button
                        class="btn btn-block btn-primary mt-2"
                        type="button"
                        id="create_schema_button"
                        data-loading-text="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> @lang('form.processing_request')">
                        @lang('form.create_schema')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


