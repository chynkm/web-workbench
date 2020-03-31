@extends('layouts.app')

@section('content')
<h1 class="ml-4">{{ $pageTitle }}</h1>

<div class="db_canvas">
    <div class="outer_container" style="height: {{ $schema->height }}px">
        <div class="db_container" id="child">
            <div id="abc" class="card m-4">
                <div class="card-header">
                    <strong>users</strong>
                    <span class="float-sm-right">
                    <button type="button" class="btn btn-outline-secondary btn-xs">
                    <span class="oi oi-pencil"></span>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs">
                    <span class="oi oi-circle-x"></span>
                    </button>
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm schema_table">
                        <tbody>
                            <tr>
                                <td><span class="oi oi-key text-warning" title="PRIMARY KEY" aria-hidden="true"></span></td>
                                <td>id</td>
                                <td>bigint(11)</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-xs">
                                    <span class="oi oi-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="oi oi-media-stop text-primary" title="FOREIGN KEY" aria-hidden="true"></span></td>
                                <td>account_id</td>
                                <td>bigint(11)</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-xs">
                                    <span class="oi oi-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="oi oi-media-stop text_null" title="NULL" aria-hidden="true"></span></td>
                                <td>first_name</td>
                                <td>varchar(30)</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-xs">
                                    <span class="oi oi-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>last_name</td>
                                <td>varchar(30)</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-xs">
                                    <span class="oi oi-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>survey_invitation_target_survey_id</td>
                                <td>integer(11)</td>
                                <td>
                                    <button type="button" class="btn btn-outline-secondary btn-xs">
                                    <span class="oi oi-pencil"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="def" id="draggable" style="height: 100px; width: 200px; border: 1px solid black;">
                div 2
                <li id="posX"></li>
                <li id="posY"></li>
            </div>
        </div>
    </div>
</div>
@endsection


