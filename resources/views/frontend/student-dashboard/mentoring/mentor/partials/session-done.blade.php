<form>
    <div class="form-group mt-4">
        <label for="mentoring_date">{{ __('Mentoring Date') }}</label>
        <input type="text" name="mentoring_date" class="form-control datetimepicker"
            value="{{ $session->mentoring_date ?? '' }}" disabled>
    </div>
    <div class="form-group mt-4">
        <label for="mentoring_note">{{ __('Catatan') }}</label>
        <p>{!! clean(@$session?->mentoring_note) !!}</p>
    </div>
    <div class="form-group mt-4">
        <label for="mentoring_instructions">{{ __('Arahan') }}
        </label>
        <p>{!! clean(@$session?->mentoring_instructions) !!}</p>
    </div>
</form>
