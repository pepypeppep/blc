<div class="form-group mt-2">
    <h6>{{ __('Activity') }}</h6>
    <p>{!! $session->activity ?? '-' !!}</p>
</div>
<div class="form-group mt-2">
    <h6>{{ __('Hambatan') }}</h6>
    <p>{!! $session->description ?? '-' !!}</p>
</div>
<div class="form-group mt-2">
    <h6 for="file">{{ __('Foto') }}</h6>
    <a href="{{ route('student.mentee.laporan-foto', $session->id) }}" target="_blank">{{ $session->image }}</a>
</div>
@if ($session->status == 'reviewed')
    <div class="form-group mt-4">
        <h6>{{ __('Catatan Mentor') }}</h6>
        <p>{!! $session->mentoring_note ?? '-' !!}</p>
    </div>
    <div class="form-group mt-2">
        <h6>{{ __('Arahan Mentor') }}</h6>
        <p>{!! $session->mentoring_instructions ?? '-' !!}</p>
    </div>
@endif
