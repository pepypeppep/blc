@extends('frontend.instructor-dashboard.layouts.master')
@push('custom_meta')
    <meta name="course_id" content="{{ request('id') }}">
@endpush

@section('dashboard-contents')
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-4">
                    <div class="d-flex justify-content-between">
                        <h1 class="course-header">
                            {{ $course->title }}
                        </h1>
                        <span>
                            @foreach ($course->levels as $level)
                                <span class="badge bg-info me-1">{{ $level->level->slug }}</span>
                            @endforeach
                        </span>
                    </div>
                    <p class="course-subheader">
                        Kategori: <strong>{{ $course->category->name }}</strong>

                    </p>
                    <div class="d-flex align-items-center mb-3">

                        <div class="text-muted d-flex ml-3  align-items-center">
                            <i class="fas fa-wallet me-2 text-success"></i>
                            Anggaran: <strong>{{ 'Rp. ' . number_format($course->price, 0, ',', '.') }}</strong>
                        </div>
                        &nbsp; | &nbsp;
                        <div class="text-muted d-flex ml-3  align-items-center">
                            <i class="fas fa-users me-2 text-warning"></i>
                            Peserta: <strong>{{ $course->enrollments->count() }}</strong>
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
                <div class="card p-4">
                    <div class="position-relative">
                        <img alt="Course demo image" class="img-fluid" height="200"
                            src="{{ route('instructor.courses.detail', ['id' => $course->id]) }}" width="300" />
                        <div class="demo-overlay">
                            <button class="btn btn-light rounded-circle">
                                <i class="fas fa-play">
                                </i>
                            </button>
                            <span>
                                View Demo
                            </span>
                        </div>
                    </div>

                    <h3 class="mt-4">
                        xxx:
                    </h3>
                    <ul class="learn-list">
                        <li>
                            <i class="fas fa-calendar me-2 text-primary"></i>
                            Dibuat:
                            <strong>{{ \Carbon\Carbon::parse($course->created_at)->locale('id')->translatedFormat('j F Y H:i') }}</strong>
                        </li>
                        <li>
                            <i class="fas fa-check-circle">
                            </i>
                            Basic HTML &amp; CSS
                        </li>
                        <li>
                            <i class="fas fa-check-circle">
                            </i>
                            User Interface Design
                        </li>
                        <li>
                            <i class="fas fa-check-circle">
                            </i>
                            Create a Website
                        </li>
                        <li>
                            <i class="fas fa-check-circle">
                            </i>
                            Wireframe
                        </li>
                        <li>
                            <i class="fas fa-check-circle">
                            </i>
                            Create Responsive Website
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
        .course-header {
            font-size: 24px;
            font-weight: bold;
        }

        .course-subheader {
            font-size: 16px;
            color: #6c757d;
        }

        .rating {
            color: #ffc107;
        }

        .nav-tabs .nav-link.active {
            color: #dc3545;
            border-color: #dc3545;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
        }

        .save-badge {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .learn-list {
            list-style: none;
            padding: 0;
        }

        .learn-list li {
            margin-bottom: 10px;
        }

        .learn-list li i {
            color: #28a745;
            margin-right: 10px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card img {
            border-radius: 10px;
        }

        .position-absolute {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .btn-light {
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
        }

        .demo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .demo-overlay span {
            color: white;
            font-size: 18px;
            margin-left: 10px;
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
