@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="">{{ __('Detail Tema Mentoring') }}</h4>
            <a href="{{ route('student.mentor.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>

        <div class="mt-3 border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">{{ $mentoring->title }}</h5>
                <span
                    class="badge fs-6
                    @php
$statusColors = [
                            'Draft' => 'bg-secondary',
                            'Pengajuan' => 'bg-warning',
                            'Proses' => 'bg-info',
                            'Selesai' => 'bg-success',
                            'Tolak' => 'bg-danger',
                        ];
                        echo $statusColors[$mentoring->status] ?? 'bg-light text-dark'; @endphp
                ">
                    {{ $mentoring->status }}
                </span>
            </div>

            <div class="mb-3 border-top pt-3 mt-4">
                <h6 class="title">{{ __('Main Issue') }}</h6>
                <div>{!! $mentoring->description !!}</div>
            </div>

            <div class="mb-3">
                <h6 class="title">{{ __('Purpose') }}</h6>
                <div>{!! $mentoring->purpose !!}</div>
            </div>

            <div class="mb-3 d-flex align-items-center gap-4 border-top pt-3 mt-4">
                <div>
                    <h6 class="mb-1 title">{{ __('Mentor') }}</h6>
                    <p class="mb-0">{{ $mentoring->mentor->name ?? '-' }}</p>
                </div>

                <div class="text-end flex-grow-1">
                    <h6 class="mb-1 title">{{ __('Surat Kesediaan Mentor') }}</h6>
                    @if ($mentoring->mentor_availability_letter)
                        <a href="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'mentor_availability_letter']) }}"
                            target="_blank" class="btn-outline-primary btn-sm">
                            <i class="fa fa-file-pdf"></i> Lihat Surat
                        </a>
                    @else
                        <p class="text-muted mb-0">Tidak ada file.</p>
                    @endif
                </div>
            </div>

            <div class="mb-3 border-top pt-3 mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="title mb-1">
                            {{ __('Jadwal Sesi Mentoring') }}
                            <span class="badge bg-secondary ms-1" title="Jumlah pertemuan">
                                {{ $mentoring->total_session }}
                            </span>
                        </h6>
                        <span class="text-muted small">
                            Pastikan setiap sesi mentoring dilakukan sesuai jadwal yang telah ditentukan.
                        </span>
                    </div>
                    @if ($mentoring->status == Mentoring::STATUS_SUBMISSION)
                        <div>
                            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="modal"
                                data-bs-target="#changeSessionDatetimeModal">
                                <i class="fa fa-calendar-alt me-1"></i> Ubah Jadwal Sesi
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                @foreach ($mentoring->mentoringSessions as $session)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapse-{{ $loop->iteration }}" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapse-{{ $loop->iteration }}">
                                <div class="d-block">
                                    <div>
                                        <strong>Pertemuan {{ $loop->iteration }}</strong>
                                        {!! !empty($session->activity) ? '<span class="badge bg-info">Terisi</span>' : '' !!}
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapse-{{ $loop->iteration }}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                @if (!empty($session->activity))
                                    <div>
                                        <strong class="d-block">Deskripsi Kegiatan:</strong>
                                        <div class="text-body">{!! $session->activity ?: '<em>Tidak ada deskripsi kegiatan.</em>' !!}</div>
                                    </div>

                                    <div class="mb-2">
                                        <strong class="d-block">Hambatan:</strong>
                                        <div class="text-body">{!! $session->description ?: '<em>Tidak ada hambatan dicatat.</em>' !!}</div>
                                    </div>

                                    <div class="mb-2">
                                        <strong class="d-block">Dokumentasi:</strong>
                                        @if ($session->image && Storage::disk('private')->exists($session->image))
                                            <a href="{{ route('student.mentee.view.img', $session->id) }}" target="_blank">
                                                <img src="{{ route('student.mentee.view.img', $session->id) }}"
                                                    alt="img" class="img-thumbnail mt-2" style="max-width: 200px;">
                                            </a>
                                        @else
                                            <p class="text-muted"><em>Belum ada dokumentasi gambar.</em></p>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        @if ($session->status == 'reviewed')
                                            @include('frontend.student-dashboard.mentoring.mentor.partials.session-done')
                                        @else
                                            @include('frontend.student-dashboard.mentoring.mentor.partials.session-submit')
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center">
                                        <h4 class="text-muted">Belum ada kegiatan</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                <div>
                    <h6 class="title">Laporan Akhir Mentoring</h6>
                    <span class="text-muted small">
                        Laporan akhir dari kegiatan mentoring yang telah dilakukan.
                    </span>
                </div>
                @if ($mentoring->isProcessOrEvaluationOrDone() && !$hasIncompleteSessions)
                    @if ($mentoring->final_report)
                        <div class="mt-2">
                            <a href="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                                target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-file-pdf"></i> Buka di Tab Baru
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            <div class="mb-3">
                @if ($mentoring->isProcessOrEvaluationOrDone() && !$hasIncompleteSessions)
                    @if ($mentoring->final_report)
                        <embed
                            src="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                            type="application/pdf" width="100%" height="500px" class="border rounded shadow-sm" />
                    @endif
                @endif
            </div>

            <div class="mb-3">
                @if (
                    $mentoring->status == Mentoring::STATUS_EVALUATION ||
                        $mentoring->status == Mentoring::STATUS_VERIFICATION ||
                        $mentoring->status == Mentoring::STATUS_DONE)
                    <div class="mt-3 border-top pt-3">
                        @if ($mentoring->status == Mentoring::STATUS_EVALUATION && $mentoring->final_report)
                            <div class="mt-2 alert alert-warning" role="alert">
                                <div class="d-flex justify-content-center">
                                    <strong>{{ __('Silakan Menyelesaikan Evaluasi melalui tautan berikut') }}</strong>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    @if ($mentoring->feedback)
                                        <a href="{{ route('student.mentor.evaluasi', $mentoring->id) }}"
                                            class="btn btn-sm btn-primary"><span>{{ __('Mentee Evaluation') }}</span></a>
                                    @else
                                        <strong>Untuk dapat mengevaluasi mentee, harap menunggu Mentee menyelesaikan
                                            Mentoring
                                            terlebih dahulu</strong>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($mentoring->status == Mentoring::STATUS_DONE)
                            <div class="mt-2 alert alert-info" role="alert">
                                <strong>{{ __('Evaluasi telah selesai, jika ingin melihat hasilnya silahkan klik tautan berikut') }}</strong>
                                <br>
                                <a
                                    href="{{ route('student.mentor.evaluasi', $mentoring->id) }}"><span>{{ __('Lihat Evaluasi') }}</span></a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($mentoring->status == Mentoring::STATUS_SUBMISSION)
            <div class="row">
                <div class="col-12 justify-content-between d-flex align-items-center">
                    <button type="button" onclick="handleTolakMentoring(event, {{ $mentoring->id }})"
                        class="btn-danger mt-4 mb-3">
                        {{ __('Tolak Mentoring') }} <i class="fa fa-arrow-right"></i>
                    </button>
                    <form action="{{ route('student.mentor.approve', $mentoring->id) }}" method="POST" class="d-inline"
                        id="approve_mentoring">
                        @csrf
                        @method('POST')
                        <button type="button" class="btn mt-4 mb-3" onclick="handleApproveMentoring(event)">
                            {{ __('Lanjutkan Proses Mentoring') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal for changing session datetime -->
    <div class="modal fade" id="changeSessionDatetimeModal" tabindex="-1"
        aria-labelledby="changeSessionDatetimeModalLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('student.mentor.update.session') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeSessionDatetimeModalLabel">Ubah Jadwal Sesi Mentoring</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="session_number" class="form-label">Pilih Sesi</label>
                            <select class="form-select" id="session_number" name="session_id" required>
                                @foreach ($mentoring->mentoringSessions as $session)
                                    <option value="{{ $session->id }}">
                                        Pertemuan {{ $loop->iteration }} &mdash;
                                        {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="new_datetime" class="form-label">Tanggal &amp; Waktu Baru</label>
                            <input type="text" class="form-control datetimepicker" id="new_datetime"
                                name="mentoring_date" placeholder="Pilih tanggal & waktu baru" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0);
            z-index: -9999;
        }

        .modal-body {
            overflow: auto;
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>
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
@endpush
