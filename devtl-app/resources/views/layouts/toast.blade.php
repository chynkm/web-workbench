@if (isset($alert) || $alert = session('alert'))
<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000">
    <div class="toast-body bg-{{ $alert['class'] }} text-white">
        {!! $alert['message'] !!}
    </div>
</div>
@endif
