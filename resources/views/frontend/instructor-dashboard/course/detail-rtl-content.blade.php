@extends('frontend.instructor-dashboard.layouts.master')
@push('custom_meta')
    <meta name="course_id" content="{{ request('course_id') }}">
@endpush

@section('dashboard-contents')
    <div class="container mt-1">
        <div class="row">
            <div class="col-md-12">

                <div class="contact-card bg-white rounded-4 shadow-sm overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5 d-flex align-items-center justify-content-center position-relative p-3">
                            <img alt="{{ $rtl->course?->title }}" class="img-fluid rounded w-100"
                                src="{{ asset($rtl->course->thumbnail) }}"
                                onerror="this.onerror=null; this.src='{{ asset('frontend/img/bg/video_bg.jpg') }}'" />
                            @if ($rtl->course->demo_video_source)
                                <div class="play-button position-absolute top-50 start-50 translate-middle">
                                    <a href="{{ $rtl->course->demo_video_source }}" class="popup-video text-light"
                                        aria-label="{{ $rtl->course?->title }}">
                                        <i class="fas fa-play-circle fa-3x"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">

                            <div class="card-body p-4">
                                <h5 class="card-title mb-3 text-dark fw-bold">Rencana Tindak Lanjut: <br />
                                    <i class="text-dark"> {{ $rtl->title }} </i>
                                </h5>
                                <p class="text-muted"> Kursus: {{ $rtl->course->title }} </p>
                                <div class="row text-muted small mb-3">
                                    <p>{{ $rtl->description }}</p>
                                    <div class="col-md-6">
                                        <div><i class="far fa-clock"></i> Mulai: {{ $rtl->start_date }}
                                        </div>
                                        <div><i class="fas fa-users"></i> Belum Unggah: <span class="text-danger"><b>
                                                    {{ $notSubmittedCount }} </b> </span> Peserta
                                        </div>
                                        <div><i class="fas fa-users"></i> Total Peserta: &nbsp;&nbsp; <b>
                                                {{ $totalParticipants }} </b>
                                            Peserta
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div><i class="fas fa-question-circle"></i> Batas: {{ $rtl->due_date }}
                                        </div>
                                        <div><i class="fas fa-users"></i> Sudah Unggah: <span class="text-primary"><b>
                                                    {{ $submittedCount }} </b> </span> Peserta
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <!-- [ Row 2 ] start -->
            <div class="col-md-12 mt-3">
                <div class="card">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="belum-tab" data-bs-toggle="tab" data-bs-target="#belum"
                                type="button" role="tab" aria-controls="belum" aria-selected="true">Belum Mengumpulkan
                                RTL</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sudah-tab" data-bs-toggle="tab" data-bs-target="#sudah"
                                type="button" role="tab" aria-controls="sudah" aria-selected="false">Sudah
                                Mengumpulkan RTL</button>
                        </li>
                    </ul>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="belum" role="tabpanel"
                                aria-labelledby="belum-tab">
                                <div class="table-responsive affiliate-table">
                                    <table id="enrollmentsTable" class="table table-hover  mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nama Peserta</th>
                                                <th scope="col">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($enrollments as $participant)
                                                <tr>
                                                    <td>
                                                        {{ $participant->user->name }}
                                                    </td>
                                                    <td class=" f-w-600">{{ $participant->user->email }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sudah" role="tabpanel" aria-labelledby="sudah-tab">
                                <div class="table-responsive">
                                    <table id="rtlTable" class="table table-hover  mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nama Peserta</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Nilai</th>
                                                <th scope="col">Apresiator</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($submissions as $submission)
                                                <tr>
                                                    <td>
                                                        {{ $submission->participant->name }}
                                                    </td>
                                                    <td class=" f-w-600">{{ $submission->participant->email }}</td>
                                                    <td class=" f-w-600">
                                                        {{ $submission->score == null ? 'Belum Dinilai' : $submission->score }}
                                                    </td>
                                                    <td class=" f-w-600">
                                                        {{ $submission->instructor ? $submission->instructor->name : '-' }}
                                                    </td>

                                                    <td>
                                                        <a href="javascript:;" class="ms-2 preview-existing-btn"
                                                            data-url="{{ route('instructor.courses.response-rtl', $submission->id) }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#participantResponseModal" title="Preview">
                                                            <i class='fas fa-eye'></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Row 2 ] end -->
        </div>
    </div>
    {{-- <div class="modal fade" id="participantResponseModal" tabindex="-1" aria-labelledby="participantResponseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border: 1px solid #dee2e6">
                <div class="modal-header">
                    <h5 class="modal-title" id="participantResponseModalLabel">Pratinjau File PDF Saat ini</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <code>

                    </code>
                    <iframe src="https://pdfobject.com/pdf/sample.pdf" style="width: 100%; height: 100vh;"
                        frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="participantResponseModal" tabindex="-1" aria-labelledby="participantResponseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border: 1px solid #dee2e6">
                <div class="modal-header">
                    <h5 class="modal-title" id="participantResponseModalLabel">Pratinjau File PDF Saat ini</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form id="instructorFeedbackForm">
                        <div class="p-3">
                            <div class="mb-3">
                                <label for="participantResponse" class="form-label"><b>Ringkasan Peserta</b></label>
                                <p class="text-muted" id="participantResponse"></p>
                            </div>

                            <div class="mb-3">
                                <label for="participantPdfIframe" class="form-label"><b>File PDF Peserta</b></label>
                                <iframe id="participantPdfIframe" src="" style="width: 100%; height: 80vh;"
                                    frameborder="0"></iframe>

                            </div>

                            <div class="mb-3">
                                <label for="instructorResponse" class="form-label">Catatan dari Pelatih</label>
                                <textarea class="form-control" id="instructorResponse" rows="6"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="score" class="form-label">Skor</label>
                                <input type="number" class="form-control" id="score" min="0"
                                    max="100">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
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


        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.4);
            /* Transparan biar nggak terlalu gelap */
            z-index: -9999;
            /* Lebih rendah dari modal */
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            /* Lebih tinggi dari backdrop */
        }

        .modal-dialog {
            padding-top: 10px;
            max-width: 55%;
            width: 55%;
            height: 75%;
            margin: 0;
        }

        .modal-content {
            height: 100%;
            border: none;
            border-radius: 0;
            box-shadow: none;
            z-index: 1050;
            /* Lebih tinggi dari backdrop */
        }

        .modal-body {
            overflow: auto;
        }

        body.modal-open {
            overflow: hidden;
            /* Disable scroll body belakang modal */
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
            $('#enrollmentsTable').DataTable();

            // Initialize DataTable
            $('#rtlTable').DataTable({

                "columnDefs": [{
                    "orderable": false,
                    "targets": [3]
                }]
            });
        });



        $(document).on('click', '.preview-existing-btn', function() {
            // let id = $(this).data('id');

            // Ambil URL route dari attribute data-url (lihat HTML button-nya nanti)
            let url = $(this).data('url');
            alert(url);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);


                    // Tampilkan file PDF di iframe
                    $('#participantPdfIframe').attr('src',
                        '{{ url('instructor/courses/rtl-file/') }}/' + response.participant_file);

                    $('#participantResponse').text(response.participant_response || '');
                    $('#score').val(response.score || '');

                    const modal = new bootstrap.Modal(document.getElementById(
                        'participantResponseModal'));
                    modal.show();
                },
                error: function() {
                    alert('Gagal mengambil data file.');
                }
            });
        });
    </script>
@endpush
