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
                                    <div class="col-lg-12 mb-4">
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
                                                @foreach ($mentoring->mentoringSessions as $index => $session)
                                                    <div class="accordion-item border border-secondary-subtle mb-2">
                                                        <h2 class="accordion-header">
                                                            <div
                                                                class="accordion_header_content d-flex justify-content-between align-items-center">
                                                                <button class="accordion-button course-quiz-btn collapsed"
                                                                    type="button" data-toggle="collapse"
                                                                    data-target="#mentoring-collapse{{ $index }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="mentoring-collapse{{ $index }}"
                                                                    style="width: 100%;">
                                                                    <div class="text-start">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <span class="fw-semibold text-dark"
                                                                                style="font-size: 0.95rem;">
                                                                                Pertemuan {{ $index + 1 }}
                                                                            </span>
                                                                            @if (!empty($session->activity))
                                                                                <span
                                                                                    class="badge bg-info text-white ml-2 px-2 py-1"
                                                                                    style="font-size: 0.7rem; font-weight: 700;">
                                                                                    Terisi
                                                                                </span>
                                                                            @endif
                                                                            @if (!empty($session->mentoring_note))
                                                                                <span
                                                                                    class="badge bg-primary text-white ml-2 px-2 py-1"
                                                                                    style="font-size: 0.7rem; font-weight: 700;">
                                                                                    Direviu
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-muted text-left"
                                                                            style="font-size: 0.85rem;">
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
                                                                            <a href="{{ route('admin.mentoring.view.img', $session->id) }}"
                                                                                target="_blank">
                                                                                <img src="{{ route('admin.mentoring.view.img', $session->id) }}"
                                                                                    alt="img"
                                                                                    class="img-thumbnail mt-2"
                                                                                    style="max-width: 200px;">
                                                                            </a>
                                                                        @else
                                                                            <p class="text-muted"><em>Belum ada dokumentasi
                                                                                    gambar.</em></p>
                                                                        @endif
                                                                    </div>

                                                                    <div class="mb-2">
                                                                        <div class="form-group mt-4">
                                                                            <strong class="d-block">Catatan:</strong>
                                                                            <div class="text-body">
                                                                                {!! clean(@$session?->mentoring_note) !!}</div>
                                                                        </div>
                                                                        <div class="form-group mt-4">
                                                                            <strong class="d-block">Arahan:</strong>
                                                                            <div class="text-body">
                                                                                {!! clean(@$session?->mentoring_instructions) !!}</div>
                                                                        </div>
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
                                            <p class="text-primary mb-0" style=" font-weight: 600;">
                                                {{ __('Final Report Mentoring') }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <p class="mb-3" style="font-size: 1.1rem;">
                                                    Laporan akhir dari kegiatan mentoring yang telah dilakukan.
                                                </p>
                                                @if ($mentoring->final_report)
                                                    <a href="{{ route('admin.mentoring.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                                                        target="_blank" class="btn btn-primary">
                                                        <i class="fa fa-file-pdf"></i> Buka di Tab Baru
                                                    </a>
                                                @endif
                                            </div>

                                            @if ($mentoring->final_report)
                                                <embed
                                                    src="{{ route('admin.mentoring.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                                                    type="application/pdf" width="100%" height="500px"
                                                    class="border rounded shadow-sm" />
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
                        {{-- jika status mentoring Verifikasi / Selesai --}}
                        @if ($mentoring->status == 'Verifikasi' || $mentoring->status == 'Selesai')
                            <!-- pilih sertifikat -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Sertifikat') }}</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.mentoring.update-certificate', $mentoring->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="certificate">{{ __('Pilih Sertifikat') }} <code>*</code></label>
                                            <div>
                                                <div id="certificateBg">
                                                    @if ($mentoring->certificate_id)
                                                        <img src="{{ route('admin.certificate-builder.getBg', $mentoring->certificate_id) }}"
                                                            alt="{{ __('Sertifikat') }}"
                                                            style="width: 100%; height: auto; max-width: 300px;">
                                                    @endif
                                                </div>
                                                <input type="hidden" name="certificate_id"
                                                    value="{{ $mentoring->certificate_id }}" class="form-control">
                                                <div class="d-flex justify-content-around">
                                                    @if ($mentoring->status == 'Verifikasi')
                                                        <button type="button" class="btn btn-primary mt-3"
                                                            data-toggle="modal" data-target="#certificateModal">
                                                            {{ __('Pilih Sertifikat') }}
                                                        </button>
                                                        <button type="button" class="btn btn-success mt-3"
                                                            data-toggle="modal" data-target="#tteModal">
                                                            {{ __('Ajukan TTE') }}
                                                        </button>
                                                    @endif
                                                    {{-- jika status mentoring Selesai tampilkan label menunggu proses TTE --}}
                                                    @if ($mentoring->status == 'Selesai' && $mentoring->signing_status == 'pending')
                                                        <span class="badge badge-warning">Menunggu proses TTE</span>
                                                    @endif
                                                    @if ($mentoring->status == 'Selesai' && $mentoring->signing_status == 'signed')
                                                        <a target="_blank"
                                                            href="{{ route('public.mentoring', $mentoring->uuid) }}"
                                                            class="btn btn-success">
                                                            <i class="fa fa-download"></i> Download Sertifikat TTE
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($mentor)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('List Mentor') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://i.pinimg.com/736x/f9/8a/db/f98adb0622aa2341ba29aaeb46901b33.jpg"
                                                alt="{{ $mentor->name }}" class="rounded-circle" width="50"
                                                height="50" style="object-fit: cover;">
                                            <div class=" ml-2">
                                                <strong>{{ $mentor->name }}</strong><br>
                                                <small class="text-muted">{{ $mentor->email }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.mentoring.mentor.evaluasi', [$mentoring, $mentor]) }}"
                                                class="btn btn-primary" target="_blank">
                                                <i class="fa fa-envelope-open-text"></i> Hasil Evaluasi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($mentee)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('List Mentee') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://i.pinimg.com/736x/f9/8a/db/f98adb0622aa2341ba29aaeb46901b33.jpg"
                                                alt="{{ $mentee->name }}" class="rounded-circle" width="50"
                                                height="50" style="object-fit: cover;">
                                            <div class=" ml-2">
                                                <strong>{{ $mentee->name }}</strong><br>
                                                <small class="text-muted">{{ $mentee->email }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.mentoring.mentee.evaluasi', [$mentoring, $mentor]) }}"
                                                class="btn btn-primary" target="_blank">
                                                <i class="fa fa-envelope-open-text"></i> Hasil Evaluasi
                                            </a>
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
                                        <a href="{{ route('admin.mentoring.view.document', ['id' => $mentoring->id, 'type' => 'mentor_availability_letter']) }}"
                                            target="_blank" class="btn btn-outline-danger d-flex align-items-center gap-2"
                                            style="font-size: 1.5rem;">
                                            <i class="fas fa-file-pdf fa-2x mr-1"></i> Lihat Surat
                                        </a>
                                    @else
                                        <p class="text-muted mb-0">Tidak ada file.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </section>
    </div>
@endsection

<!-- Certificate Modal -->
<div class="modal fade" id="certificateModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header pl-4">
                <h5 class="modal-title" id="certificateModalLabel">
                    {{ __('Pilih Sertifikat') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col">
                    <div class="row">
                        @foreach ($certificates as $certificate)
                            <div class="col-md-3 mb-3">
                                <div class="card certificate-card" id="certificate-{{ $certificate->id }}">
                                    <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                        alt="{{ $certificate->name }}" class="card-img-top"
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $certificate->name }}</h6>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="chooseCertificate({{ $certificate->id }}, '{{ $certificate->name }}')">
                                            {{ __('Pilih') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="tte1">{{ __('TTE Depan') }}</label>
                                <select class="form-control tte_select" id="tte1" name="tte1">
                                    @foreach ($mentoring->signers as $signer)
                                        @if ($signer->step == 1)
                                            <option selected value="{{ $signer->user_id }}">{{ $signer->user->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-4">
                            <div class="form-group">
                                <label for="tte2">{{ __('TTE Belakang') }}</label>
                                <select class="form-control tte_select" id="tte2" name="tte2">
                                    @foreach ($mentoring->signers as $signer)
                                        @if ($signer->step == 2)
                                            <option selected value="{{ $signer->user_id }}">{{ $signer->user->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('Tutup') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveCertificate()" id="saveCertificateBtn"
                    disabled>
                    {{ __('Simpan Sertifikat') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TTE Confirmation Modal -->
<div class="modal fade" id="tteModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="tteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pl-4">
                <h5 class="modal-title" id="tteModalLabel">{{ __('Konfirmasi Pengajuan TTE') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Apakah Anda yakin ingin mengajukan TTE untuk mentoring ini?') }}</p>
                <p class="text-muted">{{ __('Mentoring:') }} <strong>{{ $mentoring->title }}</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                <button type="button" class="btn btn-success" id="confirmTTE">{{ __('Ajukan TTE') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for TTE submission -->
<form id="tteForm" method="POST" action="{{ route('admin.mentoring.request-sign-certificate', $mentoring->id) }}"
    style="display: none;">
    @csrf
    <input type="hidden" name="mentoring_id" value="{{ $mentoring->id }}">
    <input type="hidden" name="title" value="{{ $mentoring->title }}">
</form>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('css')
    <style>
        .certificate-card.border-danger {
            border: 2px solid #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .certificate-card .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endpush

@push('js')
    <script>
        // Certificate selection functions
        let selectedCertificateId = null;

        function chooseCertificate(id, name) {
            selectedCertificateId = id;

            // Remove previous highlights
            $('.certificate-card').removeClass('border-danger shadow-danger');
            $('.certificate-card .btn').removeClass('btn-danger').addClass('btn-primary');

            // Highlight selected certificate
            $('#certificate-' + id).addClass('border-danger shadow-danger');
            $('#certificate-' + id + ' .btn').removeClass('btn-primary').addClass('btn-danger');

            // Enable save button
            $('#saveCertificateBtn').prop('disabled', false);

            // Update preview
            $('input[name="certificate_id"]').val(id);
            $('#certificateBg').html(
                '<img src="' + '{{ route('admin.certificate-builder.getBg', '') }}/' + id + '" alt="' + name +
                '" style="width: 100%; height: auto; max-width: 300px;">'
            );
        }

        function saveCertificate() {
            if (!selectedCertificateId) return;

            let tteDepan = $('#tte1').val();
            let tteBelakang = $('#tte2').val();

            $.ajax({
                url: '{{ route('admin.mentoring.update-certificate', $mentoring->id) }}',
                type: 'PUT',
                data: {
                    certificate_id: selectedCertificateId,
                    tte_depan: tteDepan,
                    tte_belakang: tteBelakang,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#certificateModal').modal('hide');
                    location.reload(); // Reload to show updated certificate
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }

        // Initialize on page load
        $(document).ready(function() {
            // Initialize Select2 for TTE dropdowns with AJAX
            $('.tte_select').select2({
                placeholder: 'Pilih TTE',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route('admin.mentoring.get-users') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(user) {
                                return {
                                    id: user.id,
                                    text: user.name + ' (' + user.jabatan + ')'
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                dropdownParent: $("#certificateModal"),
            });

            @if ($mentoring->certificate_id)
                $('#certificateBg').html(
                    '<img src="{{ route('admin.certificate-builder.getBg', $mentoring->certificate_id) }}" alt="{{ __('Sertifikat') }}" style="width: 100%; height: auto; max-width: 300px;">'
                );
            @endif
        });
    </script>

    <script>
        // TTE Request functionality
        $(document).ready(function() {
            // Handle TTE button click to show modal
            $('[data-target="#tteModal"]').click(function() {
                $('#tteModal').modal('show');
            });

            // Handle confirmation in modal
            $('#confirmTTE').click(function() {
                $('#tteModal').modal('hide');
                $('#tteForm').submit();
            });
        });
    </script>
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
