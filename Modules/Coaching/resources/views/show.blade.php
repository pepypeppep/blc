@extends('admin.master_layout')
@section('title')
    <title>{{ __('Coaching Detail') }}</title>
@endsection

@php
    use Modules\Coaching\app\Models\Coaching;
@endphp

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Detail Tema Coaching') }}</h1>
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
                                <h4>{{ $coaching->title }}</h4>
                                <div class="badge badge-{{ $coaching->stat['color'] }}">{{ $coaching->stat['label'] }}
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Goal') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->goal !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Reality') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->reality !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Option') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->option !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Way Forward') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->way_forward !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Success Indicator') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->success_indicator !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Sumber Belajar') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->sumber_belajar !!}
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('SPT Coaching') }}</p>
                                            @if ($coaching->spt)
                                                <a href="{{ route('student.coach.view.spt', $coaching->id) }}" target="_blank"
                                                    class="btn-outline-primary btn-sm">
                                                    <i class="fa fa-file-pdf"></i> Lihat Surat
                                                </a>
                                            @else
                                                <p class="text-muted mb-0"><em>Tidak ada file</em></p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="mb-3 d-flex flex-wrap justify-content-between align-items-start border-top pt-3 mt-4 gap-3">
                                            <div class="flex-grow-1 me-3" style="min-width: 250px; max-width: 80%;">
                                                <h6 class="title">
                                                    {{ __('List Coachee') }}
                                                    <span
                                                        title="Jumlah coachee yang telah merespon">({{ $coaching->respondedCoachees()->count() }}/{{ $coaching->coachees()->count() }})</span>
                                                </h6>
                                                <span class="text-muted small d-block">
                                                    Lakukan sesi coaching bersama dengan coachee yang telah dipilih dan bergabung.
                                                </span>
                                                @if ($coaching->status == Coaching::STATUS_DRAFT)
                                                    <span class="text-muted small d-block">
                                                        Klik <strong>Buat Konsensus</strong> agar coachee dapat melakukan konsensus
                                                        (menyetujui/menolak).
                                                    </span>
                                                @elseif ($coaching->status == Coaching::STATUS_CONSENSUS)
                                                    <span class="text-muted small d-block">
                                                        Klik <strong>Mulai Proses Coaching</strong> agar sesi pertemuan bisa dilakukan.
                                                    </span>
                                                @elseif ($coaching->status == Coaching::STATUS_PROCESS)
                                                    <span class="text-muted small d-block">
                                                        Penilaian dapat dilakukan ketika coachee sudah menyelesaikan laporan pertemuan dan unggah
                                                        Laporan Akhir. <br />
                                                        Penilaian dapat dikirim ke BKPSDM ketika semua coachee yang bergabung telah dinilai.
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-2">
                                                @if ($coaching->status == Coaching::STATUS_DRAFT)
                                                    <form action="{{ route('student.coach.set-consensus', $coaching->id) }}" method="POST"
                                                        class="d-inline" id="init_consensus">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" class="btn btn-outline-primary" onclick="handleInitConsensus(event)">
                                                            {{ __('Buat Konsensus') }} <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
                                                @elseif ($coaching->status == Coaching::STATUS_CONSENSUS)
                                                    <form action="{{ route('student.coach.process-coaching', $coaching->id) }}" method="POST"
                                                        class="d-inline" id="process_coaching">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" class="btn btn-outline-success" onclick="handleStartCoaching(event)">
                                                            {{ __('Mulai Proses Coaching') }} <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
                                                @elseif ($coaching->status == Coaching::STATUS_PROCESS && $coaching->isAllCoacheesAssessed())
                                                    <form action="{{ route('student.coach.send-assessment', $coaching->id) }}" method="POST"
                                                        class="d-inline" id="send_assessment">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" class="btn btn-outline-success"
                                                            onclick="handleSendAssessment(event)">
                                                            {{ __('Kirim Penilaian') }} <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="w-full table-responsive rounded-lg mb-8">
                                            <table class="table-auto w-full border">
                                                <thead>
                                                    <tr>
                                                        <th class="border px-4 py-2">Nama</th>
                                                        <th class="border px-4 py-2">Status</th>
                                                        <th class="border px-4 py-2">Keterangan Konsensus</th>
                                                        <th class="border px-4 py-2">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($coaching->coachees as $coachee)
                                                        <tr>
                                                            <td class="border px-4 py-2">{{ $coachee->name }}</td>
                                                            <td class="border px-4 py-2">
                                                                @if (is_null($coachee->pivot->is_joined))
                                                                    <span class="badge bg-secondary">Belum merespons</span>
                                                                @elseif ($coachee->pivot->is_joined)
                                                                    <span class="badge bg-success">Bergabung</span>
                                                                @else
                                                                    <span class="badge bg-danger">Menolak</span>
                                                                @endif
                                                            </td>
                                                            <td class="border px-4 py-2">
                                                                @if ($coachee->pivot->is_joined && $coachee->pivot->joined_at)
                                                                    <small>Dibuat :
                                                                        {{ \Carbon\Carbon::parse($coachee->pivot->joined_at)->translatedFormat('d F Y H:i') }}</small>
                                                                @else
                                                                    {{ $coachee->pivot->notes ? truncate(strip_tags($coachee->pivot->notes)) : '-' }}<br />
                                                                    <small>Dibuat :
                                                                        {{ \Carbon\Carbon::parse($coachee->pivot->updated_at)->translatedFormat('d F Y H:i') }}</small>
                                                                @endif
                                                            </td>
                                                            <td class="border px-4 py-2">
                                                                <div class="dashboard__action d-inline-flex align-items-center gap-2">
                                                                    @if ($coachee->pivot->final_report && $coachee->pivot->is_joined)
                                                                        <a href="{{ route('student.coach.view.report', $coachee->pivot->id) }}"
                                                                            class="btn-action-primary" title="Lihat Laporan Akhir" target="_blank">
                                                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Laporan Akhir') }}
                                                                        </a>
                                                                        <a class="btn-action-warning"
                                                                            href="{{ route('student.coach.penilaian', [$coaching->id, $coachee->id]) }}">
                                                                            <i class="fa fa-check-circle"></i> &nbsp;{{ __('Penilaian') }}
                                                                        </a>
                                                                    @else
                                                                        <a href="javascript:void(0)" class="btn-action-primary disabled"
                                                                            title="Laporan akhir belum tersedia" onclick="return false;"
                                                                            style="pointer-events: none; opacity: 0.5;">
                                                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Laporan Akhir') }}
                                                                        </a>
                                                                        <a href="javascript:void(0)" class="btn-action-warning disabled"
                                                                            title="Laporan akhir belum tersedia" onclick="return false;"
                                                                            style="pointer-events: none; opacity: 0.5;">
                                                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Penilaian') }}
                                                                        </a>
                                                                    @endif

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="border px-4 py-2 text-center">Belum ada coachee</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                Pelaksanaan Pertemuan ({{ count($coaching->coachingSessions) }})
                                            </p>

                                            <div class="accordion card mb-2" id="coachingAccordion">
                                                @foreach ($coaching->coachingSessions as $index => $session)
                                                    <div class="accordion-item border border-secondary-subtle mb-2">
                                                        <h2 class="accordion-header">
                                                            <div
                                                                class="accordion_header_content d-flex justify-content-between align-items-center">
                                                                <button class="accordion-button course-quiz-btn collapsed"
                                                                    type="button" data-toggle="collapse"
                                                                    data-target="#coaching-collapse{{ $index }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="coaching-collapse{{ $index }}"
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
                                                                            @if (!empty($session->coaching_note))
                                                                                <span
                                                                                    class="badge bg-primary text-white ml-2 px-2 py-1"
                                                                                    style="font-size: 0.7rem; font-weight: 700;">
                                                                                    Direviu
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-muted text-left"
                                                                            style="font-size: 0.85rem;">
                                                                            {{ \Carbon\Carbon::parse($session->coaching_date)->translatedFormat('l, d F Y H:i') }}
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </h2>
                                                        <div id="coaching-collapse{{ $index }}"
                                                            class="accordion-collapse collapse"
                                                            data-bs-parent="#coachingAccordion">
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
                                                                            <a href="{{ route('admin.coaching.view.img', $session->id) }}"
                                                                                target="_blank">
                                                                                <img src="{{ route('admin.coaching.view.img', $session->id) }}"
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
                                                                        @if ($session->status == 'reviewed')
                                                                            <form>
                                                                                <div class="form-group mt-4">
                                                                                    <label
                                                                                        for="coaching_date">{{ __('Mentoring Date') }}</label>
                                                                                    <input type="text"
                                                                                        name="coaching_date"
                                                                                        class="form-control datetimepicker"
                                                                                        value="{{ $session->coaching_date ?? '' }}"
                                                                                        disabled>
                                                                                </div>
                                                                                <div class="form-group mt-4">
                                                                                    <strong
                                                                                        class="d-block">Catatan:</strong>
                                                                                    <div class="text-body">
                                                                                        {!! clean(@$session?->coaching_note) !!}</div>
                                                                                </div>
                                                                                <div class="form-group mt-4">
                                                                                    <strong class="d-block">Arahan:</strong>
                                                                                    <div class="text-body">
                                                                                        {!! clean(@$session?->coaching_instructions) !!}</div>
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
                                            <p class="text-primary mb-0" style=" font-weight: 600;">
                                                {{ __('Final Report Mentoring') }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <p class="mb-3" style="font-size: 1.1rem;">
                                                    Laporan akhir dari kegiatan coaching yang telah dilakukan.
                                                </p>
                                                @if ($coaching->final_report)
                                                    <a href="{{ route('admin.coaching.view.document', ['id' => $coaching->id, 'type' => 'final_report']) }}"
                                                        target="_blank" class="btn btn-primary">
                                                        <i class="fa fa-file-pdf"></i> Buka di Tab Baru
                                                    </a>
                                                @endif
                                            </div>

                                            @if ($coaching->final_report)
                                                <embed
                                                    src="{{ route('admin.coaching.view.document', ['id' => $coaching->id, 'type' => 'final_report']) }}"
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
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Mentor Availability Letter') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group d-flex justify-content-center">
                                    @if ($coaching->mentor_availability_letter)
                                        <a href="{{ route('admin.coaching.view.document', ['id' => $coaching->id, 'type' => 'mentor_availability_letter']) }}"
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
            </div>
        </section>
    </div>
@endsection

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
                title: 'Apakah Anda yakin ingin menolak coaching ini?',
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
                title: 'Apakah Anda yakin ingin memproses coaching ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#approve_coaching').submit();
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
