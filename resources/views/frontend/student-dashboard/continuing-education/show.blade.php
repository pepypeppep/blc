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
                                        $studyProgram = $vacancy->details->firstWhere('type', 'study_program');
                                    @endphp
                                    {{ $studyProgram ? $studyProgram->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Jenjang Pendidikan') }}</td>
                                <td>
                                    @php
                                        $educationLevel = $vacancy->details->firstWhere('type', 'education_level');
                                    @endphp
                                    {{ $educationLevel ? $educationLevel->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>

                            </tr>
                            <tr>
                                <td>{{ __('Syarat Minimal Pangkat/Golongan') }}</td>
                                <td>
                                    @php
                                        $minimumRank = $vacancy->details->firstWhere('type', 'minimum_rank');
                                    @endphp
                                    {{ $minimumRank ? $minimumRank->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Status Kepegawaian') }}</td>
                                <td>
                                    @php
                                        $employmentStatus = $vacancy->details->firstWhere('type', 'employment_status');
                                    @endphp
                                    {{ $employmentStatus ? $employmentStatus->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Pembiayaan') }}</td>
                                <td>
                                    @php
                                        $fundingSource = $vacancy->details->firstWhere('type', 'funding_source');
                                    @endphp
                                    {{ $fundingSource ? $fundingSource->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Jumlah Formasi') }}</td>
                                <td>
                                    @php
                                        $formasiCount = $vacancy->details->firstWhere('type', 'formasi_count');
                                    @endphp
                                    {{ $formasiCount ? $formasiCount->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Batas Usia Pensiun') }}</td>
                                <td>
                                    @php
                                        $retirementAge = $vacancy->details->firstWhere('type', 'retirement_age');
                                    @endphp
                                    {{ $retirementAge ? $retirementAge->value_type : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Mulai Pendaftaran') }}</td>
                                <td>{{ $vacancy->start_at ? \Carbon\Carbon::parse($vacancy->start_at)->format('d F Y') : '-' }}</td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Selesai Pendaftaran') }}</td>
                                <td>{{ $vacancy->end_at ? \Carbon\Carbon::parse($vacancy->end_at)->format('d F Y') : '-' }}</td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- Div baru untuk status pemenuhan syarat -->
        <div class="row">
            <div class="col-12">
                <div class="mt-3 p-3">
                    <div class="courses__item-tag"
                        style="height: 80px; background: #EFEFF2; border-radius: 10px; padding: 7px 13px; display: flex; align-items: center; justify-content: center; text-align: center;">
                        {{-- @if ($vacancy->status) --}}
                        <p style="margin: 0; font-size: 17px; color: #161439; font-weight: 600;">Anda memenuhi semua syarat
                            Pendidikan Lanjutan</p>
                        {{-- @else
                            <p style="margin: 0; font-size: 17px; color: #161439; font-weight: 600;">Anda tidak memenuhi semua syarat untuk Pendidikan Lanjutan</p>
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


        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive mt-4">
                    <h5 class="title">{{ __('Lampiran yang Diperlukan') }}</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Jenis Lampiran') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Unggah Berkas') }}</th>
                                <th class="text-center">{{ __('Lihat Berkas') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ __('Surat Pernyataan') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ __('Lengkap') }}</span>
                                </td>
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
                                <td>{{ __('SK Pangkat Terakhir') }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ __('Belum Lengkap') }}</span>
                                </td>
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
                                <td>{{ __('Ijazah Terakhir') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ __('Lengkap') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="align-middle " data-bs-toggle="tooltip" title="Lihat Berkas">
                                        <i class="fas fa-times"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
