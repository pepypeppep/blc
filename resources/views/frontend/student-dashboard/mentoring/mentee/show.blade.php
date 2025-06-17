@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="title">{{ __('Detail Tema Mentoring') }}</h4>
            <a href="{{ route('student.mentee.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><strong>{{ $mentoring->title }}</strong></h5>
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
                    <h6><strong>{{ __('Main Issue') }}</strong></h6>
                    <div>{!! $mentoring->description !!}</div>
                </div>

                <div class="mb-3">
                    <h6><strong>{{ __('Purpose') }}</strong></h6>
                    <div>{!! $mentoring->purpose !!}</div>
                </div>

                <div class="mb-3 d-flex align-items-center gap-4 border-top pt-3 mt-4">
                    <div>
                        <h6 class="mb-1"><strong>{{ __('Mentor') }}</strong></h6>
                        <p class="mb-0">{{ $mentoring->mentor->name ?? '-' }}</p>
                    </div>

                    <div class="text-end flex-grow-1">
                        <h6 class="mb-1"><strong>{{ __('Surat Kesediaan Mentor') }}</strong></h6>
                        @if ($mentoring->mentor_availability_letter)
                            <a href="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'mentor_availability_letter']) }}" target="_blank" class="btn-outline-primary btn-sm">
                                <i class="fa fa-file-pdf"></i> Lihat Surat
                            </a>
                        @else
                            <p class="text-muted mb-0">Tidak ada file.</p>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <h6><strong>{{ __('Total Session') }}</strong></h6>
                    <p>{{ $mentoring->total_session }}</p>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6><strong>{{ __('Session Datetime') }}</strong></h6>
                        <span class="text-muted small">
                            Lakukan sesi mentoring sesuai jadwal dan laporkan hasil penugasan.
                        </span>
                    </div>
                    @if ($mentoring->isProcessOrDone() && $hasIncompleteSessions)
                        <button class="btn btn-outline-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editSessionModal">
                            <i class="fa fa-edit"></i> Isi Kegiatan
                        </button>
                    @endif
                </div>
                @foreach($mentoring->mentoringSessions as $session)
                    @if(!empty($session->activity))
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                <strong>Pertemuan {{ $loop->iteration }}</strong>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}
                                    </small>
                                </div>

                                <div class="mb-2">
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
                            </div>
                        </div>
                    @else
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pertemuan {{ $loop->iteration }}</strong><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}
                                    </small>
                                </div>

                                @if(empty($session->activity))
                                    <span class="badge bg-warning text-dark">Belum ada aktivitas</span>
                                @endif
                            </li>
                        </ul>
                    @endif
                @endforeach

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6><strong>Laporan Akhir Mentoring</strong></h6>
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
                    @if (!$mentoring->final_report)
                    <div class="alert alert-info">
                        <strong>Ketentuan :</strong><br>
                        - Harap unggah laporan akhir yang telah <strong>ditandatangani oleh Mentor</strong>.<br>
                        - Format laporan akhir yang diunggah berupa file <strong>PDF</strong> dengan ukuran <strong>maksimal 5MB</strong>.<br>
                        - Laporan akhir dapat diunggah ketika Sesi Pertemuan Mentoring telah selesai dilakukan.
                    </div>
                    @endif
                    @if ($mentoring->isProcessOrDone() && !$hasIncompleteSessions)
                        @if ($mentoring->final_report)
                            <embed src="{{ route('student.mentee.view.document', ['id' => $mentoring->id, 'type' => 'final_report']) }}"
                                type="application/pdf"
                                width="100%"
                                height="500px"
                                class="border rounded shadow-sm" />
                        @else
                        <form action="{{ route('student.mentee.report', $mentoring->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="input-group mb-3">
                                <span class="input-group-text text-dark" style="cursor: pointer;" onclick="document.getElementById('final_report').click();">
                                    <i class="fa fa-file-pdf"></i> &nbsp;{{ __('Choose') }}
                                </span>
                                <input id="file_name" readonly class="form-control" type="text"
                                    placeholder="Belum ada file dipilih" onclick="document.getElementById('final_report').click();">

                                <input id="final_report" name="final_report" class="d-none" type="file"
                                    onchange="document.getElementById('file_name').value = this.files[0]?.name || '';"
                                    accept=".pdf" required>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-upload"></i> Unggah
                                </button>
                            </div>
                            <!-- <small class="form-text text-muted">
                                Berkas harus berupa <strong>PDF</strong> dan sudah ditandatangani oleh mentor.
                            </small> -->
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
<!-- Modal Edit Session -->
    <div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('student.mentee.update.session') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSessionModalLabel">Isi Detail Pertemuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal-session-id" class="form-label">Pilih Pertemuan<code>*</code></label>
                            <select class="form-select" name="session_id" id="modal-session-id" required>
                                <option value="" disabled selected>Pilih Jadwal Pertemuan</option>
                                @foreach($mentoring->mentoringSessions as $session)
                                    @if(empty($session->activity))
                                        <option value="{{ $session->id }}">Pertemuan {{ $loop->iteration }} - {{ \Carbon\Carbon::parse($session->mentoring_date)->translatedFormat('l, d F Y H:i') }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal-activity" class="form-label">Deskripsi Kegiatan<code>*</code></label>
                            <textarea class="form-control summernote" name="activity" id="modal-activity" style="height:150px;" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="modal-obstacle" class="form-label">Hambatan</label>
                            <textarea class="form-control summernote" name="obstacle" id="modal-obstacle" style="height:150px;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Dokumentasi<code>*</code></label>
                            <input type="file" class="form-control" name="image" id="image"  accept="image/jpeg,image/png" required>
                            <small class="form-text text-danger">
                                Berkas harus berupa <strong>JPG/PNG</strong> dengan ukuran maksimal 2MB.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button> -->
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
    $('#editSessionModal').on('shown.bs.modal', function () {
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
