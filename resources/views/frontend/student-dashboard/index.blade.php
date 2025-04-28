@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap dashboard__content-wrap-two mb-60">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Dashboard') }}</h4>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="dashboard__counter-item">
                    <div class="icon">
                        <i class="flaticon-mortarboard"></i>
                    </div>
                    <div class="content">
                        <span class="count odometer" data-count="{{ $totalEnrolledCourses }}"></span>
                        <p>{{ __('ENROLLED COURSES') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="dashboard__counter-item">
                    <div class="icon">
                        <img src="{{ asset('uploads/website-images/quiz.svg') }}">
                    </div>
                    <div class="content">
                        <span class="count odometer" data-count="{{ $totalQuizAttempts }}"></span>
                        <p>{{ __('QUIZ ATTEMPTS') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="dashboard__counter-item">
                    <div class="icon">
                        <img src="{{ asset('uploads/website-images/reviews.svg') }}">
                    </div>
                    <div class="content">
                        <span class="count odometer" data-count="{{ $totalReviews }}"></span>
                        <p>{{ __('YOUR TOTAL REVIEWS') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
