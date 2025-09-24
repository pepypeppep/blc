@extends('frontend.pages.learning-player.master')
@section('meta_title', $course->title . ' || ' . $setting->app_name)

@section('contents')
    <section class="wsus__course_video" id="main-lesson-section">
        <div class="col-12">
            <div class="wsus__course_header">
                <a href="{{ route('student.dashboard') }}"><i class="fas fa-angle-left"></i>
                    {{ __('Go back to dashboard') }}</a>
                <p>{{ __('Your Progress') }}: {{ $courseLectureCompletedByUser }} {{ __('of') }}
                    {{ $courseLectureCount }} ({{ number_format($courseCompletedPercent) }}%)</p>

                <div class="wsus__course_header_btn">
                    <i class="fas fa-stream"></i>
                </div>
            </div>
        </div>

        <div class="wsus__course_video_player">

            {{-- Player --}}
            <div class="video-payer position-relative">
                <div class="player-placeholder">
                    <div class="preloader-two player">
                        <div class="loader-icon-two player">
                            <img src="{{ route('get.section.asset', [1, 'preloader']) }}?module=general" alt="Preloader">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Panel --}}
            @include('frontend.pages.learning-player.bottom-panel')

        </div>


        <div class="wsus__course_sidebar">
            <div class="wsus__course_sidebar_btn">
                <i class="fas fa-times"></i>
            </div>
            <h2 class="video_heading">{{ __('Course Content') }}</h2>
            <div class="accordion-item">
                <div id="collapse-x" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body course-content pt-2 bg-white">
                        <div id="tosSectionTrigger" class="form-check" data-bs-toggle="modal"
                            data-bs-target="#termOfServiceModal">
                            <input @checked($enrollment->tos_status == 'accepted') class="form-check-input lesson-completed-checkbox"
                                type="checkbox">
                            <label class="form-check-label">
                                {{ __('Term of Service') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion" id="accordionExample">
                @foreach ($course->chapters as $chapter)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $chapter->id }}" aria-expanded="false"
                                aria-controls="collapse-{{ $chapter->id }}"
                                {{ $enrollment->tos_status != 'accepted' || $enrollment->tos_status == null ? 'disabled' : '' }}>
                                <b>{{ $chapter->title }}</b>
                                <span></span>
                            </button>
                        </h2>
                        <div id="collapse-{{ $chapter->id }}"
                            class="accordion-collapse collapse {{ $currentProgress?->chapter_id == $chapter->id ? 'show' : '' }}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body course-content">
                                @foreach ($chapter->chapterItems as $chapterItem)
                                    @if ($chapterItem->type == 'lesson' || $chapterItem->type == 'live')
                                        <div class="form-check cursor-pointer lesson-item {{ $chapterItem->lesson->id == $currentProgress?->lesson_id ? 'item-active' : '' }}"
                                            data-lesson-id="{{ $chapterItem->lesson->id }}"
                                            data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $course->id }}"
                                            data-type="{{ $chapterItem->type }}"
                                            data-duration="{{ $chapterItem->lesson->duration }}">
                                            <input @checked(in_array($chapterItem->lesson->id, $alreadyWatchedLectures))
                                                class="form-check-input lesson-completed-checkbox" type="checkbox"
                                                data-lesson-id="{{ $chapterItem->lesson->id }}" value="1"
                                                data-type="lesson" onclick="return false;">
                                            <label class="form-check-label">
                                                {{ $chapterItem->lesson->title }}
                                                <span>
                                                    @if ($chapterItem->type == 'live')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            style="font-size: 18px; color: #808080;" class="bi bi-cast"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="m7.646 9.354-3.792 3.792a.5.5 0 0 0 .353.854h7.586a.5.5 0 0 0 .354-.854L8.354 9.354a.5.5 0 0 0-.708 0" />
                                                            <path
                                                                d="M11.414 11H14.5a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.5-.5h-13a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5h3.086l-1 1H1.5A1.5 1.5 0 0 1 0 10.5v-7A1.5 1.5 0 0 1 1.5 2h13A1.5 1.5 0 0 1 16 3.5v7a1.5 1.5 0 0 1-1.5 1.5h-2.086z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor"
                                                            style="font-size: 18px; color: #808080;" class="bi bi-play-btn"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z" />
                                                            <path
                                                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                                                        </svg>
                                                    @endif
                                                    {{ $chapterItem->lesson->duration ? minutesToHours($chapterItem->lesson->duration) : '--.--' }}
                                                </span>
                                            </label>
                                        </div>
                                    @elseif ($chapterItem->type == 'document')
                                        <div class="form-check cursor-pointer lesson-item {{ $chapterItem->lesson->id == $currentProgress?->lesson_id ? 'item-active' : '' }}"
                                            data-lesson-id="{{ $chapterItem->lesson->id }}"
                                            data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $course->id }}"
                                            data-type="document">
                                            <input @checked(in_array($chapterItem->lesson->id, $alreadyWatchedLectures))
                                                class="form-check-input lesson-completed-checkbox" type="checkbox"
                                                data-lesson-id="{{ $chapterItem->lesson->id }}" value="1"
                                                data-type="document">
                                            <label class="form-check-label">
                                                {{ $chapterItem->lesson->title }}
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" style="font-size: 18px; color: #808080;"
                                                        class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z" />
                                                        <path fill-rule="evenodd"
                                                            d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </div>
                                    @elseif ($chapterItem->type == 'quiz')
                                        <div class="form-check cursor-pointer lesson-item"
                                            data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $course->id }}"
                                            data-lesson-id="{{ $chapterItem->quiz->id }}" data-type="quiz">
                                            <input @checked(in_array($chapterItem->quiz->id, $alreadyCompletedQuiz))
                                                class="form-check-input lesson-completed-checkbox" type="checkbox"
                                                data-lesson-id="{{ $chapterItem->quiz->id }}" value="1"
                                                data-type="quiz">
                                            <label class="form-check-label">
                                                {{ $chapterItem->quiz->title }}
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" style="font-size: 18px; color: #808080;"
                                                        class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </div>
                                    @elseif ($chapterItem->type == 'rtl')
                                        <div class="form-check cursor-pointer lesson-item"
                                            data-chapter-id="{{ $chapter->id }}" data-course-id="{{ $course->id }}"
                                            data-lesson-id="{{ $chapterItem->rtl->id }}" data-type="rtl">
                                            <input @checked(in_array($chapterItem->rtl->id, $alreadyCompletedRtl))
                                                class="form-check-input lesson-completed-checkbox" type="checkbox"
                                                data-lesson-id="{{ $chapterItem->rtl->id }}" value="1"
                                                data-type="rtl">
                                            <label class="form-check-label">
                                                {{ $chapterItem->rtl->title }}
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" style="font-size: 18px; color: #808080;"
                                                        class="bi bi-file-earmark-richtext" viewBox="0 0 16 16">
                                                        <path
                                                            d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z" />
                                                        <path
                                                            d="M4.5 12.5A.5.5 0 0 1 5 12h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m1.639-3.708 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V8.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V8s1.54-1.274 1.639-1.208M6.25 6a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <div class="modal fade" id="termOfServiceModal" tabindex="-1" aria-labelledby="termOfServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="term-of-service-content p-4 text-dark" style="max-height: 70vh; overflow-y: auto;">
                        <div style="background: #f7f7f7;" class="p-3">
                            {!! clean(@$courseTos->description) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between px-4 pb-4 pt-3"
                    style="border-top: 1px solid #dee2e6;">
                    @if ($enrollment->tos_status != 'accepted')
                        <a href="{{ route('student.enrolled-courses') }}" class="btn text-white"
                            style="background: #dc3545;">{{ __('Tolak') }}</a>
                        <form action="{{ route('student.learning.accept-tos', $course->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="acceptTermsBtn" data-bs-dismiss="modal"
                                title="Baca hingga akhir agar tombol persetujuan aktif"
                                disabled>{{ __('Setuju') }}</button>
                        </form>
                    @else
                        <span></span>
                        <h3 style="color: #5751e1;">Anda telah menyetujui syarat dan ketentuan</h3>
                        <span></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center vh-100" id="warning" style="display: none;">
        <div class="text-center">
            <h2 class="mb-3">⚠️ Developer Tools Detected</h2>
            <img src="{{ asset('frontend/img/police.png') }}" alt="" class="img-fluid w-25">
            <p>Halaman ini berisi konten yang dilindungi. Silakan tutup Developer Tools untuk melanjutkan.</p>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var preloader_path = "{{ route('get.section.asset', [1, 'preloader']) }}?module=general";
        var watermark =
            "{{ property_exists($setting, 'watermark_img') ? route('get.section.asset', [1, 'watermark_img']) . '?module=general' : '' }}";
        window.Y17JxWAOWqgYx999V6v4hpTZyFaxbF = @json(session('access_token'));
    </script>
    <script src="{{ asset('frontend/js/videojs-watermark.min.js') }}"></script>
    <script src="{{ asset('frontend/js/default/learning-player.js') }}?v={{ $setting?->version }}"></script>
    <script src="{{ asset('frontend/js/default/quiz-page.js') }}?v={{ $setting?->version }}"></script>
    <script src="{{ asset('frontend/js/default/qna.js') }}?v={{ $setting?->version }}"></script>
    <script src="{{ asset('frontend/js/pdf.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jszip.min.js') }}"></script>
    <script src="{{ asset('frontend/js/docx-preview.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            // reset quiz timer
            resetCountdown();
            if ("{{ $enrollment->tos_status }}" == 'accepted') {
                // auto click on current lesson
                var lessonId = "{{ request('lesson') }}";
                var type = "{{ request('type') }}";
                var currentLessonSelector = $(
                    '.lesson-item[data-lesson-id="{{ $currentProgress?->lesson_id }}"][data-type="{{ $currentProgress?->type }}"]'
                );
                var targetLessonSelector = $(`.lesson-item[data-lesson-id="${lessonId}"][data-type="${type}"]`);

                if (targetLessonSelector.length) {
                    targetLessonSelector.trigger('click');
                } else if (currentLessonSelector.length) {
                    currentLessonSelector.trigger('click');
                } else {
                    $('.lesson-item:first').trigger('click');
                }
            } else {
                $('#tosSectionTrigger').trigger('click');
            }

            // term of service
            $('#termOfServiceModal').on('shown.bs.modal', function() {
                const $content = $('.term-of-service-content');
                const $acceptBtn = $('#acceptTermsBtn');

                // Smooth scroll to top when modal is shown
                $content.stop().animate({
                    scrollTop: 0
                }, 400);

                // Reset accept button state when modal is shown
                $acceptBtn.prop('disabled', true);

                $content.on('scroll', function() {
                    // Check if user has scrolled to the bottom
                    const isBottom = $(this).scrollTop() + $(this).innerHeight() >= $(this)[0]
                        .scrollHeight - 10;

                    if (isBottom) {
                        $acceptBtn.prop('disabled', false);
                    }
                });
            });

            // Also check on initial load in case content is shorter than container
            $('#termOfServiceModal').on('scroll', '.term-of-service-content', function() {
                const $content = $(this);
                const $acceptBtn = $('#acceptTermsBtn');
                const isBottom = $content.scrollTop() + $content.innerHeight() >= $content[0].scrollHeight -
                    10;

                if (isBottom) {
                    $acceptBtn.prop('disabled', false);
                }
            });
        });

        function completeLesson(lessonId) {
            // let lessonId = $(this).attr("data-lesson-id");

            let input = $(`input[data-lesson-id="${lessonId}"]`);
            let type = input.attr("data-type");
            input.prop("checked", true);
            let checked = input.is(":checked") ? 1 : 0;
            $.ajax({
                method: "POST",
                url: base_url + "/student/learning/make-lesson-complete",
                data: {
                    _token: csrf_token,
                    lessonId: lessonId,
                    status: checked,
                    type: type,
                },
                success: function(data) {
                    if (data.status == "success") {
                        toastr.success(data.message);
                    } else if (data.status == "error") {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                    });
                },
            });
        }

        (function() {
            let devtoolsOpen = false;
            const threshold = 160;

            const checkDevTools = () => {
                const widthThreshold = window.outerWidth - window.innerWidth > threshold;
                const heightThreshold = window.outerHeight - window.innerHeight > threshold;
                if (widthThreshold || heightThreshold) {
                    if (!devtoolsOpen) {
                        devtoolsOpen = true;
                        document.getElementById('main-lesson-section').style.display = 'none';
                        document.getElementById('warning').style.display = 'block';
                    }
                } else {
                    devtoolsOpen = false;
                    document.getElementById('warning').style.display = 'none';
                    document.getElementById('main-lesson-section').style.display = 'block';
                }
            };

            // check repeatedly
            setInterval(checkDevTools, 500);
        })();
    </script>
    <script src="{{ asset('frontend/js/custom-tinymce.js') }}"></script>
