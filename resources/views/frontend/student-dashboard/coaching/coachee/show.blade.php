@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Coaching\app\Models\Coaching;
    use Modules\Coaching\app\Models\CoachingSessionDetail;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="">{{ __('Detail Tema Coaching') }}</h4>
            <a href="{{ route('student.coachee.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>

        <div class="mt-3 border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">{{ $coaching->title }}</h5>
                @if ($coachingUser->is_joined == 1)
                    <span class="badge fs-6 bg-success text-white">
                        {{ __('Bergabung') }}
                    </span>
                @elseif ($coachingUser->isRejected())
                    <span class="badge fs-6 bg-danger text-white">
                        {{ __('Menolak Bergabung') }}
                    </span>
                @else
                    <span class="badge fs-6 bg-danger text-white">
                        {{ __('Belum Bergabung') }}
                    </span>
                @endif
            </div>

            <div class="mb-3 border-top pt-3 mt-4">
                <h6 class="title">{{ __('Main Issue') }}</h6>
                <div>{!! $coaching->main_issue !!}</div>
            </div>

            <div class="mb-3">
                <h6 class="title">{{ __('Purpose') }}</h6>
                <div>{!! $coaching->purpose !!}</div>
            </div>

            <div class="mb-3 d-flex align-items-center gap-4 border-top pt-3 mt-4">
                <div>
                    <h6 class="mb-1 title">{{ __('Coach') }}</h6>
                    <p class="mb-0">{{ $coaching->coach->name ?? '-' }}</p>
                </div>

                <div class="text-end flex-grow-1">
                    <h6 class="mb-1 title">{{ __('Surat Konsesus') }}</h6>
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

            @if ($coachingUser->is_joined == 1)
                <div class="mb-3 d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <div>
                        <h6 class="title">{{ __('Session Datetime') }} <span title="Jumlah pertemuan"></span></h6>
                        <span class="text-muted small">
                            Lakukan sesi coaching sesuai jadwal dan laporkan hasil penugasan.
                        </span>
                    </div>
                    @if ($coachingUser->coaching->status == Coaching::STATUS_PROCESS && $coachingUser->final_report == null && !$userCanSubmitFinalReport)
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editSessionModal">
                            <i class="fa fa-edit"></i> Isi Kegiatan
                        </button>
                    @endif
                </div>

                <div class="accordion" id="accordionPanelsStayOpenExample">
                    @foreach ($sessions as $session)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapse-{{ $loop->iteration }}" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapse-{{ $loop->iteration }}">
                                    <div class="d-block">
                                        <div>
                                            <strong>Pertemuan {{ $loop->iteration }}</strong>
                                            @if ($session->details->count() > 0)
                                                @if ($session->details->first()->coaching_note && $session->details->first()->coaching_instructions)
                                                    <span class="badge bg-warning">Ditinjau</span>
                                                @else
                                                    <span class="badge bg-info">Terisi</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Kosong</span>
                                            @endif
                                        </div>
                                        <div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($session->coaching_date)->translatedFormat('l, d F Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapse-{{ $loop->iteration }}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @if ($session->details->count() > 0)
                                        <div>
                                            <strong class="d-block">Deskripsi Kegiatan:</strong>
                                            <div class="text-body"><em>{!! $session->details->first()->description !!}</em></div>
                                        </div>
                                        <div class="mb-2">
                                            <strong class="d-block">Dokumentasi:</strong>
                                            @if ($session->details->first()->image)
                                                <a href="{{ route('student.coachee.preview', ['coachingId' => $session->coaching_id, 'coachingSessionId' => $session->id]) }}"
                                                    target="_blank">
                                                    <img src="{{ route('student.coachee.preview', ['coachingId' => $session->coaching_id, 'coachingSessionId' => $session->id]) }}"
                                                        alt="Dokumentasi"
                                                        style="max-width: 200px; max-height: 150px; display: block;">
                                                </a>
                                            @else
                                                <p class="text-muted"><em>Tidak ada dokumentasi</em></p>
                                            @endif
                                        </div>
                                        @if ($session->details->first()->coaching_note)
                                            <div class="mb-2">
                                                <strong class="d-block">Catatan Coach:</strong>
                                                <div class="text-body"><em>{!! $session->details->first()->coaching_note !!}</em></div>
                                            </div>
                                        @endif
                                        @if ($session->details->first()->coaching_instructions)
                                            <div class="mb-2">
                                                <strong class="d-block">Instruksi Coach:</strong>
                                                <div class="text-body"><em>{!! $session->details->first()->coaching_instructions !!}</em></div>
                                            </div>
                                        @endif
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
                        <h6 class="title">Laporan Akhir Coaching</h6>
                        <span class="text-muted small">
                            Laporan akhir dari kegiatan coaching yang telah dilakukan.
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    @if (!$coachingUser->final_report)
                        @if ($userCanSubmitFinalReport)
                            <div class="alert alert-info">
                                <strong>Ketentuan :</strong><br>
                                - Harap unggah laporan akhir yang telah <strong>ditandatangani oleh Coach</strong>.<br>
                                - Format laporan akhir yang diunggah berupa file <strong>PDF</strong> dengan ukuran
                                <strong>maksimal
                                    5MB</strong>.<br>
                                - Laporan akhir dapat diunggah ketika Sesi Pertemuan Coaching telah selesai dilakukan.
                            </div>
                            <form
                                action="{{ route('student.coachee.submit-final-report', ['coachingUserId' => $coachingUser->id, 'coachingId' => $coachingUser->coaching_id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="input-group mb-3">
                                    <span class="input-group-text text-dark" style="cursor: pointer;"
                                        onclick="document.getElementById('final_report').click();">
                                        <i class="fa fa-file-pdf"></i> &nbsp;{{ __('Choose') }}
                                    </span>
                                    <input id="file_name" readonly class="form-control" type="text"
                                        placeholder="Belum ada file dipilih"
                                        onclick="document.getElementById('final_report').click();">

                                    <input id="final_report" name="final_report" class="d-none" type="file"
                                        onchange="document.getElementById('file_name').value = this.files[0]?.name || '';"
                                        accept=".pdf" required>

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-upload"></i> Unggah
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <strong>Catatan:</strong> Anda mungkin belum menyelesaikan sesi pertemuan. Silakan lengkapi
                                laporan
                                akhir setelah semua sesi selesai dan tunggu coach untuk meninjau.
                            </div>
                        @endif
                    @else
                        <embed
                            src="{{ route('student.coachee.preview-final-report', ['coachingId' => $coachingUser->coaching_id, 'coachingUserId' => $coachingUser->id]) }}"
                            type="application/pdf"
                            style="border:1px solid #ccc; border-radius:4px; cursor:pointer; width:100%; height:500px;"
                            onclick="window.open('{{ route('student.coachee.preview-final-report', ['coachingId' => $coachingUser->coaching_id, 'coachingUserId' => $coachingUser->id]) }}', '_blank')" />
                    @endif

                </div>
            @endif
        </div>
        @if (!$coachingUser->isRejected() && !$coachingUser->is_joined == 1)
            <div class="row">
                <div class="col-12 justify-content-between d-flex align-items-center">
                    <button type="button" onclick="handleTolakKonsesus(event, {{ $coaching->id }})"
                        class="btn-danger mt-4 mb-3">
                        {{ __('Tolak Konsesus') }} <i class="fa fa-arrow-right"></i>
                    </button>
                    <form action="{{ route('student.coachee.join-konsensus', $coaching->id) }}" method="POST"
                        class="d-inline" id="approve_konsesus">
                        @csrf
                        <button type="button" class="btn mt-4 mb-3" onclick="handleApproveKonsesus(event)">
                            {{ __('Lanjutkan Proses Konsesus') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('modals')
    <!-- Modal Edit Session -->
    <div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('student.coachee.submit-report') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSessionModalLabel">Isi Detail Pertemuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal-session-id" class="form-label">Pilih Pertemuan<code>*</code></label>
                            <select class="form-select" name="session_id" id="modal-session-id" required>
                                <option value="" disabled selected>Pilih Jadwal Pertemuan</option>
                                @foreach ($sessions as $session)
                                    @if ($session->details->count() == 0)
                                        <option value="{{ $session->id }}">Pertemuan {{ $loop->iteration }} -
                                            {{ \Carbon\Carbon::parse($session->coaching_date)->translatedFormat('l, d F Y H:i') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal-activity" class="form-label">Deskripsi Kegiatan<code>*</code></label>
                            <textarea class="form-control summernote" name="activity" id="modal-activity" style="height:150px;" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Dokumentasi<code>*</code></label>
                            <input type="file" class="form-control" name="image" id="image"
                                accept="image/jpeg,image/png" required>
                            <small class="form-text text-danger">
                                Berkas harus berupa <strong>JPG/PNG</strong> dengan ukuran maksimal 2MB.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Edit Session -->
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editSessionModal').on('shown.bs.modal', function() {
                // console.log('Modal shown, initialize summernote');
                if ($('#modal-activity').next('.note-editor').length) {
                    $('#modal-activity').summernote('destroy');
                }
                if ($('#modal-obstacle').next('.note-editor').length) {
                    $('#modal-obstacle').summernote('destroy');
                }
                // Init Summernote
                $('#modal-activity').summernote({
                    height: 120,
                    placeholder: 'Deskripsikan kegiatan...',
                });
                $('#modal-obstacle').summernote({
                    height: 120,
                    placeholder: 'Tulis hambatan jika ada...',
                });
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        function handleTolakKonsesus(event, id) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin menolak konsesus ini?',
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
                        url: base_url + "/student/coachee/" + id + "/tolak-konsensus",
                        data: {
                            _token: csrf_token,
                            reason: notes
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.status === "success") {
                                toastr.success(data.message);
                                window.location.href = base_url + "/student/coachee";
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

        function handleApproveKonsesus(event) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin memproses konsesus ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#approve_konsesus').submit();
                }
            })
        }
    </script>
@endpush
