<div class="modal-header">
    <h1 class="modal-title fs-5" id="">{{ __('Add Document') }}</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="p-3">
    <form action="{{ route('instructor.course-chapter.lesson.store') }}" method="POST"
        class="add_lesson_form instructor__profile-form">
        @csrf
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="col-md-12">
            <div class="form-grp">
                <label for="chapter">{{ __('Chapter') }} <code>*</code></label>
                <select name="chapter" id="chapter" class="chapter from-select">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($chapters as $chapter)
                        <option @selected($chapterId == $chapter->id) value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-grp">
                <label for="title">{{ __('Title') }} <code>*</code></label>
                <input id="title" name="title" type="text" value="">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 upload">
                <div class="from-group mb-3">
                    <label class="form-file-manager-label" for="">{{ __('Path') }}
                        <code>*</code></label>
                    <div class="input-group">
                        <span class="input-group-text file-choose" id="basic-addon1">
                            <a data-input="path" data-preview="holder">
                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                            </a>
                        </span>
                        <input class="form-control d-none" type="file" name="upload_path" accept=".pdf, .docx, .txt">
                        <input id="path" readonly class="form-control file-choose" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-none">
                <div class="form-grp">
                    <label for="file_type_select">{{ __('File Type') }} <code>*</code></label>
                    <select name="file_type" id="file_type_select" class="file_type form-select">
                        <option value="">{{ __('Select') }}</option>
                        @foreach (config('course.file_types') as $key => $value)
                            @if (in_array($key, ['pdf', 'txt', 'docx']))
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-grp">
                <label for="description">{{ __('Description') }} <code></code></label>
                <textarea name="description" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">{{ __('Create') }}</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $(".file-choose").on("click", function() {
            $("input[name='upload_path']").trigger("click");
            $("input[name='upload_path']").on("change", function() {
                var file = $(this).get(0).files[0];
                var fileName = file.name;
                $("#path").val(fileName);

                var fileExt = fileName.split('.').pop().toLowerCase();
                $('#file_type_select').val(fileExt).trigger('change');
            });
        });
    });
</script>
