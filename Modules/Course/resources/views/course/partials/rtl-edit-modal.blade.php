<div class="modal-header">
    <h6 class="modal-title fs-5" id="">{{ __('Ubah Rencana Tindak Lanjut') }}</h6>
</div>

<div class="">
    <form action="{{ route('admin.course-chapter.rtl.update', $chapterItem->followUpAction->id) }}" method="POST"
        class="add_lesson_form instructor__profile-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        <input type="hidden" name="chapter_item_id" value="{{ $chapterItem->id }}">
        <input type="hidden" name="type" value="{{ $chapterItem->type }}">

        <div class="col-md-12">

            <div class="form-grp">
                <label for="chapter">{{ __('Chapter') }} <code>*</code></label>
                <select name="chapter" id="chapter" class="chapter form-control">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($chapters as $chapter)
                        <option @selected($chapterItem->chapter_id == $chapter->id) value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-grp">
                <label for="title">{{ __('Title') }} <code>*</code></label>
                <input id="title" name="title" type="text" class="form-control"
                    value="{{ $chapterItem->followUpAction->title }}" />
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-grp">
                <label for="description">{{ __('Description') }} <code></code></label>
                <textarea name="description" class="form-control">{{ $chapterItem->followUpAction->description }}</textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">{{ __('Update') }}</button>
        </div>
    </form>
</div>
