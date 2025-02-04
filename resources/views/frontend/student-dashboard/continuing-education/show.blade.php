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
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
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
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>
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
                                <td>{{ __('Batas Usia Pensiun') }}</td>
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
                                <td>{{ $vacancy->open_at ? \Carbon\Carbon::parse($vacancy->open_at)->format('d F Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Selesai Pendaftaran') }}</td>
                                <td>{{ $vacancy->close_at ? \Carbon\Carbon::parse($vacancy->close_at)->format('d F Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>
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
            <div class="col-12">
                <h5 class="title">{{ __('Lampiran yang Diperlukan') }}</h5>
                <div class="dashboard__review-table table-responsive mt-4">
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
                            @foreach ($vacancyConditions as $condition)
                                <tr>
                                    <form id="{{ $condition->id }}_form"
                                        action="{{ route('student.continuing-education.attachment') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $condition->name }}</td>
                                        <td>
                                            @if (count($condition->attachment) == 0)
                                                <span class="badge bg-warning">{{ __('Belum Lengkap') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('Lengkap') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="attachment_id" value="{{ $condition->id }}"
                                                    hidden />
                                                @if (count($condition->attachment) > 0)
                                                    <input type="text" @if (count($condition->attachment) != 0) disabled @endif
                                                        class="form-control me-2"
                                                        value="{{ $condition->attachment[0]->file }}" />
                                                @else
                                                    <input type="file" name="file"
                                                        @if ($condition->type == 'pdf') accept="application/pdf" @endif
                                                        @if (count($condition->attachment) != 0) disabled @endif
                                                        class="form-control me-2"
                                                        onchange="$('#{{ $condition->id }}_form').trigger('submit')" />
                                                @endif

                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if (count($condition->attachment) > 0)
                                                <a target="_blank"
                                                    href="{{ route('vacancies-participant.get.file', [$condition->attachment[0]->vacancy_attachment_id, auth()->user()->id]) }}"
                                                    class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                                </a>
                                            @else
                                                <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 24px; height: 24px; font-size: 16px;"></i>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-end">
                <form action="{{ route('student.continuing-education.register', $vacancy->id) }}" method="POST"
                    class="d-inline">
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
