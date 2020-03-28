@php $pageTitle = __('form.404') @endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="clearfix">
                <h1 class="float-left display-3 mr-4">404</h1>
                <h4 class="pt-3">{{ isset($exception) && $exception->getMessage() ? $exception->getMessage() : __('form.404_message') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection
