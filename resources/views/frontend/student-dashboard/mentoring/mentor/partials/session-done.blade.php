<form>
    <div class="form-group mt-4">
        <label for="mentoring_date">{{ __('Mentoring Date') }}</label>
        <input type="text" name="mentoring_date" class="form-control datetimepicker"
            value="{{ $session->mentoring_date ?? '' }}" disabled>
    </div>
    <div class="form-group mt-4">
        <strong class="d-block">Catatan:</strong>
        <div class="text-body">{!! clean(@$session?->mentoring_note) !!}</div>
    </div>
    <div class="form-group mt-4">
        <strong class="d-block">Arahan:</strong>
        <div class="text-body">{!! clean(@$session?->mentoring_instructions) !!}</div>
    </div>
</form>
