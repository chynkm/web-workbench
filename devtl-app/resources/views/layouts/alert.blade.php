@if (isset($alert) || $alert = session('alert'))
<div class="alert alert-dismissible alert-{{ $alert['class'] }}" role="alert">
    {!! $alert['message'] !!}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
@endif
