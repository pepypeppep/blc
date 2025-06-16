<form action="{{ route('student.mentee.lapor', $session->id) }}" method="POST" class="d-inline"
    enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-group mt-2">
        <label for="activity">{{ __('Activity') }} <code>*</code></label>
        <textarea name="activity" class="text-editor form-control summernote">{!! clean(@$session?->activity) !!}</textarea>
    </div>
    <div class="form-group mt-2">
        <label for="description">{{ __('Hambatan') }} <code>*</code></label>
        <textarea name="description" class="text-editor form-control summernote">{!! clean(@$session?->description) !!}</textarea>
    </div>
    <div class="form-group mt-2">
        <label for="file">{{ __('Foto') }} <code>*</code></label>
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1" onclick="$('#file').trigger('click');">
                <a data-input="file" data-preview="holder">
                    <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                </a>
            </span>
            <input id="file_name_{{ $key }}" readonly class="form-control" type="text" name="file_name"
                value="#" onclick="$('#file').trigger('click');">
            <input id="file" name="file" class="form-control d-none" type="file"
                onchange="$('#file_name_{{ $key }}').val(this.files[0].name)" accept=".jpg,.jpeg,.png">
        </div>
    </div>
    @if ($session->status == 'pending')
        <div class="row">
            <div class="col-12 text-end">

                <button type="submit" class="btn mt-4 mb-3">
                    {{ __('Submit Laporan') }} <i class="fa fa-arrow-right"></i>
                </button>

            </div>
        </div>
    @endif
</form>
