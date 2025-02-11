<div class="modal-header">
    <h6 class="modal-title fs-5" id="">{{ __('Add Document') }}</h6>
</div>

<div class="">
    <form action="{{ route('admin.course-chapter.lesson.store') }}" method="POST"
        class="add_lesson_form instructor__profile-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="">
            <div class="form-group">
                <label for="chapter">{{ __('Chapter') }} <code>*</code></label>
                <select name="chapter" id="chapter" class="chapter form-control">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($chapters as $chapter)
                        <option @selected($chapterId == $chapter->id) value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="">
            <div class="form-grp">
                <label for="title">{{ __('Title') }} <code>*</code></label>
                <input id="title" name="title" type="text" value="" class="form-control">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 upload">
                <div class="from-group mb-3">
                    <label class="form-file-manager-label" for="">{{ __('Path') }}
                        <code>*</code></label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"
                            onclick="document.getElementById('path').click()">
                            <a data-input="path" data-preview="holder">
                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                            </a>
                        </span>
                        <input id="path" class="form-control d-none" type="file" name="file_path" value=""
                            onchange="document.getElementById('path_name').value = this.files[0].name" accept=".pdf">
                        <input id="path_name" readonly class="form-control" type="text" name="upload_path_name"
                            value="" onclick="document.getElementById('path').click()">
                    </div>
                </div>
            </div>
        </div>

        <div class="">
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
