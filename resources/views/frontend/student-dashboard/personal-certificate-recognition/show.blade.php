@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="alert alert-{{ $pengakuan->stat['color'] }} d-flex justify-content-between" role="alert">
                        <div><span class="text text-capitalize">Status:
                                <strong>{{ $pengakuan->stat['label'] }}</strong></span></div>
                        <div class="courses__item-bottom m-0">
                            <div class="button">
                                <a href="{{ route('student.pengakuan-sertifikat.edit', $pengakuan->id) }}"
                                    class="already-enrolled-btn bg-primary" data-id="">
                                    <i class="fa fa-pencil-alt text-white"></i>
                                </a>
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
                            <label for="competency_development_id"><strong>Jalur Pengembangan Kompetensi</strong>
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
                            <label for="official_position"><strong>Jabatan Pejabat yang mengeluarkan</strong></label>
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
@endsection
