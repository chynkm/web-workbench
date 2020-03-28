@php $pageTitle = __('form.login') @endphp
@extends('layouts.app')

@section('content')
<div class="row justify-content-center signin">
    <div class="col-md-8">
        <form class="form-signin" method="POST" action="{{ route('link.sendLoginEmail') }}">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal">@lang('form.sign_in_to_app', ['app_name' => config('app.name')])</h1>
            <div class="form-group">
                <label for="inputEmail">@lang('form.email')</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="@lang('form.email_placeholder')" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">
                @lang('form.email_me_a_link')
            </button>
        </form>
    </div>
</div>
@endsection
