@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Pendaftaran Tugas Belajar') }}</h4>
        </div>
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="instructor__profile-form-wrap">
                    <div class="row ">
                        <div class="col-xl-8 ">
                            <div class="dashboard__nav-wrap">
                                {{-- <h5 class="mb-4">Detail Pendaftaran Tugas Belajar</h5> --}}

                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="itemOne-tab" data-bs-toggle="tab"
                                            data-bs-target="#itemOne-tab-pane" type="button" role="tab"
                                            aria-controls="itemOne-tab-pane" aria-selected="true">Data Peserta</button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="itemFour-tab" data-bs-toggle="tab"
                                            data-bs-target="#itemFour-tab-pane" type="button" role="tab"
                                            aria-controls="itemFour-tab-pane" aria-selected="false"
                                            tabindex="-1">Dokumen</button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="itemSix-tab" data-bs-toggle="tab"
                                            data-bs-target="#itemSix-tab-pane" type="button" role="tab"
                                            aria-controls="itemSix-tab-pane" aria-selected="false"
                                            tabindex="-1">Lampiran</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="itemSeven-tab" data-bs-toggle="tab"
                                            data-bs-target="#itemSeven-tab-pane" type="button" role="tab"
                                            aria-controls="itemSeven-tab-pane" aria-selected="false"
                                            tabindex="-1">Laporan</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="itemOne-tab-pane" role="tabpanel"
                                    aria-labelledby="itemOne-tab" tabindex="0">
                                    <div class="dashboard__review-table " style="overflow: hidden;">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('NIP') }}</td>
                                                    <td>
                                                        Informatika
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Nama Pegawai') }}</td>
                                                    <td>
                                                        Nabila Sholihah, S.
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>{{ __('Jabatan / Pangkat') }}</td>
                                                    <td>
                                                        Pranata Komputer Pertama / Penata Muda
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Golongan') }}</td>
                                                    <td>
                                                        III/b
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Unit Kerja') }}</td>
                                                    <td>
                                                        Dinas Komunikasi dan Informatika Kabupaten Bantul
                                                    </td>
                                                    <td class="text-center">
                                                        <i
                                                            class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Jenjang/Program Studi') }}</td>
                                                    <td>
                                                        S2/Informatika
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Tahun Tubel') }}</td>
                                                    <td>
                                                        2025
                                                    </td>
                                                    <td class="text-center">
                                                        <i
                                                            class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="itemFour-tab-pane" role="tabpanel"
                                    aria-labelledby="itemFour-tab" tabindex="0">

                                    <div class="dashboard__review-table " style="overflow: hidden;">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td width  = "80%"> Surat Pernyataan Kesanggupan Biaya.pdf</td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>

                                                    <td class="text-center">
                                                        <a href="#" class="align-middle" data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Scan Ijazah Terakhir') }} </td>
                                                    <td>
                                                        <div class="badge bg-danger-subtle text-danger ">
                                                            {{ __('Rejected') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle" data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Letter of Acceptance (LoA)') }} </td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Surat Usulan Dari Perangkat Daerah') }}
                                                    </td>
                                                    <td>
                                                        <div class="badge bg-danger-subtle text-danger ">
                                                            {{ __('Rejected') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('SK Akreditasi Program Studi') }} </td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Jadwal Perkuliahan') }} </td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Surat Keterangan Sehat') }} </td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width  = "80%">{{ __('Surat Keterangan ') }} </td>
                                                    <td>
                                                        <div class="badge bg-success-subtle text-success ">
                                                            {{ __('Approved') }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="#" class="align-middle " data-bs-toggle="tooltip"
                                                            title="Lihat Berkas">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="table table-borderless">
                                            <div class="blog__details-bottom">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-7">
                                                        <div class="tg-post-tag">
                                                            <h5 class="tag-title">Status Assesment :</h5>
                                                            <ul class="list-wrap p-0 mb-0">
                                                                <li><a class="bg-danger text-white " href="javascript:;"> <strong>Ditolak</strong> </a></li>
                                                                <li>
                                                                    <span tabindex="0" class="fa fa-info-circle text-dark"
                                                                        data-bs-toggle="popover"
                                                                        data-bs-trigger="hover focus"
                                                                        data-bs-placement="top"
                                                                        data-bs-content="Alasan : karna kamu terlalu baik">
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="itemSix-tab-pane" role="tabpanel"
                                    aria-labelledby="itemSix-tab" tabindex="0">
                                    {{-- <div class="faq__wrap "> --}}
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse2" aria-expanded="false"
                                                    aria-controls="collapse2">
                                                    Dokumen Perjanjian Kerja
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse show"
                                                data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">
                                                    <iframe
                                                        src="https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf"
                                                        width="100%" height="500px"></iframe>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse1"
                                                    aria-expanded="true" aria-controls="collapse1">
                                                    Dokumen SK Peserta
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">
                                                    <iframe
                                                        src="https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf"
                                                        width="100%" height="500px"></iframe>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse3"
                                                    aria-expanded="false" aria-controls="collapse3">
                                                    Dokumen Petikan
                                                </button>
                                            </h2>
                                            <div id="collapse3" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">
                                                    <iframe
                                                        src="https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf"
                                                        width="100%" height="500px"></iframe>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- </div> --}}

                                </div>

                                <div class="tab-pane fade" id="itemSeven-tab-pane" role="tabpanel"
                                    aria-labelledby="itemSeven-tab" tabindex="0">
                                    <div class="instructor__profile-form-wrap">
                                        <form action="#" method="POST" enctype="multipart/form-data"
                                            class="instructor__profile-form">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-grp">
                                                        <label for="gender">Pilih Semester</label>
                                                        <select name="gender" id="gender" class="form-select">
                                                            <option value="1">Semester 1</option>
                                                            <option value="2">Semester 2</option>
                                                            <option value="3">Semester 3</option>
                                                            <option value="4">Semester 4</option>
                                                            <option value="5">Semester 5</option>
                                                            <option value="6">Semester 6</option>
                                                            <option value="7">Semester 7</option>
                                                            <option value="8">Semester 8</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-grp">
                                                        <label for="age">File Laporan</label>
                                                        <input id="age" name="age" type="file"
                                                            value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <label for="age"></label>

                                                    <div class="submit-btn">
                                                        <button type="submit" class="btn"><i
                                                                class="fa fa-upload"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Tabel Pemantauan File -->
                                        <div class="dashboard__review-table mt-4" style="overflow: hidden;">
                                            <h6 class="mb-3">Daftar Laporan per Semester</h6>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Semester</th>
                                                        <th>Nama File</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data Dummy -->
                                                    <tr>
                                                        <td>1</td>
                                                        <td>laporan_semester1.pdf</td>
                                                        <td><span class="badge bg-success">Disetujui</span></td>
                                                        <td>
                                                            <a href="#" class="text-primary"><i
                                                                    class="fa fa-pencil-alt"></i></a>
                                                            <a href="#" class="text-danger delete-item"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>laporan_semester2.pdf</td>
                                                        <td><span class="badge bg-warning">Ditinjau</span></td>
                                                        <td>
                                                            <a href="#" class="text-primary"><i
                                                                    class="fa fa-pencil-alt"></i></a>
                                                            <a href="#" class="text-danger delete-item"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>laporan_semester3.pdf</td>
                                                        <td>
                                                            <span class="badge bg-danger cursor-pointer"
                                                                style="cursor: pointer;" data-bs-toggle="popover"
                                                                data-bs-content="Alasan ditolak: Dokumen tidak sesuai format"
                                                                data-bs-placement="top">
                                                                <i class="fa fa-comment-alt text-white me-1"></i> Ditolak
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-primary"><i
                                                                    class="fa fa-pencil-alt"></i></a>
                                                            <a href="#" class="text-danger delete-item"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>-</td>
                                                        <td><span class="badge bg-secondary">Belum Diupload</span></td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tugas Akhir</td>
                                                        <td>-</td>
                                                        <td><span class="badge bg-secondary">Belum Diupload</span></td>
                                                        <td>-</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-1">

                        </div>
                        <div class="col-xl-3 p-3">
                            {{-- <div class="instructor__details-Skill"> --}}
                            <h5 class="mb-4">Timeline</h5>
                            <div class="bsb-timeline-1">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="timeline">
                                                <li class="timeline-item">
                                                    <div class="timeline-body">
                                                        <div class="timeline-content">
                                                            <div class="card border-0">
                                                                <div class="card-body p-0">
                                                                    <h6 class="card-subtitle text-secondary mb-1">
                                                                        1 Jan - 1 Feb 2025
                                                                    </h6>
                                                                    <h6 class="card-title mb-3">Pengajuan Berkas
                                                                        Administrasi</h6>
                                                                    <p>Calon peserta mengajukan berkas pendaftaran
                                                                        pendidikan.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="timeline-item  active-step">
                                                    <div class="timeline-body">
                                                        <div class="timeline-content">
                                                            <div class="card border-0">
                                                                <div class="card-body p-0">
                                                                    <h6 class="card-subtitle text-secondary mb-1">
                                                                        10 Feb - 15 Feb 2025
                                                                    </h6>
                                                                    <h6 class="card-title mb-3">Verifikasi dan Validasi
                                                                        Berkas</h6>
                                                                    <p>Berkas yang diajukan diperiksa dan diverifikasi
                                                                        oleh
                                                                        pihak terkait.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="timeline-item">
                                                    <div class="timeline-body">
                                                        <div class="timeline-content">
                                                            <div class="card border-0">
                                                                <div class="card-body p-0">
                                                                    <h6 class="card-subtitle text-secondary mb-1">
                                                                        16 Feb - 17 Feb 2025
                                                                    </h6>
                                                                    <h6 class="card-title mb-3">Pengumuman Hasil
                                                                        Seleksi
                                                                    </h6>
                                                                    <p>Peserta yang lolos seleksi diumumkan dan
                                                                        diberikan
                                                                        surat keputusan penerimaan.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="timeline-item">
                                                    <div class="timeline-body">
                                                        <div class="timeline-content">
                                                            <div class="card border-0">
                                                                <div class="card-body p-0">
                                                                    <h6 class="card-subtitle text-secondary mb-1">
                                                                        17 Feb - 20 Feb 2025
                                                                    </h6>
                                                                    <h6 class="card-title mb-3">Pendaftaran Ulang</h6>
                                                                    <p>Peserta yang lolos melakukan pendaftaran ulang
                                                                        dan
                                                                        melengkapi dokumen administrasi.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            width: 15px;
            height: 15px;
            background: #5751e1;
            border-radius: 50%;
        }

        .active-step::before {
            animation: blink 0.5s infinite alternate ease-in-out;
        }

        @keyframes blink {
            0% {

                background: #ffc224;
                box-shadow: 0 0 20px #ffc224;
            }

            100% {

                background: #5751e1;
                box-shadow: 0 0 0px #5751e1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
@endpush