@endpush
@push('styles')
    @php
        $maxWidth = $setting?->max_width ?? '300';
        $opacity = $setting?->opacity ?? '0.7';
        $position = $setting?->position ?? 'top_right';
        $watermarkStatus = property_exists($setting, 'watermark_status') ? $setting->watermark_status : 'inactive';

        $positionCSS = '';
        switch ($position) {
            case 'top_left':
                $positionCSS = 'top: 0; left: 0;';
                break;
            case 'bottom_right':
                $positionCSS = 'bottom: 44px; right: 0;';
                break;
            case 'bottom_left':
                $positionCSS = 'bottom: 44px; left: 0;';
                break;
            default:
                $positionCSS = 'top: 0; right: 0;';
        }

        $display = $watermarkStatus === 'active' ? 'display: inline;' : '';
    @endphp

    <style>
        .vjs-watermark {
            max-width: {{ $maxWidth }}px;
            opacity: {{ $opacity }} !important;
            {!! $positionCSS !!} {!! $display !!}
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .video-error-container {
            position: relative;
            width: 100%;
            height: 80vh;
            background: black;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        .video-error-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .video-error-message {
            position: relative;
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 8px;
            max-width: 80%;
        }

        .video-error-message p {
            margin: 5px 0;
        }

        /* Hide all video.js and YouTube elements */
        .video-error-mode .video-js,
        .video-error-mode .vjs-tech,
        .video-error-mode iframe {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
    </style>
@endpush
