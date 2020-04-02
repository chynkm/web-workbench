@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1>{{ $pageTitle }}</h1>
    <button type="button" class="btn btn-primary" id="create_table_btn">@lang('form.create_table')</button>
</div>

<div id="table_listing" class="d-none">
    <div class="row">
        @for($i = 0; $i < 5; $i++)
        @forelse ($schemaTables as $schemaTable)
        <div class="col-md-3 mt-2">
            <button type="button" class="btn btn-outline-primary btn-block">
                {{ $schemaTable->name }}
            </button>
        </div>
        @endforeach
        @endfor
    </div>
</div>

<div id="table_column_listing">
    <div class="row">
        <div class="col-md-3 border tables_listing_div">
            <div class="row">
                @for($i = 0; $i < 2; $i++)
                @forelse ($schemaTables as $schemaTable)
                <div class="col-md-12 mt-2">
                    <button type="button" class="btn btn-outline-primary btn-block">
                        {{ $schemaTable->name }}
                    </button>
                </div>
                @endforeach
                @endfor
            </div>
        </div>
        <div class="col-md-9">
            <div class="row table_detail">
                <form id="create_schema_table_form" class="w-100" action="{{ route('schemaTables.store', ['schema' => $schema->id]) }}">
                    @csrf
                    <div class="card rounded-0 h-100">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="form-group">
                                    <label>@lang('form.table_name')</label>
                                    <input type="text" class="form-control" name="name" placeholder="@lang('form.enter_table_name')">
                                </div>
                            </div>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Column name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Length</th>
                                        <th scope="col">PK</th>
                                        <th scope="col">Null</th>
                                        <th scope="col">UQ</th>
                                        <th scope="col">UN</th>
                                        <th scope="col">ZF</th>
                                        <th scope="col">AI</th>
                                        <th scope="col">Default</th>
                                        <th scope="col">Comments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>bigint</td>
                                        <td>11</td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['primary_key'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['null'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unique'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unsigned'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['zerofill'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['auto_increment'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td>Null</td>
                                        <td>comment</td>
                                    </tr>
                                    <tr>
                                        <td>account_id</td>
                                        <td>bigint</td>
                                        <td>11</td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['primary_key'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['null'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unique'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unsigned'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['zerofill'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['auto_increment'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td>Null</td>
                                        <td>comment</td>
                                    </tr>
                                    <tr>
                                        <td>first_name</td>
                                        <td>varchar</td>
                                        <td>30</td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['primary_key'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['null'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unique'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unsigned'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['zerofill'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['auto_increment'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td>Null</td>
                                        <td>comment</td>
                                    </tr>
                                    <tr>
                                        <td>last_name</td>
                                        <td>varchar</td>
                                        <td>30</td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['primary_key'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['null'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unique'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unsigned'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['zerofill'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['auto_increment'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td>Null</td>
                                        <td>comment</td>
                                    </tr>
                                    <tr>
                                        <td>survey_invitation_target_survey_id</td>
                                        <td>integer</td>
                                        <td>11</td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['primary_key'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['null'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unique'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['unsigned'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['zerofill'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input position-static" type="checkbox" name="schema_table_columns['auto_increment'][]" value="1" id="defaultCheck1">
                                            </div>
                                        </td>
                                        <td>Null</td>
                                        <td>comment</td>
                                    </tr>
                                </tbody>
                            </table>
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
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
