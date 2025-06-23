<form action="{{ route('student.mentor.review', $session->id) }}" method="POST" class="d-inline"
    enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group mt-4">
        <label for="mentoring_date">{{ __('Mentoring Date') }} <code>*</code></label>
        <input type="text" name="mentoring_date" class="form-control datetimepicker"
            value="{{ $session->mentoring_date ?? '' }}" required>
        <span>* Jika sesi mentoring tidak sesuai jadwal, silahkan ubah tanggal sesi mentoring</span>
    </div>
    <div class="form-group mt-4">
        <label for="mentoring_note">{{ __('Catatan') }} <code>*</code></label>
        <textarea name="mentoring_note" class="text-editor form-control summernote">{!! clean(@$session?->mentoring_note) !!}</textarea>
    </div>
    <div class="form-group mt-4">
        <label for="mentoring_instructions">{{ __('Arahan') }}
            <code>*</code></label>
        <textarea name="mentoring_instructions" class="text-editor form-control summernote">{!! clean(@$session?->mentoring_instructions) !!}</textarea>
    </div>
    @if (!$session->mentoring_note && !$session->mentoring_instructions && $mentoring->status == 'Proses')
        <div class="row">
            <div class="col-12 text-end">

                <button type="submit" class="btn mt-4 mb-3">
                    {{ __('Kirim') }} <i class="fa fa-arrow-right"></i>
                </button>

            </div>
        </div>
    @endif
</form>
