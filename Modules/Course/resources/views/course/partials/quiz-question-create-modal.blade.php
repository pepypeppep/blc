<div class="modal-body">
    <form action="{{ route('admin.course-chapter.quiz-question.store', $quizId) }}" method="POST"
        class="add_lesson_form instructor__profile-form">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title">{{ __('Question Title') }} <code>*</code></label>
                    <textarea id="title" name="title" value="" class="form-control summernote"></textarea>
                </div>
                <div class="mb-3 row">
                    <label for="title" class="col-md-2 col-form-label">Bobot {{ __('Grade') }}
                        <code>*</code></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="title" name="grade" value="">
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="answer-container">
            <div class="card border-1 mt-3">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="form-grp">
                            <div class="d-flex justify-content-between">
                                <label for="answer">{{ __('Answer Title') }} <code>*</code></label>
                                <button class="remove-answer" type="button"><i class="fas fa-trash-alt"></i></button>
                            </div>
                            <textarea class="answer form-control summernote" name="answers[]" value=""></textarea>
                        </div>
                        <div class="switcher row mt-2">
                            <div class="col-md-5 d-flex align-items-center">
                                <p class="mr-3">{{ __('Correct Answer') }}</p>
                                <label for="toggle-0" class="ms-2">
                                    <input type="checkbox" class="correct" id="toggle-0" value="1"
                                        name="correct[]" />
                                    <span><small></small></span>
                                </label>
                            </div>
                            <div class="col-md-7 d-flex justify-content-end">
                                <button type="button"
                                    class="add-answer btn btn-primary">{{ __('Add Answer') }}</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">Simpan Kuis</button>
        </div>
    </form>
</div>
