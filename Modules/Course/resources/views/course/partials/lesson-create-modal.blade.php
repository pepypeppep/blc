<div class="modal-header">
    <h6 class="modal-title fs-5" id="">{{ __('Add Lesson') }}</h6>
</div>

<div class="">
    <form action="{{ route('admin.course-chapter.lesson.store') }}" method="POST"
        class="add_lesson_form instructor__profile-form">
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
            <div class="col-md-8 link_path">
                <div class="form-grp">
                    <label for="meta_description">{{ __('Path') }} <code></code></label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-link"></i></span>
                        <input type="text" class="form-control" id="input_link" name="link_path"
                            placeholder="{{ __('Youtube source url') }}" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="duration">{{ __('Duration') }} <code>*
                            <small>({{ __('in minutes') }})</small></code></label>
                    <input class="form-control" id="duration" name="duration" type="number" value=""
                        pattern="^(?!0)\d+$" title="{{ __('Cannot input 0 infront') }}"
                        oninput="this.value = this.value.replace(/^0/, '')">
                </div>
            </div>
        </div>

        <div class="">
            <div class="form-grp">
                <label for="description">{{ __('Description') }} <code></code></label>
                <textarea name="description" class="form-control"></textarea>
            </div>
        </div>

        <div class="row is_free_wrapper">
            <div class="col-md-6 mt-2">
                <span>{{ __('Preview') }}</span>
                <div class="switcher ms-3">
                    <label for="toggle-0">
                        <input type="checkbox" id="toggle-0" value="1" name="is_free" />
                        <span><small></small></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">{{ __('Create') }}</button>
        </div>
    </form>
</div>
