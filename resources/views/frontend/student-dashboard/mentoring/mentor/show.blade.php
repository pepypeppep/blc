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

        @if ($mentoring->status == Mentoring::STATUS_EVALUATION || $mentoring->status == Mentoring::STATUS_DONE)
        <div class="mt-3 border-top pt-3">
            @if ($mentoring->status == Mentoring::STATUS_EVALUATION)
                <div class="mt-2 alert alert-warning" role="alert">
                    <strong>{{ __('Silahkan Menyelesaikan Evaluasi melalui tautan berikut') }}</strong>
                    <br>
                    <a
                        href="{{ route('student.mentor.evaluasi', $mentoring->id) }}"><span>{{ __('Evaluasi Sekarang') }}</span></a>
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

         <div class="mt-3 border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">{{ $mentoring->title }}</h5>
                <span class="badge fs-6
                    @php
                        $statusColors = [
                            'Draft' => 'bg-secondary',
                            'Pengajuan' => 'bg-warning',
                            'Proses' => 'bg-info',
                            'Selesai' => 'bg-success',
                            'Tolak' => 'bg-danger',
                        ];
                        echo $statusColors[$mentoring->status] ?? 'bg-light text-dark';
                    @endphp
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
                        <a href="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'mentor_availability_letter']) }}" target="_blank" class="btn-outline-primary btn-sm">
                            <i class="fa fa-file-pdf"></i> Lihat Surat
                        </a>
                    @else
                        <p class="text-muted mb-0">Tidak ada file.</p>
                    @endif
                </div>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                <div>
                    <h6 class="title">{{ __('Session Datetime') }} <span title="Jumlah pertemuan">({{ $mentoring->total_session }})</span></h6>
                    <span class="text-muted small">
                        Lakukan sesi mentoring sesuai jadwal dan laporkan hasil penugasan.
                    </span>
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                @foreach($mentoring->mentoringSessions as $session)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-{{$loop->iteration}}" aria-expanded="true" aria-controls="panelsStayOpen-collapse-{{$loop->iteration}}">
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
                    <div id="panelsStayOpen-collapse-{{$loop->iteration}}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @if(!empty($session->activity))
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
                                            <img src="{{ route('student.mentee.view.img', $session->id) }}" alt="img" class="img-thumbnail mt-2" style="max-width: 200px;">
                                        </a>
                                    @else
                                        <p class="text-muted"><em>Belum ada dokumentasi gambar.</em></p>
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <form action="{{ route('student.mentor.review', $session->id) }}" method="POST" class="d-inline"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                        <div class="form-group mt-4">
                                            <label for="mentoring_note">{{ __('Catatan') }} <code>*</code></label>
                                            <textarea name="mentoring_note" class="text-editor form-control summernote">{!! clean(@$session?->mentoring_note) !!}</textarea>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="mentoring_instructions">{{ __('Arahan') }} <code>*</code></label>
                                            <textarea name="mentoring_instructions" class="text-editor form-control summernote">{!! clean(@$session?->mentoring_instructions) !!}</textarea>
                                        </div>
                                        @if (!$session->mentoring_note && !$session->mentoring_instructions && $mentoring->status == 'Proses')
                                            <div class="row">
                                                <div class="col-12 text-end">

                                                    <button type="submit" class="btn mt-4 mb-3">
                                                        {{ __('Kirim') }} <i class="fa fa-arrow-right"></i>
                                                    </button>

                                                </div>
                                            </div>
                                        @endif
                                    </form>
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
                @if ($mentoring->isProcessOrDone() && !$hasIncompleteSessions)
                    @if ($mentoring->final_report)
                        <div class="mt-2">
                            <a href="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                            target="_blank"
                            class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-file-pdf"></i> Buka di Tab Baru
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <div class="mb-3">
                @if ($mentoring->isProcessOrDone() && !$hasIncompleteSessions)
                    @if ($mentoring->final_report)
                        <embed src="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                            type="application/pdf"
                            width="100%"
                            height="500px"
                            class="border rounded shadow-sm" />
                    @endif
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
                    <form action="{{ route('student.mentor.approve', $mentoring->id) }}" method="POST" class="d-inline" id="approve_mentoring">
                        @csrf
                        @method('POST')
                        <button type="button" class="btn mt-4 mb-3"
                            onclick="handleApproveMentoring(event)">
                            {{ __('Lanjutkan Proses Mentoring') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
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
