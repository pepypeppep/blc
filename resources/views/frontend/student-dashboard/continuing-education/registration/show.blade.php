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
                                                @foreach ($attachments as $attachment)
                                                    @if ($attachment->category == 'syarat')
                                                        <tr>
                                                            <td width  = "80%">{{ $attachment->vacancyattachment->name }}
                                                            </td>
                                                            <td>
                                                                <div class="badge bg-success-subtle text-success ">
                                                                    {{ __('Approved') }}</div>
                                                            </td>

                                                            <td class="text-center">
                                                                <a target="_blank"
                                                                    href="{{ route('vacancies-participant.get.file', [$attachment->vacancyattachment->id, auth()->user()->id]) }}"
                                                                    class="align-middle" data-bs-toggle="tooltip"
                                                                    title="Lihat Berkas">
                                                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                                                </a>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <table class="table table-borderless">
                                            <div class="blog__details-bottom">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-7">
                                                        <div class="tg-post-tag">
                                                            <h5 class="tag-title">Status Assesment :</h5>
                                                            <ul class="list-wrap p-0 mb-0">
                                                                <li><a class="bg-danger text-white " href="javascript:;">
                                                                        <strong>Ditolak</strong> </a></li>
                                                                <li>
                                                                    <span tabindex="0"
                                                                        class="fa fa-info-circle text-dark"
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
                                        @foreach ($attachments as $attachment)
                                            @if ($attachment->category == 'lampiran')
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target={{ '#collapse' . $attachment->id }}
                                                            aria-expanded="false"
                                                            aria-controls={{ '#collapse' . $attachment->id }}>
                                                            {{ $attachment->vacancyattachment->name }}
                                                        </button>
                                                    </h2>
                                                    <div id={{ 'collapse' . $attachment->id }}
                                                        class="accordion-collapse collapse"
                                                        data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body">
                                                            <object
                                                                data={{ Route('vacancies-participant.get.file', [$attachment->vacancyattachment->id, auth()->user()->id]) }}
                                                                width="100%" height="500px">
                                                                <span
                                                                    class="full-width rounded p-2 bg-danger text-white">Dokumen
                                                                    Tidak Ditemukan</span>
                                                            </object>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    {{-- </div> --}}

                                </div>

                                <div class="tab-pane fade" id="itemSeven-tab-pane" role="tabpanel"
                                    aria-labelledby="itemSeven-tab" tabindex="0">
                                    <div class="instructor__profile-form-wrap">
                                        <form
                                            action="{{ route('student.continuing-education.registration.report', $vacancy->id) }}"
                                            method="POST" enctype="multipart/form-data"
                                            class="instructor__profile-form">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-grp">
                                                        <label for="name">Pilih Semester</label>
                                                        <select name="name" id="name" class="form-select">
                                                            <option value="Semester 1">Semester 1</option>
                                                            <option value="Semester 2">Semester 2</option>
                                                            <option value="Semester 3">Semester 3</option>
                                                            <option value="Semester 4">Semester 4</option>
                                                            <option value="Semester 5">Semester 5</option>
                                                            <option value="Semester 6">Semester 6</option>
                                                            <option value="Semester 7">Semester 7</option>
                                                            <option value="Semester 8">Semester 8</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-grp">
                                                        <label for="file">File Laporan</label>
                                                        <input id="file" name="file" type="file"
                                                            accept=".pdf" value="">
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
                                                    @foreach ($reports as $report)
                                                        <tr>
                                                            <td>{{ $report->name }}</td>
                                                            <td>{{ $report->file }}</td>
                                                            <td><span
                                                                    class="badge bg-success">{{ $report->status }}</span>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-primary"><i
                                                                        class="fa fa-pencil-alt"></i></a>
                                                                <a href="#" class="text-danger delete-item"><i
                                                                        class="fas fa-trash-alt"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
                                                @foreach ($logs as $log)
                                                    <li class="timeline-item">
                                                        <div class="timeline-body">
                                                            <div class="timeline-content">
                                                                <div class="card border-0">
                                                                    <div class="card-body p-0">
                                                                        <h6 class="card-subtitle text-secondary mb-1">
                                                                            {{ formatDate($log->created_at) }}
                                                                        </h6>
                                                                        <h6 class="card-title mb-2">{{ $log->name }}
                                                                            <div class="badge bg-warning">
                                                                                {{ $log->status }}</div>
                                                                        </h6>
                                                                        <p>{{ $log->description }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
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
