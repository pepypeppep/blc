@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="container-instructor-review">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ __('Instructor Review') }}</h2>
        <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ $course->title }}</h3>

        <form action="{{ route('student.instructorevaluation.store') }}" method="POST">
            @csrf

            {{-- input hidden --}}
            <input type="hidden" name="instructor_id" value="{{ optional($selectedInstructor)->id }}">
            <input type="hidden" name="course_id" value="{{ $course->id }}">


            <div class="form-group">
                <label>{{ __('Select Instructor to Review') }}</label>
                <div class="instructor-selection">


                    @foreach ($instructors as $instructor)
                        <a href="{{ route('student.instructorevaluation.create', ['course' => $course->id, 'instructor' => $instructor->id]) }}"
                            for="instructor2"
                            class="instructor-option @if ($instructorEvaluations->pluck('instructor_id')->contains($instructor->id)) reviewed @endif
                    @if (optional($selectedInstructor)->id == $instructor->id) instructor-radio-selected @endif">
                            @if ($instructorEvaluations->pluck('instructor_id')->contains($instructor->id))
                                <div class="already-reviewed">✓</div>
                            @endif

                            <img src="{{ asset('/frontend/img/frontend-avatar.png') }}" alt="David Chen"
                                class="instructor-avatar">
                            <div class="instructor-name">{{ $instructor->name }}</div>
                            {{-- <div class="instructor-role">Backend Developer</div> --}}
                            {{-- <div class="already-reviewed-text">Already Reviewed</div> --}}
                        </a>
                    @endforeach


                </div>
            </div>

            @if ($selectedInstructor)
                <div class="form-group">
                    <label for="rating">Penguasaan Materi</label>
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="rating-stars d-flex justify-content-start flex-row-reverse">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="material_mastery{{ $i }}" name="material_mastery"
                                    value="{{ $i }}"
                                    {{ optional($selectedInstructorEvaluation)->material_mastery == $i ? 'checked' : '' }} />
                                <label for="material_mastery{{ $i }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rating">Kemampuan Melakukan Transfer Keilmuan</label>
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="rating-stars d-flex justify-content-start flex-row-reverse">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="knowledge_transfer_ability{{ $i }}"
                                    name="knowledge_transfer_ability" value="{{ $i }}"
                                    {{ optional($selectedInstructorEvaluation)->knowledge_transfer_ability == $i ? 'checked' : '' }} />
                                <label for="knowledge_transfer_ability{{ $i }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rating">Berkomunikasi dan Memotivasi Peserta</label>
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="rating-stars d-flex justify-content-start flex-row-reverse">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="communication_and_motivation{{ $i }}"
                                    name="communication_and_motivation" value="{{ $i }}"
                                    {{ optional($selectedInstructorEvaluation)->communication_and_motivation == $i ? 'checked' : '' }} />
                                <label for="communication_and_motivation{{ $i }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rating">Proses Diskusi dan Latihan</label>
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="rating-stars d-flex justify-content-start flex-row-reverse">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="discussion_and_exercise_process{{ $i }}"
                                    name="discussion_and_exercise_process" value="{{ $i }}"
                                    {{ optional($selectedInstructorEvaluation)->discussion_and_exercise_process == $i ? 'checked' : '' }} />
                                <label for="discussion_and_exercise_process{{ $i }}"
                                    class="star">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="feedback">Ulasan</label>
                    <textarea id="feedback" name="feedback" placeholder="Write your review here" required>{{ $selectedInstructorEvaluation ? $selectedInstructorEvaluation->feedback : '' }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/student/instructor-evaluation/create/{{ $course->id }}"
                        class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit">{{ __('Submit Review') }}</button>
                </div>
            @endif
        </form>
    </div>
@endsection
@push('styles')
    <style>
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            color: #fff;
        }

        .rating-stars {
            gap: 20px;
            margin-top: -10%;
            margin-bottom: -10%;
        }

        .rating-stars .star {
            font-size: 7rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars input[type="radio"]:checked~.star,
        .rating-stars input[type="radio"]:checked+label~label {
            color: #ffc107;
        }

        .container-instructor-review {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 768px;
            margin: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 1rem;
            color: #374151;
            outline: none;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button[type="submit"] {
            background-color: #6366f1;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #4f46e5;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating input {
            display: none;
        }

        .rating label {
            position: relative;
            width: 1em;
            font-size: 2rem;
            color: #ffc107;
            cursor: pointer;
        }

        .rating label::before {
            content: "\2605";
            position: absolute;
            top: 0;
            left: 0;
            /* opacity: 0.1; */
            color: #b4b4b3;
            transition: opacity 0.2s ease;
        }

        .rating label:hover::before,
        .rating label:hover~label::before,
        .rating input:checked~label::before {
            opacity: 1;
            color: #ffc107;
        }

        /* Instructor Selection Styles */
        .instructor-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .instructor-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: border-color 0.2s ease, background-color 0.2s ease;
            position: relative;
            /* For positioning the checkmark */
        }

        .instructor-option:hover {
            border-color: #9ca3af;
            background-color: #f9fafb;
        }

        .instructor-radio {
            display: none;
        }



        .instructor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
            border: 2px solid transparent;
            transition: border-color 0.2s ease;
        }

        .instructor-name {
            font-weight: 500;
            color: #1f2937;
            text-align: center;
        }

        .instructor-role {
            font-size: 0.875rem;
            color: #4b5563;
            text-align: center;
        }

        /* Already Reviewed Indicator */
        .already-reviewed {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #10b981;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .already-reviewed-text {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.75rem;
            color: #10b981;
            font-weight: 500;
        }

        .instructor-option.reviewed {
            border-color: #d1fae5;
            background-color: rgba(16, 185, 129, 0.05);
        }

        .instructor-option.reviewed .instructor-avatar {
            border-color: #10b981;
        }

        .instructor-option.instructor-radio-selected {
            border-color: #6366f1;
            background-color: rgba(99, 102, 241, 0.05);
        }
    </style>
@endpush
