@component('mail::message')
@lang('form.hello'),

@lang('form.please_click_button_to_login')
@component('mail::button', ['url' => $url])
@lang('form.sign_in')
@endcomponent

@lang('form.email_no_action_required')<br>

@lang('form.thanks'),<br>
{{ config('app.name') }}
@endcomponent
