@extends('layouts.app')

@section('content')
<div class="row justify-content-center signin">
    <div class="col-md-8">
        <form class="form-signin" method="POST" action="{{ route('register') }}">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal">@lang('form.create_your_account', ['app_name' => config('app.name')])</h1>
            <div class="form-group">
                <label for="inputEmail">@lang('form.email')</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="@lang('form.email_placeholder')" value="{{ old('email') }}" autofocus>
                <small id="emailHelp" class="form-text text-muted">@lang('form.register_help_text')</small>
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
