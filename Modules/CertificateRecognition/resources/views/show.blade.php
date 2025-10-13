@extends('admin.master_layout')
@php
    use Modules\CertificateRecognition\app\Models\CertificateRecognition;
@endphp
@section('title')
    <title>{{ __('Certificate Recognition') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Certificate Recognition') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body row">
                                <div class="alert alert-{{ $pengakuan->stat['color'] }} d-flex justify-content-between w-100"
                                    role="alert">
                                    <div class="my-auto">
                                        <span class="text text-capitalize">Status:
                                            <strong>{{ $pengakuan->stat['label'] }}</strong>
                                        </span>
                                        @if ($pengakuan->status == 'Rejected')
                                            <div class="mt-2"><strong>Alasan:</strong></div>
                                            <div>{!! clean($pengakuan->article->note) !!}</div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="">
                                            <div class="btn btn-info">
                                                <a href="{{ route('admin.knowledge.detail', $pengakuan->article->id) }}"
                                                    class="already-enrolled-btn" data-id="">
                                                    <i class="fa fa-eye"></i> Lihat Pengetahuan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="form-group">
                                        <label for="title"><strong>Nama Pengembangan Kompetensi</strong></label>
                                        <div>{{ $pengakuan->title }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="competency_development_id"><strong>Jalur Pengembangan
                                                Kompetensi</strong>
                                        </label>
                                        <div>{{ $pengakuan->competency_development->name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="organization"><strong>Penyelenggara</strong></label>
                                        <div>{{ $pengakuan->organization }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="start_date"><strong>Tanggal Mulai</strong></label>
                                        <div>{{ $pengakuan->start_date }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="end_date"><strong>Tanggal Selesai</strong></label>
                                        <div>{{ $pengakuan->end_date }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="official_position"><strong>Jabatan Pejabat yang
                                                mengeluarkan</strong></label>
                                        <div>{{ $pengakuan->official_position }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="graduation_predicate"><strong>Predikat Kelulusan</strong></label>
                                        <div>{{ $pengakuan->graduation_predicate ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">
                                        <label for="certificate_number"><strong>Nomor Sertifikat</strong></label>
                                        <div>{{ $pengakuan->certificate_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">
                                        <label for="certificate_date"><strong>Tanggal Sertifikat</strong></label>
                                        <div>{{ $pengakuan->certificate_date }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">
                                        <label for="jp"><strong>Jumlah Jam Pelajaran</strong></label>
                                        <div>{{ $pengakuan->jp }}</div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2" id="file-upload-field2">
                                    <div class="form-group">
                                        <label for="file"><strong>Laporan Pengembangan Kompetensi</strong></label>
                                        <div>
                                            <object
                                                data="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'report_file']) }}"
                                                type="application/pdf" width="100%" height="500px">
                                                <embed
                                                    src="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'report_file']) }}"
                                                    type="application/pdf">
                                            </object>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2" id="file-upload-field3">
                                    <div class="form-group">
                                        <label for="file"><strong>Sertifikat</strong></label>
                                        <div>
                                            <object
                                                data="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'certificate_file']) }}"
                                                type="application/pdf" width="100%" height="500px">
                                                <embed
                                                    src="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'certificate_file']) }}"
                                                    type="application/pdf">
                                            </object>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2" id="file-upload-field4">
                                    <div class="form-group">
                                        <label for="file"><strong>Piagam Penghargaan / Sejenisnya</strong></label>
                                        <div>
                                            <object
                                                data="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'award_file']) }}"
                                                type="application/pdf" width="100%" height="500px">
                                                <embed
                                                    src="{{ route('student.pengakuan-sertifikat.attachment', [$pengakuan->id, 'award_file']) }}"
                                                    type="application/pdf">
                                            </object>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
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

    <script>
        function handleApprove(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to approve this certificate?') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('Yes, approve it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#verification-status').val('approved');
                    $('#verification-form').submit();
                }
            });
        }

        function handleReject(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to reject this certificate?') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('Yes, reject it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __('Explain the reason for rejection...') }}',
                        input: 'text',
                        inputPlaceholder: '{{ __('Explain the reason for rejection...') }}',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('Submit') }}',
                        cancelButtonText: '{{ __('Cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#verification-status').val('rejected');
                            $('#verification-reason').val(result.value);
                            $('#verification-form').submit();
                        }
                    });
                }
            });
        }
    </script>
@endpush
@push('css')
    <style>
        .render-content {
            font-size: 1.1rem !important;
        }
    </style>
@endpush
