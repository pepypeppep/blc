@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Program Pendidikan Lanjutan') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ __('Program Studi') }}</td>
                                <td>
                                    @php
                                        $studyProgram = $vacancy->study->name;
                                    @endphp
                                    {{ $studyProgram ? $studyProgram : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Jenjang Pendidikan') }}</td>
                                <td>
                                    @php
                                        $educationLevel = $vacancy->educationLevel();
                                    @endphp
                                    {{ $educationLevel ? $educationLevel : '-' }}
                                </td>

                            </tr>
                            <tr>
                                <td>{{ __('Syarat Minimal Pangkat/Golongan') }}</td>
                                <td>
                                    @php
                                        $minimumRank = $vacancy->employment_grade;
                                    @endphp
                                    {{ $minimumRank ? $minimumRank : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Status Kepegawaian') }}</td>
                                <td>
                                    @php
                                        $employmentStatus = $vacancy->employmentStatus();
                                    @endphp
                                    {{ $employmentStatus ? $employmentStatus : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Pembiayaan') }}</td>
                                <td>
                                    @php
                                        $fundingSource = $vacancy->cost_type;
                                    @endphp
                                    {{ $fundingSource ? $fundingSource : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Jumlah Formasi') }}</td>
                                <td>
                                    @php
                                        $formasiCount = $vacancy->formation;
                                    @endphp
                                    {{ $formasiCount ? $formasiCount : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Batas Usia') }}</td>
                                <td>
                                    @php
                                        $retirementAge = $vacancy->age_limit;
                                    @endphp
                                    {{ $retirementAge ? $retirementAge : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Mulai Pendaftaran') }}</td>
                                <td>{{ $vacancy->start_at ? \Carbon\Carbon::parse($vacancy->start_at)->format('d F Y') : '-' }}</td>

                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Penutupan Pendaftaran') }}</td>
                                <td>{{ $vacancy->end_at ? \Carbon\Carbon::parse($vacancy->end_at)->format('d F Y') : '-' }}</td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <h5 class="heading-title mt-5">{{ __('Lampiran yang Diperlukan') }}</h5>
            <div class="col-12">
                <div class="dashboard__review-table mt-2 " style="overflow: hidden;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Jenis Lampiran') }}</th>
                                <th>{{ __('Unggah Berkas') }}</th>
                                <th class="text-center">{{ __('Lihat Berkas') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ __('Surat Pernyataan Kesanggupan Biaya Mandiri (Mandiri) atau SK Penerima Beasiswa (APBD/Non APBD)') }}  <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>

                            </tr>
                            <tr>
                                <td>2</td>
                                <td>{{ __('Scan Ijazah Terakhir') }}   <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Letter of Acceptance (LoA)') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Surat Usulan Dari Perangkat Daerah') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('SK Akreditasi Program Studi') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Jadwal Perkuliahan') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Surat Keterangan Sehat') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Surat Keterangan ') }} <small class="text-danger fw-sm font-size-sm">*wajib</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <div class="p-3">
                    <div style="background: #EFEFF2;" class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4">
                         {{-- @if ($vacancy->status) --}}
                            <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                Anda memenuhi semua syarat Pendidikan Lanjutan
                            </p>
                        {{-- @else
                            <p class="mb-0 fs-5 text-dark fw-bold text-center">
                            Anda tidak memenuhi semua syarat untuk Pendidikan Lanjutan
                            </p>
                        @endif --}}

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-end">
                <form action="{{ route('vacancies-participant.register', $vacancy->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn mt-4 mb-3">
                        {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>



    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
