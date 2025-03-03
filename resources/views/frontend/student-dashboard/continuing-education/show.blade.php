@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    @php
        $isExist = count($vacancy->users) > 0;
    @endphp
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
                                <td></td>
                            </tr>
                            <tr>
                                <td>{{ __('Jenjang Pendidikan') }}</td>
                                <td>
                                    @php
                                        $educationLevel = $vacancy->educationLevel();
                                    @endphp
                                    {{ $educationLevel ? $educationLevel : '-' }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>{{ __('Syarat Pangkat/Golongan') }}</td>
                                <td>
                                    @php
                                        $minimumRank = $vacancy->employment_grade;
                                    @endphp
                                    {{ $minimumRank ? $minimumRank : '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($passEmployeeGrade)
                                        <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                    @else
                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                    @endif
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
                                    @if ($passAgeLimit)
                                        <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                    @else
                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                    @endif
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
                                <td></td>
                                {{-- <td class="text-center">
                                    @if ($passEmploymeeStatus)
                                        <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                    @else
                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                    @endif
                                </td> --}}
                            </tr>
                            <tr>
                                <td>{{ __('Pembiayaan') }}</td>
                                <td>
                                    @php
                                        $fundingSource = $vacancy->cost_type;
                                    @endphp
                                    {{ $fundingSource ? $fundingSource : '-' }}
                                </td>
                                <td></td>
                                {{-- <td class="text-center">
                                    <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>
                                </td> --}}
                            </tr>
                            <tr>
                                <td>{{ __('Jumlah Formasi') }}</td>
                                <td>
                                    @php
                                        $formasiCount = $vacancy->formation;
                                    @endphp
                                    {{ $formasiCount ? $formasiCount : '-' }}
                                </td>
                                <td></td>
                                {{-- <td class="text-center">
                                    <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                </td> --}}
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Mulai Pendaftaran') }}</td>
                                <td>{{ $vacancy->open_at ? \Carbon\Carbon::parse($vacancy->open_at)->format('d F Y') : '-' }}
                                </td>
                                <td></td>

                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Penutupan Pendaftaran') }}</td>
                                <td>{{ $vacancy->close_at ? \Carbon\Carbon::parse($vacancy->close_at)->format('d F Y') : '-' }}
                                </td>
                                <td></td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <div class="p-3">
                    @if (!$isExist)
                        @if ($passAgeLimit && $passEmployeeGrade)
                            <div style="background: #d3ffde;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-success">
                                {{-- @if ($vacancy->status) --}}
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Anda memenuhi semua syarat Pendidikan Lanjutan
                                </p>
                            </div>
                        @elseif (!$passAgeLimit || !$passEmployeeGrade)
                            <div style="background: #ffd3d3;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-danger">
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Anda belum memenuhi syarat Pendidikan Lanjutan
                                </p>
                            </div>
                        @endif
                    @elseif($isExist)
                        <div style="background: #ffc224"
                            class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4">
                            <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                Anda telah mendaftar pada Pendidikan Lanjutan
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($passAgeLimit && $passEmployeeGrade)
            <div class="row" id="attachment_container">
                <h5 class="heading-title mt-5">{{ __('Lampiran yang Diperlukan') }}</h5>
                <div class="col-12">
                    <div class="dashboard__review-table mt-2 " style="overflow: hidden;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Jenis Lampiran') }}</th>
                                    <th class="text-center">{{ __('Aksi') }}</th>
                                    <th>{{ __('Berkas') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vacancyConditions as $condition)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $condition->name }} <span class="text-danger">*</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if (!$isExist && count($condition->attachment) != 0)
                                                    <form id="{{ $condition->id }}_form_action"
                                                        action="{{ route('student.continuing-education.attachment', $condition->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input id="{{ $condition->id }}_file" type="file" name="file"
                                                            @if ($condition->type == 'pdf') accept="application/pdf" @endif
                                                            @if (count($condition->attachment) == 0) disabled @endif
                                                            class="form-control me-2"
                                                            onchange="$('#{{ $condition->id }}_form_action').trigger('submit')"
                                                            hidden />
                                                        <button onclick="$('#{{ $condition->id }}_file').click()"
                                                            type="button" class="align-middle border-0 bg-transparent"
                                                            data-bs-toggle="tooltip" title="Unggah Ulang Berkas">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if (count($condition->attachment) > 0)
                                                    <a target="_blank"
                                                        href="{{ route('vacancies-participant.get.file', [$condition->attachment[0]->vacancy_attachment_id, auth()->user()->id]) }}"
                                                        class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="attachment_id" value="{{ $condition->id }}"
                                                    hidden />
                                                @if (count($condition->attachment) > 0)
                                                    <input type="text" @if (count($condition->attachment) != 0) disabled @endif
                                                        class="form-control me-2"
                                                        value="{{ $condition->attachment[0]->file }}" />
                                                @elseif (!$isExist)
                                                    <form id="{{ $condition->id }}_form"
                                                        action="{{ route('student.continuing-education.attachment', $condition->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="file" name="file"
                                                            @if ($condition->type == 'pdf') accept="application/pdf" @endif
                                                            @if (count($condition->attachment) != 0) disabled @endif
                                                            class="form-control me-2"
                                                            onchange="$('#{{ $condition->id }}_form').trigger('submit')" />
                                                    </form>
                                                @else
                                                    <input type="text" class="form-control me-2" disabled />
                                                @endif

                                            </div>
                                        </td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="p-3">
                        @if ($meetCondition)
                            <div style="background: #d3ffde;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-success">
                                {{-- @if ($vacancy->status) --}}
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Berkas telah dilengkapi
                                </p>
                            </div>
                        @else
                            <div style="background: #ffd3d3;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-danger">
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Harap lengkapi berkas diatas untuk dapat melanjutkan pendaftaran
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12 text-end">
                @if (!$isExist && $meetCondition && $passAgeLimit && $passEmployeeGrade)
                    <form action="{{ route('student.continuing-education.register', $vacancy->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn mt-4 mb-3">
                            {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                @endif
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
