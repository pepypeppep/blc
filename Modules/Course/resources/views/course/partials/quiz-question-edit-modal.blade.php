<div class="modal-header">
    <h6 class="modal-title fs-5" id="">{{ __('Update Quiz Question') }}</h6>
</div>

<div class="modal-body">
    <form action="{{ route('admin.course-chapter.quiz-question.update', $question->id) }}" method="POST"
        class="add_lesson_form instructor__profile-form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title">{{ __('Question Title') }} <code>*</code></label>
                    <textarea id="question-summernote" name="title" type="text" class="form-control">
                    {{ $question->title }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-md-2" for="title">Bobot {{ __('Grade') }} <code>*</code></label>
                <div class="col-md-6">
                    <input id="title" name="grade" type="text" class="form-control"
                        value="{{ $question->grade }}">
                </div>
            </div>

            <hr />
            <div class="answer-container">
                <div class="card border-1 mt-3">
                    <div class="card-body">
                        @php $i=1; @endphp
                        @foreach ($question->answers as $answer)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="answer">{{ __('Answer Title') }} <code>*</code></label>
                                        <button class="remove-answer" type="button"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </div>
                                    <textarea id="answer-summernote-{{ $i++ }}" class="answer form-control" name="answers[{{ $answer->id }}]">{{ $answer->title }}</textarea>
                                </div>
                                <div class="switcher row mt-2">
                                    <div class="col-md-5 d-flex align-items-center">
                                        <p class="mr-3">{{ __('Correct Answer') }}</p>
                                        <label for="toggle-{{ $answer->id }}">
                                            <input class="correct" type="checkbox" id="toggle-{{ $answer->id }}"
                                                value="1" name="correct[{{ $answer->id }}]"
                                                @checked($answer->correct == 1) />
                                            <span><small></small></span>
                                        </label>
                                    </div>
                                    <div class="col-md-7 d-flex justify-content-end">
                                        <button type="button"
                                            class="append-answer btn btn-primary">{{ __('Add Answer') }}</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-btn">{{ __('Update') }}</button>
        </div>
    </form>
</div>
