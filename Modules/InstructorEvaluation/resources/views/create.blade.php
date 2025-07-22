@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
<div class="container-instructor-review">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Submit Your Review</h2>
    <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ $course->title }}</h3>

    <form action="{{ route('student.instructorevaluation.store') }}" method="POST">
        @csrf

        {{-- input hidden --}}
        <input type="hidden" name="instructor_id" value="{{ optional($selectedInstructor)->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
      

        <div class="form-group">
            <label>Select Instructor to Review</label>
            <div class="instructor-selection">

                     
                @foreach ($instructors as $instructor)
            
                <a href="{{ route('student.instructorevaluation.create', ['course' => $course->id, 'instructor' => $instructor->id]) }}" for="instructor2" class="instructor-option @if($instructorEvaluations->pluck('instructor_id')->contains($instructor->id)) reviewed @endif 
                    @if (optional($selectedInstructor)->id == $instructor->id) instructor-radio-selected @endif">
                    @if($instructorEvaluations->pluck('instructor_id')->contains($instructor->id)) 
                    <div class="already-reviewed">✓</div>
                    @endif
                   
                    <img src="{{ asset("/frontend/img/frontend-avatar.png") }}" alt="David Chen" class="instructor-avatar">
                    <div class="instructor-name">{{ $instructor->name }}</div>
                    {{-- <div class="instructor-role">Backend Developer</div> --}}
                    {{-- <div class="already-reviewed-text">Already Reviewed</div> --}}
                </a> 
                
                @endforeach
            
               
            </div>
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" 
                @if (optional($selectedInstructorEvaluation)->rating == 5) checked @endif
                /><label for="star5" title="5 stars"></label>
                <input type="radio" id="star4" name="rating" value="4" 
                @if (optional($selectedInstructorEvaluation)->rating == 4) checked @endif
                /><label for="star4" title="4 stars"></label>
                <input type="radio" id="star3" name="rating" value="3" 
                @if (optional($selectedInstructorEvaluation)->rating == 3) checked @endif
                /><label for="star3" title="3 stars"></label>
                <input type="radio" id="star2" name="rating" value="2" 
                @if (optional($selectedInstructorEvaluation)->rating == 2) checked @endif
                /><label for="star2" title="2 stars"></label>
                <input type="radio" id="star1" name="rating" value="1" 
                @if (optional($selectedInstructorEvaluation)->rating == 1) checked @endif
                /><label for="star1" title="1 star"></label>
            </div>
        </div>

        <div class="form-group">
            <label for="feedback">Review</label>
            <textarea id="feedback" name="feedback" placeholder="Write your review here" required>@if($selectedInstructorEvaluation){{$selectedInstructorEvaluation->feedback}}@endif</textarea>
        </div>

        <button type="submit">Submit Review</button>
    </form>
</div>
@endsection
@push('styles')
<style>

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
    .rating label:hover ~ label::before,
    .rating input:checked ~ label::before {
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
        position: relative; /* For positioning the checkmark */
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