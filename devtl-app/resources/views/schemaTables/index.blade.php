@extends('layouts.app')

@section('content')
<h1>{{ $pageTitle }}</h1>

<div class="row schema_list">
    @forelse ($schema->schemaTables as $schemaTable)
    <div class="col-md-4 mt-4">
        <a href="#">
            <div class="card border-primary text-center">
                <div class="card-body">
                    <h4 class="card-title">{{ $schemaTable->name }}</h4>
                </div>
            </div>
        </a>
    </div>
    @endforeach
    <div class="col-md-4 mt-4">
        <a href="#" data-toggle="modal" data-target="#create_schema_modal" class="new_schema_href">
            <div class="card text-center new_schema">
                <div class="card-body">
                    <h4 class="card-title text-muted">@lang('form.create_table')</h4>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection


