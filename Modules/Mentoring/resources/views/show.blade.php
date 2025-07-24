@extends('admin.master_layout')
@section('title')
    <title>{{ __('Mentoring Detail') }}</title>
@endsection

@php
    use Modules\Mentoring\app\Models\Mentoring;
    $mentee = $mentoring->mentee;
    $mentor = $mentoring->mentor;
@endphp

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Detail Tema Mentoring') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ $mentoring->title }}</h4>
                                <div class="badge badge-{{ $mentoring->stat['color'] }}">{{ $mentoring->stat['label'] }}
                                 </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Main Issue') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $mentoring->description !!}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Purpose') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $mentoring->purpose !!}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                Pelaksanaan Pertemuan ({{ count($mentoring->mentoringSessions) }})
                                            </p>

                                            <div class="accordion card mb-2" id="mentoringAccordion">
                                                @foreach($mentoring->mentoringSessions as $index => $session)
                                                    <div class="accordion-item border border-secondary-subtle mb-2">
                                                        <h2 class="accordion-header">
                                                            <div class="accordion_header_content d-flex justify-content-between align-items-center">
                                                                <button
                                                                    class="accordion-button course-quiz-btn collapsed"
                                                                    type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#mentoring-collapse{{ $index }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="mentoring-collapse{{ $index }}"
                                                                    style="width: 100%;">
                                                                    <div class="text-start">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <span class="fw-semibold text-dark" style="font-size: 0.95rem;">
                                                                                Pertemuan {{ $index + 1 }}
                                                                            </span>
                                                                            @if (!empty($session->activity))
                                                                                <span class="badge bg-info text-white ml-2 px-2 py-1" style="font-size: 0.7rem; font-weight: 700;">
                                                                                    Terisi
                                                                                </span>
                                                                            @endif
                                                                            @if (!empty($session->mentoring_note))
                                                                                <span class="badge bg-primary text-white ml-2 px-2 py-1" style="font-size: 0.7rem; font-weight: 700;">
                                                                                    Direviu
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-muted text-left" style="font-size: 0.85rem;">
                                                                            {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </h2>
                                                        <div id="mentoring-collapse{{ $index }}"
                                                            class="accordion-collapse collapse"
                                                            data-bs-parent="#mentoringAccordion">
                                                            @if (!empty($session->activity))
                                                                <div class="accordion-body">
                                                                    <strong class="d-block">Deskripsi Kegiatan:</strong>
                                                                    <div class="text-body">{!! $session->activity ?: '<em>Tidak ada deskripsi kegiatan.</em>' !!}</div>

                                                                    <div class="mb-2">
                                                                        <strong class="d-block">Hambatan:</strong>
                                                                        <div class="text-body">{!! $session->description ?: '<em>Tidak ada hambatan dicatat.</em>' !!}</div>
                                                                    </div>

                                                                    <div class="mb-2">
                                                                        <strong class="d-block">Dokumentasi:</strong>
                                                                        @if ($session->image && Storage::disk('private')->exists($session->image))
                                                                            <a href="{{ route('admin.mentoring.view.img', $session->id) }}" target="_blank">
                                                                                <img src="{{ route('admin.mentoring.view.img', $session->id) }}" alt="img" class="img-thumbnail mt-2" style="max-width: 200px;">
                                                                            </a>
                                                                        @else
                                                                            <p class="text-muted"><em>Belum ada dokumentasi gambar.</em></p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="mb-2">
                                                                        @if ($session->status == 'reviewed')
                                                                            <form>
                                                                                <div class="form-group mt-4">
                                                                                    <label for="mentoring_date">{{ __('Mentoring Date') }}</label>
                                                                                    <input type="text" name="mentoring_date" class="form-control datetimepicker"
                                                                                        value="{{ $session->mentoring_date ?? '' }}" disabled>
                                                                                </div>
                                                                                <div class="form-group mt-4">
                                                                                    <strong class="d-block">Catatan:</strong>
                                                                                    <div class="text-body">{!! clean(@$session?->mentoring_note) !!}</div>
                                                                                </div>
                                                                                <div class="form-group mt-4">
                                                                                    <strong class="d-block">Arahan:</strong>
                                                                                    <div class="text-body">{!! clean(@$session?->mentoring_instructions) !!}</div>
                                                                                </div>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-center">
                                                                    <h4 class="text-muted">Belum ada kegiatan</h4>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Final Report Mentoring') }}
                                            </p>
                                            <p class="mb-3" style="font-size: 1.1rem;">
                                                Laporan akhir dari kegiatan mentoring yang telah dilakukan.
                                            </p>

                                            @if ($mentoring->final_report)
                                                <embed
                                                    src="{{ $mentoring->final_report }}" target="_self"
                                                    type="application/pdf"
                                                    width="100%"
                                                    height="600px"
                                                />
                                            @else
                                                <div class="text-center">
                                                    <h4 class="text-muted"><em>Belum ada laporan akhir</em></h4>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">


                        @if($mentor)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('List Mentor') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://i.pinimg.com/736x/f9/8a/db/f98adb0622aa2341ba29aaeb46901b33.jpg"
                                            alt="{{ $mentor->name }}"
                                            class="rounded-circle"
                                            width="50" height="50"
                                            style="object-fit: cover;">
                                        <div class=" ml-2">
                                            <strong>{{ $mentor->name }}</strong><br>
                                            <small class="text-muted">{{ $mentor->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($mentee)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('List Mentee') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://i.pinimg.com/736x/f9/8a/db/f98adb0622aa2341ba29aaeb46901b33.jpg"
                                            alt="{{ $mentee->name }}"
                                            class="rounded-circle"
                                            width="50" height="50"
                                            style="object-fit: cover;">
                                        <div class=" ml-2">
                                            <strong>{{ $mentee->name }}</strong><br>
                                            <small class="text-muted">{{ $mentee->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Mentor Availability Letter') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group d-flex justify-content-center">
                                     @if ($mentoring->mentor_availability_letter)
                                        <a href="{{ route('admin.mentoring.view.document', ['id' => $mentoring->id, 'type' => 'mentor_availability_letter']) }}" target="_blank" class="btn btn-outline-danger d-flex align-items-center gap-2"
                                            style="font-size: 1.5rem;">
                                            <i class="fas fa-file-pdf fa-2x mr-1"></i> Lihat Surat
                                        </a>
                                    @else
                                        <p class="text-muted mb-0">Tidak ada file.</p>
                                    @endif
                            </div>
                        </div>
                        {{-- jika status mentoring sudah selesai --}}
                        @if ($mentoring->status == 'done')
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ __('Certificate Status') }}</h4>

                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="tooltip"
                                        data-placement="left" title="Tambah Certificate">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="form-group d-flex justify-content-center">
                                        <div id="certificateBg"></div>
                                        <input type="hidden" name="certificate" value="" class="form-control">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
                                            data-target="#certificateModal">{{ __('Choose Certificate') }}</button>
                                    </div>
                                    <div class="modal fade" id="certificateModal" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                                                    <h5 class="modal-title" id="certificateModalLabel">
                                                        {{ __('Choose Certificate') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    @foreach ($certificates as $certificate)
                                                        <div class="col-md-3 d-flex flex-column">
                                                            <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                                                alt="" style="width: 100%; height: auto;">
                                                            <button class="btn btn-primary mt-auto"
                                                                onclick="chooseCertificate({{ $certificate->id }})">{{ __('Choose') }}</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                altInput: true,
                altFormat: "l, d F Y - H:i",
                locale: "id"
            });
        });

        function handleTolakMentoring(event, id) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin menolak mentoring ini?',
                text: "Silakan berikan alasan penolakan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Totak!',
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penolakan',
                inputValidator: (value) => {
                    if (!value || value.trim() === "") {
                        return 'Alasan penolakan wajib diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value || '';
                    $.ajax({
                        method: "post",
                        url: base_url + "/student/mentor/" + id + "/reject",
                        data: {
                            _token: csrf_token,
                            reason: notes
                        },
                        success: function(data) {
                            if (data.status === "success") {
                                toastr.success(data.message);
                                window.location.href = base_url + "/student/mentor";
                            }
                        },
                        error: function(xhr, status, error) {
                            const message = xhr.responseJSON?.message || '-';
                            toastr.error(message);
                        },
                    });
                } else {
                    swal.fire('Penolakan dibatalkan', '', 'error');
                }
            })
        };

        function handleApproveMentoring(event) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin memproses mentoring ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#approve_mentoring').submit();
                }
            })
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif
@endpush

@push('css')
    <style>
    </style>
@endpush
