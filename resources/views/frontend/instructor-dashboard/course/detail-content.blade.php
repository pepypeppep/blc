@extends('frontend.instructor-dashboard.layouts.master')
@push('custom_meta')
    <meta name="course_id" content="{{ request('id') }}">
@endpush

@section('dashboard-contents')
    <div class="container mt-1">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-4">
                    <div class="d-flex justify-content-between">
                        <h1 class="course-header">
                            {{ ucwords($course->title) }}
                        </h1>
                        <span>
                            @foreach ($course->levels as $level)
                                <span class="badge bg-info me-1">{{ $level->level->slug }}</span>
                            @endforeach
                        </span>
                    </div>
                    <p class="course-subheader">
                        Kategori: <strong>{{ ucwords($course->category->name) }}</strong>

                    </p>
                    <div class="d-flex align-items-center mb-3">

                        <div class="text-muted d-flex ml-3  align-items-center">
                            <i class="fas fa-calendar me-2 text-secondary"></i>
                            Dibuat:
                            <small><strong>{{ \Carbon\Carbon::parse($course->created_at)->locale('id')->translatedFormat('j F Y') }}</strong></small>
                        </div>
                        &nbsp;|&nbsp;
                        <div class="text-muted d-flex ml-3  align-items-center">
                            <i class="fas fa-wallet me-2 text-danger"></i>
                            Anggaran:<small> <strong>{{ 'Rp.' . number_format($course->price, 0, ',', '.') }}</strong>
                            </small>
                        </div>
                        &nbsp;|&nbsp;
                        <div class="text-muted d-flex ml-3  align-items-center">
                            <i class="fas fa-users me-2 text-warning"></i>
                            Peserta:<small> <strong>{{ $course->enrollments->count() }}</strong> </small>
                        </div>
                    </div>
                    {{-- <div class="d-flex align-items-center mb-3">
                        <img alt="Instructor's profile picture" class="rounded-circle me-2" height="50"
                            src="https://storage.googleapis.com/a1aa/image/8mIxjF3OYxb2D9G0kAu3iJedVQ37ZOYb4UBPEw9QUf7aQ8GUA.jpg"
                            width="50" />
                        <span>
                            Ms. Samantha William
                        </span>
                    </div> --}}

                </div>
                <div class="card mt-3 shadow-sm border-0">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="courseTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                    type="button" role="tab">Rangkuman</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="participants-tab" data-bs-toggle="tab"
                                    data-bs-target="#participants" type="button" role="tab">Peserta</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <!-- Course Information Tab -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <h5>Deskripsi</h5>
                                <p>{!! $course->description !!}</p>

                                <h5 class="mt-4">Konten Pelatihan</h5>
                                <div class="accordion" id="courseAccordion">
                                    @foreach ($course->chapters as $chapter)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $chapter->id }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $chapter->id }}"
                                                    aria-expanded="false">{{ $loop->iteration }}.
                                                    {{ $chapter->title }}</button>
                                            </h2>
                                            <div id="collapse{{ $chapter->id }}" class="accordion-collapse collapse"
                                                data-bs-parent="#courseAccordion">
                                                <div class="accordion-body">
                                                    <ul class="list-group">
                                                        @foreach ($chapter->chapterItems as $chapterItem)
                                                            <li class="list-group-item">
                                                                <span>{{ $chapterItem->title }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Participants Tab -->
                            <div class="tab-pane fade" id="participants" role="tabpanel">
                                <table id="enrollmentsTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($course->enrollments as $enrollment)
                                            <tr>
                                                <td>{{ $enrollment->user->name }}</td>
                                                <td>{{ $enrollment->user->email }}</td>
                                                <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="image-container">
                        <img alt="Two people practicing Taekwondo with neon lights in the background" class="card-img-top"
                            src="{{ asset($course->thumbnail) }}"
                            onerror="this.onerror=null; this.src='{{ asset('frontend/img/bg/video_bg.jpg') }}'" />
                        <div class="play-button">
                            @if ($course->demo_video_source)
                                <a href="{{ $course->demo_video_source }}" class="popup-video"
                                    style="color: #dfe4ea; hover: color: #a4b0be;" aria-label="{{ $course?->title }}">
                                    <i class="fas fa-play-circle">
                                    </i>
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-2">
                            @foreach ($course->levels as $level)
                                <span class="badge bg-info me-1" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                    {{ $level->level->slug }}
                                </span>
                            @endforeach
                        </div>
                        <ul class="list-unstyled">

                            <li style="padding: 5px; margin-bottom: 5px;">
                                <i class="far fa-clock me-2"></i>Durasi Pelatihan {{ $course->duration }} Menit
                            </li>

                            <li style="padding: 5px; margin-bottom: 5px;">
                                <i class="fas fa-users me-2"></i>
                                {{ $course->capacity ? $course->capacity . ' Peserta' : 'Tanpa Batas' }}
                            </li>
                            <li style="padding: 5px; margin-bottom: 5px;">
                                @if ($course->qna == 1)
                                    <i class="fas fa-check-circle text-success"></i> Tanya Jawab
                                @else
                                    <i class="fas fa-times-circle text-danger"></i> Tanya Jawab
                                @endif
                            </li>
                            <li style="padding: 5px; margin-bottom: 5px;">
                                <i class="fas fa-question-circle me-2"></i> Sertifikat Penyelesaian
                                {{ $course->certificate }}
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    @endsection



    @push('styles')
        <!-- datatables -->
        <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <style>
            .accordion-button {
                background-color: #f8f9fa;
                font-weight: bold;
            }

            .accordion-button:not(.collapsed) {
                color: #28a745;
            }

            .accordion-button::after {
                color: #28a745;
            }

            .accordion-item {
                border: none;
            }

            .play-button {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 3rem;
                color: white;
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
            });
        </script>
    @endpush
