@extends('layouts.app')

@section('content')
<h1>{{ $pageTitle }}</h1>
<div class="container-outer" style="overflow: scroll; width: 100%;">
    <div class="parent db-canvas mt-5" style="width: 3000px; overflow-x: hidden;">
        <div class="child" id="child" style="border: 1px solid black; height: 100%; overflow-y:auto; background-image: url('images/square-grid.svg');">
            <div id="abc" class="card m-4" style="width: 15rem;">
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
                    <table class="table table-bordered table-sm">
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


