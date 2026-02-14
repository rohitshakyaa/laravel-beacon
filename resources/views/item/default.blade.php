@php
    /** @var \RohitShakya\Beacon\Presenters\BeaconNotification $ui */
@endphp

<div class="beacon-item">
    <div class="beacon-title">{{ $ui->title() }}</div>

    @if ($ui->body())
        <div class="beacon-body">{{ $ui->body() }}</div>
    @endif

    @if ($ui->url())
        <a class="beacon-link" href="{{ $ui->url() }}">Open</a>
    @endif
</div>
