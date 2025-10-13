@extends('frontend.pages.learning-player.master')

@section('contents')
    <section class="wsus__course_video">
        <div class="col-12">
            <div class="wsus__course_header">
                @if (Session::has('course_slug'))
                    <a href="{{ route('student.learning.index', Session::get('course_slug')) }}"><i
                            class="fas fa-angle-left"></i>{{ truncate(Session::get('course_title')) }}</a>
                @endif
            </div>
        </div>


        <div class="container">
            <div class="question-container">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="info-col text-center">
                                <img src="{{ asset('uploads/website-images/student-grades.png') }}">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title count">{{ $quiz->pass_mark }}/{{ $quiz->total_mark }}</h6>
                                <p class="card-text">{{ __('Minimum Marks') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="info-col text-center">
                                <img src="{{ asset('uploads/website-images/test.png') }}">
                            </div>
                            <div class="card-body">
                                @if ($quiz->attempt != null)
                                    <h6 class="card-title count">{{ $attempt }}/{{ $quiz->attempt }}</h6>
                                    <p class="card-text">{{ __('Attempts') }}</p>
                                @else
                                    <h6 class="card-title count"><i class="fas fa-infinity"></i></h6>
                                    <p class="card-text">{{ __('Attempts') }} Tak terbatas</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="info-col text-center">
                                <img src="{{ asset('uploads/website-images/question.png') }}">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title count">{{ $quiz->questions_count }}</h6>
                                <p class="card-text">{{ __('Questions') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">

                        <div class="card text-center">
                            <div class="info-col text-center">
                                <img src="{{ asset('uploads/website-images/clock.png') }}">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title count"><span
                                        class="hour">{{ __('0') }}</span>{{ __(':') }}<span
                                        class="minute">{{ __('0') }}</span>{{ __(':') }}<span
                                        class="second">{{ __('0') }}</span></h6>
                                <p class="card-text">{{ __('Remained time') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                    aria-label="Warning:">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <div>
                    {{ __('Please note that you have to complete all the questions and submit before remaining time. The form will be submitted automatically if remaining time ends.') }}
                </div>
            </div>

            <div class="card mt-3">
                <form action="{{ route('student.quiz.store', request('id')) }}" method="POST" class="question-form">
                    @csrf
                    <div class="card-body">
                        @foreach ($quiz->questions as $question)
                            <div class="question-box mt-4">
                                <div class="d-flex align-items-start mb-4">
                                    <!-- Kotak nomor: tetap kecil dan sejajar atas -->
                                    <div class="me-3" style="width: 42px;">
                                        <h6 class="mb-0 fs-5 fw-bold">{{ $loop->iteration }}.</h6>
                                    </div>

                                    <!-- Konten gambar + title -->
                                    <div class="flex-grow-1">
                                        @if ($question->image)
                                            <div class="mb-2">
                                                <img src="{{ $question->image ? url('questions/image/' . baseName($question->image)) : null }}"
                                                    alt="Gambar Pertanyaan" class="img-fluid border shadow-sm"
                                                    style="max-width: 420px; height: auto; object-fit: cover; display: block;">
                                            </div>
                                        @endif

                                        <div class="question-title fs-5 fw-semibold">
                                            {!! $question->title !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($question->answers as $answer)
                                        <div class="col-md-6">
                                            <div class="card ans-body m-2 shadow-sm border-0">
                                                <label for="ans-{{ $answer->id }}" class="box first p-3 w-100 d-block">
                                                    @if ($answer->image)
                                                        <div class="mb-2 text-start">
                                                            <img src="{{ $answer->image ? url('answers/image/' . baseName($answer->image)) : null }}"
                                                                alt="Gambar Jawaban" class="img-fluid"
                                                                style="width: 100%; height: 180px; object-fit: contain; object-position: left;">
                                                        </div>
                                                    @endif

                                                    <div class="course d-flex align-items-start">
                                                        <span class="circle me-2 mt-1">
                                                            <input type="radio" name="question[{{ $question->id }}]"
                                                                id="ans-{{ $answer->id }}" value="{{ $answer->id }}">
                                                        </span>
                                                        <span class="subject question-title fw-medium">
                                                            {!! $answer->title !!}
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                            </div>
                        @endforeach
                        <div class="mt-4 text-end">
                            <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/js/default/quiz-page.js') }}"></script>
    <script>
        $(document).ready(function() {
            countdown({{ $quiz->time }});
        })
    </script>
@endpush

@push('styles')
    <style>
        .question-title p {
            display: inline;
            margin: 0;
            padding: 0;
            color: #000;
        }
    </style>
@endpush
