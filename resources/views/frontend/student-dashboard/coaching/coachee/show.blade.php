@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="">{{ __('Detail Tema Coaching') }}</h4>
            <a href="{{ route('student.coachee.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>

        <div class="mt-3 border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Lorem ipsum dolor sit amet</h5>
                <span class="badge fs-6 bg-success text-white">
                    Bergabung
                </span>
            </div>

            <div class="mb-3 border-top pt-3 mt-4">
                <h6 class="title">{{ __('Main Issue') }}</h6>
                <div>Lorem ipsum dolor sit amet</div>
            </div>

            <div class="mb-3">
                <h6 class="title">{{ __('Purpose') }}</h6>
                <div>Lorem ipsum dolor sit amet</div>
            </div>

            <div class="mb-3 d-flex align-items-center gap-4 border-top pt-3 mt-4">
                <div>
                    <h6 class="mb-1 title">{{ __('Coach') }}</h6>
                    <p class="mb-0">Unknown</p>
                </div>

                <div class="text-end flex-grow-1">
                    <h6 class="mb-1 title">{{ __('Surat Konsesus') }}</h6>
                    @if (false)
                        <a href="{{ route('student.coachee.view.document', ['id' => 1]) }}"
                            target="_blank" class="btn-outline-primary btn-sm">
                            <i class="fa fa-file-pdf"></i> Lihat Surat
                        </a>
                    @else
                        <p class="text-muted mb-0">Tidak ada file.</p>
                    @endif
                </div>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                <div>
                    <h6 class="title">{{ __('Session Datetime') }} <span title="Jumlah pertemuan">( 1 )</span></h6>
                    <span class="text-muted small">
                        Lakukan sesi coaching sesuai jadwal dan laporkan hasil penugasan.
                    </span>
                </div>
                @if (true)
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editSessionModal">
                        <i class="fa fa-edit"></i> Isi Kegiatan
                    </button>
                @endif
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapse-1" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapse-1">
                            <div class="d-block">
                                <div>
                                    <strong>Pertemuan 1</strong>
                                    <span class="badge bg-info">Terisi</span>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapse-1" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @if (true)
                                <div>
                                    <strong class="d-block">Deskripsi Kegiatan:</strong>
                                    <div class="text-body"><em>Tidak ada deskripsi kegiatan.</em></div>
                                </div>

                                <div class="mb-2">
                                    <strong class="d-block">Hambatan:</strong>
                                    <div class="text-body"><em>Tidak ada hambatan dicatat.</em></div>
                                </div>

                                <div class="mb-2">
                                    <strong class="d-block">Dokumentasi:</strong>
                                    <p class="text-muted"><em>Belum ada dokumentasi gambar.</em></p>
                                </div>
                            @else
                                <div class="text-center">
                                    <h4 class="text-muted">Belum ada kegiatan</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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
                @if (true)
                    <div class="alert alert-info">
                        <strong>Ketentuan :</strong><br>
                        - Harap unggah laporan akhir yang telah <strong>ditandatangani oleh Coach</strong>.<br>
                        - Format laporan akhir yang diunggah berupa file <strong>PDF</strong> dengan ukuran <strong>maksimal
                            5MB</strong>.<br>
                        - Laporan akhir dapat diunggah ketika Sesi Pertemuan Coaching telah selesai dilakukan.
                    </div>
                @endif
                <form action="{{ route('student.coachee.report', 1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="input-group mb-3">
                        <span class="input-group-text text-dark" style="cursor: pointer;"
                            onclick="document.getElementById('final_report').click();">
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
                </form>
            </div>
        </div>
        <div class="row">
                <div class="col-12 justify-content-between d-flex align-items-center">
                    <button type="button" onclick="handleTolakKonsesus(event, 1)"
                        class="btn-danger mt-4 mb-3">
                        {{ __('Tolak Konsesus') }} <i class="fa fa-arrow-right"></i>
                    </button>
                    <form action="{{ route('student.coachee.approve', 1) }}" method="POST" class="d-inline"
                        id="approve_konsesus">
                        @csrf
                        @method('POST')
                        <button type="button" class="btn mt-4 mb-3" onclick="handleApproveKonsesus(event)">
                            {{ __('Lanjutkan Proses Konsesus') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
    </div>
@endsection

@push('modals')
    <!-- Modal Edit Session -->
    <div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('student.coachee.update.session', 1) }}"
                    enctype="multipart/form-data">
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
                                <option value="1">Pertemuan 1 -
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}
                                </option>
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
                            <input type="file" class="form-control" name="image" id="image"
                                accept="image/jpeg,image/png" required>
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
                        url: base_url + "/student/coachee/" + id + "/reject",
                        data: {
                            _token: csrf_token,
                            reason: notes
                        },
                        success: function(data) {
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
