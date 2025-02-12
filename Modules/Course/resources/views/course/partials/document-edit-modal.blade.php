<div class="modal-header">
    <h6 class="modal-title fs-5" id="">{{ __('Update Document') }}</h6>
</div>

<div class="p-3">
    <form action="{{ route('admin.course-chapter.lesson.update') }}" method="POST"
        class="update_lesson_form instructor__profile-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        <input type="hidden" name="chapter_item_id" value="{{ $chapterItem->id }}">
        <input type="hidden" name="type" value="{{ $chapterItem->type }}">

        <div>
            <div class="form-group">
                <label for="chapter">{{ __('Chapter') }} <code>*</code></label>
                <select name="chapter" id="chapter" class="chapter form-control">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($chapters as $chapter)
                        <option @selected($chapterItem->chapter_id == $chapter->id) value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="title">{{ __('Title') }} <code>*</code></label>
                <input id="title" name="title" type="text" value="{{ $chapterItem->lesson->title }}"
                    class="form-control">
            </div>
        </div>

        <div class="row">
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
                            value="{{ $chapterItem->lesson->storage == 'upload' ? $chapterItem->lesson->file_path : '' }}"
                            onclick="document.getElementById('path').click()">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="form-group">
                <label for="description">{{ __('Description') }} <code></code></label>
                <textarea name="description" class="form-control">{{ $chapterItem->lesson->description }}</textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">{{ __('Update') }}</button>
        </div>
    </form>
</div>
