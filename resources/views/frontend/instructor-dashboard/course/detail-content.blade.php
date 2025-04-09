@extends('frontend.instructor-dashboard.layouts.master')
@push('custom_meta')
    <meta name="course_id" content="{{ request('id') }}">
@endpush

@section('dashboard-contents')
    <div class="container mt-1">
        <div class="row">
            <div class="col-md-12">

                <div class="contact-card bg-white rounded-4 shadow-sm overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5 d-flex align-items-center justify-content-center position-relative p-3">
                            <img alt="{{ $course?->title }}" class="img-fluid rounded w-100"
                                src="{{ asset($course->thumbnail) }}"
                                onerror="this.onerror=null; this.src='{{ asset('frontend/img/bg/video_bg.jpg') }}'" />
                            @if ($course->demo_video_source)
                                <div class="play-button position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ $course->demo_video_source }}" class="popup-video text-light"
                                        aria-label="{{ $course?->title }}">
                                        <i class="fas fa-play-circle fa-3x"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="card-body p-4">
                                <h3 class="card-title mb-3 text-dark fw-bold">{{ $course->title }}</h3>
                                <div class="row text-muted small mb-3">
                                    <div class="col-md-6">
                                        <div><i class="far fa-clock"></i> Durasi Pelatihan: {{ $course->duration }} Menit
                                        </div>
                                        <div><i class="fas fa-wallet text-danger"></i> Jam Pelajaran (JP):
                                            <strong>{{ $course->price }}</strong>
                                        </div>
                                        <div><i class="fas fa-users text-success"></i>
                                            {{ $course->enrollments->count() == 0 ? 'Belum ada Peserta' : 'Terdapat ' . $course->enrollments->count() . ' Peserta' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><i class="fas fa-question-circle"></i> Sertifikat:
                                            {{ $course->certificate ? 'Tersedia' : 'Tidak Tersedia' }}</div>
                                        <div>
                                            @if ($course->qna == 1)
                                                <i class="fas fa-check-circle text-success"></i> Tanya Jawab
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i> Tanya Jawab
                                            @endif
                                        </div>
                                        <div><i class="fas fa-users"></i>
                                            {{ $course->capacity ? $course->capacity . ' Peserta' : 'Tanpa Batas' }}</div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 d-flex justify-content-between text-muted small">
                                    <div><i class="fas fa-tags text-secondary"></i>
                                        <strong>{{ ucwords($course->category->name) }}</strong>
                                    </div>
                                    <div><i class="fas fa-calendar text-secondary"></i>
                                        <strong>{{ \Carbon\Carbon::parse($course->created_at)->locale('id')->translatedFormat('j F Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 border-0">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#overview-tab-pane" type="button" role="tab"
                                aria-controls="overview-tab-pane" aria-selected="true">{{ __('Overview') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="curriculum-tab" data-bs-toggle="tab"
                                data-bs-target="#curriculum-tab-pane" type="button" role="tab"
                                aria-controls="curriculum-tab-pane" aria-selected="false">{{ __('Curriculum') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="participants-tab" data-bs-toggle="tab"
                                data-bs-target="#participants-tab-pane" type="button" role="tab"
                                aria-controls="participants-tab-pane" aria-selected="false">{{ __('Peserta') }}
                                <span class="badge rounded-pill bg-info text-white">
                                    {{ $course->enrollments->count() == 0 ? 0 : $course->enrollments->count() }} 
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rtl-tab" data-bs-toggle="tab" data-bs-target="#rtl-tab-pane"
                                type="button" role="tab" aria-controls="rtl-tab-pane"
                                aria-selected="false">{{ __('Rencana Tindak Lanjut') }}</button>
                        </li>

                    </ul>
                    <div class="tab-content " id="myTabContent">
                        <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel"
                            aria-labelledby="overview-tab" tabindex="0">
                            <div class="courses__overview-wrap border-0" style="border-radius: 0%">
                                <h3 class="title">{{ __('Course Description') }}</h3>
                                {!! clean($course->description) !!}

                            </div>
                        </div>
                        <div class="tab-pane fade" id="curriculum-tab-pane" role="tabpanel" aria-labelledby="curriculum-tab"
                            tabindex="0">
                            <div class="courses__curriculum-wrap border-0" style="border-radius: 0%">
                                <h3 class="title">{{ __('Course Curriculum') }}</h3>
                                <p></p>
                                <div class="accordion" id="accordionExample">
                                    @foreach ($course->chapters as $chapter)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $chapter->id }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $chapter->id }}" aria-expanded="false"
                                                    aria-controls="collapse{{ $chapter->id }}">
                                                    {{ $loop->iteration }}. {{ $chapter?->title }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $chapter->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $chapter->id }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul class="list-wrap">
                                                        @foreach ($chapter->chapterItems as $chapterItem)
                                                            @if ($chapterItem?->type == 'lesson')
                                                                @if ($chapterItem?->lesson?->is_free == 1)
                                                                    @if ($chapterItem?->lesson?->file_type == 'video')
                                                                        @if ($chapterItem?->lesson->storage == 'google_drive')
                                                                            <li class="course-item open-item">
                                                                                <a href="javascript:;"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#videoModal"
                                                                                    data-bs-video="https://drive.google.com/file/d/{{ extractGoogleDriveVideoId($chapterItem?->lesson->file_path) }}/preview"
                                                                                    class="course-item-link">
                                                                                    <span
                                                                                        class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                                    <div class="course-item-meta">
                                                                                        <span
                                                                                            class="item-meta duration">{{ minutesToHours($chapterItem?->lesson?->duration) }}</span>
                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                        @else
                                                                            {{-- <li class="course-item open-item">
                                                                                    <a href="@if (!in_array($chapterItem?->lesson->storage, ['wasabi', 'aws'])) {{ $chapterItem?->lesson->file_path }} @else {{ Storage::disk($chapterItem?->lesson->storage)->temporaryUrl($chapterItem?->lesson->file_path, now()->addHours(1)) }} @endif"
                                                                                        class="course-item-link popup-video">
                                                                                        <span
                                                                                            class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                                        <div class="course-item-meta">
                                                                                            <span
                                                                                                class="item-meta duration">{{ minutesToHours($chapterItem?->lesson?->duration) }}</span>
                                                                                        </div>
                                                                                    </a>
                                                                                </li> --}}
                                                                            <li class="course-item">
                                                                                <a href="javascript:;"
                                                                                    class="course-item-link">
                                                                                    <span
                                                                                        class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                                    <div class="course-item-meta">
                                                                                        <span class="item-meta duration">
                                                                                            --.-- </span>

                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @else
                                                                        <li class="course-item">
                                                                            <a href="javascript:;"
                                                                                class="course-item-link">
                                                                                <span
                                                                                    class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                                <div class="course-item-meta">
                                                                                    <span class="item-meta duration">
                                                                                        --.-- </span>

                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @else
                                                                    <li class="course-item">
                                                                        <a href="javascript:;" class="course-item-link">
                                                                            <span
                                                                                class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                            <div class="course-item-meta">
                                                                                <span
                                                                                    class="item-meta duration">{{ minutesToHours($chapterItem?->lesson?->duration) }}</span>

                                                                            </div>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @elseif($chapterItem?->type == 'document')
                                                                <li class="course-item">
                                                                    <a href="javascript:;" class="course-item-link">
                                                                        <span
                                                                            class="item-name">{{ $chapterItem?->lesson?->title }}</span>
                                                                        <div class="course-item-meta">
                                                                            <span
                                                                                class="item-meta duration">{{ minutesToHours($chapterItem?->lesson?->duration) }}</span>

                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            @elseif ($chapterItem->type == 'quiz')
                                                                <li class="course-item">
                                                                    <a href="javascript:;" class="course-item-link">
                                                                        <span
                                                                            class="item-name">{{ $chapterItem?->quiz?->title }}</span>
                                                                        <div class="course-item-meta">
                                                                            <span
                                                                                class="item-meta duration">{{ minutesToHours($chapterItem?->lesson?->duration) }}</span>

                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="participants-tab-pane" role="tabpanel"
                            aria-labelledby="participants-tab" tabindex="0">
                            <div class="courses__overview-wrap border-0" style="border-radius: 0%">
                                <h3 class="title">{{ __('Daftar Peserta') }}</h3>
                                <table id="enrollmentsTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="rtl-tab-pane" role="tabpanel" aria-labelledby="rtl-tab"
                            tabindex="0">
                            <div class="courses__overview-wrap border-0" style="border-radius: 0%">
                                <h3 class="title">{{ __('Daftar Rencana Tindak Lanjut') }}</h3>

                                <table id="rtlTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th> Materi</th>
                                            <th> Rencana Tindak Lanjut</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection



@push('styles')
    <!-- datatables -->
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .contact-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .social-icon {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .social-icon:hover {
            transform: scale(1.2);
        }

        .play-button a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background-color: rgba(255, 0, 0, 0.8);
            /* Warna merah transparan */
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
        }

        .play-button a i {
            font-size: 2rem;
            transition: transform 0.3s ease-in-out;
        }

        .play-button a:hover {
            background-color: rgba(255, 0, 0, 1);
            /* Warna merah solid saat hover */
            transform: scale(1.1);
        }

        .play-button a:hover i {
            transform: scale(1.2);
        }


        .image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .image-container img {
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            height: auto;
        }

        .title-background {
            background-color: #e9ecef;
            padding: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('global/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('frontend/js/default/courses.js') }}?v={{ $setting?->version }}"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#enrollmentsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('instructor.courses.detail', ['id' => $course->id]) }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ]
            });

            $('#rtlTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('instructor.courses.list-rtl', ['id' => $course->id]) }}",
                columns: [{
                        data: 'chapter.title',
                        name: 'chapter.title'
                    },
                    {
                        data: 'follow_up_action.title',
                        name: 'follow_up_action.title'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
