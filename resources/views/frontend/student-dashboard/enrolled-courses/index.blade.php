@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Enrolled Courses') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="tab-content" id="courseTabContent">
                            @forelse ($enrolls as $enroll)
                                <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel"
                                    aria-labelledby="all-tab" tabindex="0">
                                    <div class="dashboard-courses-active dashboard_courses">
                                        <div class="courses__item courses__item-two shine__animate-item">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5">
                                                    <div class="courses__item-thumb courses__item-thumb-two">
                                                        <a href="{{ route('student.learning.index', $enroll->course->slug) }}"
                                                            class="shine__animate-link">
                                                            <img src="{{ $enroll->course->thumbnail_url }}"
                                                                alt="img">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-7">
                                                    <div class="courses__item-content courses__item-content-two">
                                                        <ul class="courses__item-meta list-wrap">
                                                            <li class="courses__item-tag">
                                                                <a
                                                                    href="javascript:;">{{ $enroll->course->category->translation->name }}</a>
                                                            </li>
                                                        </ul>

                                                        <h5 class="title"><a
                                                                href="{{ route('student.learning.index', $enroll->course->slug) }}">{{ $enroll->course->title }}</a>
                                                        </h5>
                                                        <div class="courses__item-content-bottom">
                                                            <div class="author-two">
                                                                <a href="javascript:;">
                                                                    @if ($enroll->course->instructor)
                                                                    <img
                                                                        src="{{ asset('uploads/website-images/frontend-avatar.png') }}"
                                                                        alt="img">{{ $enroll->course->instructor->name }}</a>
                                                                    @endif
                                                            </div>
                                                            <div class="avg-rating">
                                                                <i class="fas fa-star"></i>
                                                                {{ number_format($enroll->course->reviews()->avg('rating') ?? 0, 1) }}
                                                            </div>
                                                        </div>
                                                        @php
                                                            $courseLectureCount = App\Models\CourseChapterItem::whereHas(
                                                                'chapter',
                                                                function ($q) use ($enroll) {
                                                                    $q->where('course_id', $enroll->course->id);
                                                                },
                                                            )->count();

                                                            $courseLectureCompletedByUser = App\Models\CourseProgress::where(
                                                                'user_id',
                                                                userAuth()->id,
                                                            )
                                                                ->where('course_id', $enroll->course->id)
                                                                ->where('watched', 1)
                                                                ->count();
                                                            $courseCompletedPercent =
                                                                $courseLectureCount > 0
                                                                    ? ($courseLectureCompletedByUser /
                                                                            $courseLectureCount) *
                                                                        100
                                                                    : 0;

                                                                    $courseCompletedPercent=100;
                                                        @endphp
                                                        <div class="progress-item progress-item-two">
                                                            <h6 class="title">
                                                                {{ __('COMPLETE') }}<span>{{ number_format($courseCompletedPercent, 1) }}%</span>
                                                            </h6>
                                                            <div class="progress" role="progressbar"
                                                                aria-label="Example with label" aria-valuenow="25"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar"
                                                                    style="width: {{ number_format($courseCompletedPercent, 1) }}%">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="courses__item-bottom-two">
                                                        <ul class="list-wrap">
                                                            <li><i class="flaticon-book"></i>{{ $courseLectureCount }}
                                                            </li>
                                                            <li><i
                                                                    class="flaticon-clock"></i>{{ minutesToHours($enroll->course->duration) }}
                                                            </li>
                                                            <li><i
                                                                    class="flaticon-mortarboard"></i>{{ $enroll->course->enrollments()->count() }}
                                                            </li>
                                                            @if ($courseCompletedPercent == 100)
                                                                {{-- Pending to get signed --}}
                                                                @if ($enroll->certificate_status == 'requested')
                                                                    <li class="ms-auto">
                                                                        {{-- certificate already requested --}}
                                                                        <div>{{ __('Certificate Requested') }}</div>
                                                                    </li>
                                                                {{-- Signed certificate --}}
                                                                @elseif ($enroll->certificate_status == 'signed')
                                                                    <li class="ms-auto">
                                                                        <a class="success-button" target="_blank"
                                                                            href="{{ route('student.download-certificate', $enroll->id) }}"><i
                                                                                class="certificate fas fa-signature"
                                                                                style="color: #fafdff;"></i>
                                                                            {{ __('Download Certificate') }}</a>
                                                                    </li>
                                                                @else

                                                                @if (isAllInstructorEvaluated($enroll->course, userAuth()))
                                                                    {{-- Show certificate request button --}}
                                                                    <li class="ms-auto">
                                                                        <a class="basic-button"
                                                                            href="{{ route('student.request-sign-certificate', $enroll->id) }}"><i
                                                                                class="certificate fas fa-signature"
                                                                                style="color: #fafdff;"></i>
                                                                            {{ __('Certificate Request') }}</a>
                                                                    </li>
                                                                @else
                                                                    {{-- Show instructor evaluation button --}}
                                                                    <li class="ms-auto">
                                                                        <a class="basic-button"
                                                                            href="{{ route('student.instructorevaluation.create', ['course' => $enroll->course->id]) }}"><i
                                                                                class="certificate fas fa-signature"
                                                                                style="color: #fafdff;"></i>
                                                                            {{ __('Instructor Evaluation') }}</a>
                                                                    </li>   

                                                                @endif
                                                                
                                                                @endif


                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <h6 class="text-center">{{ __('No Course Found') }}</h6>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
