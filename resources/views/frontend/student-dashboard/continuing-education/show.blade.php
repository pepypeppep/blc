@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    @php
        $isExist = count($vacancy->users) > 0;
        $detailId = $vacancyUser->vacancy_detail_id ?? 0;
    @endphp
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Program Pendidikan Lanjutan') }}</h4>
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <label for="vacancy" class="form-label">Pilih Skema Pendidikan Lanjutan<span class="text-danger">
                        *</span></label>
                <select class="form-select" aria-label="Pilih Skema Pendidikan Lanjutan" id="skemaId"
                    onchange="skemaOnChange(this)" {{ $detailId ? 'disabled' : '' }}>
                    @foreach ($vacancyDetail as $item)
                        <option value="{{ $item->id }}" {{ $detailId == $item->id ? 'selected' : '' }}>
                            {{ $item->employment_status }} | {{ $item->cost_type }} | Batas
                            Usia Pensiun {{ $item->age_limit }}</option>
                    @endforeach
                </select>
            </div>
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
                                <td class="text-center">
                                    @if ($passJenjangPendidikanTerakhir)
                                        <i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>
                                    @else
                                        <i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px; font-size: 16px;"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr id="batasUsiaPensiunId">
                            </tr>
                            <tr id="statusKepegawaanId">
                            </tr>
                            <tr id="pembiayaanId">
                                <td>{{ __('Pembiayaan') }}</td>
                                {{-- <td>
                                    @php
                                        $fundingSource = $vacancyUser->cost_type;
                                    @endphp
                                    {{ $fundingSource ? $fundingSource : '-' }}
                                </td>
                                <td></td> --}}
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
                        <div id="statusContainer" class="text-end"></div>
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

        @if (isset($vacancyUser) && $vacancyUser->vacancy_detail_id && $vacancyUser->vacancy_detail_id != 0)
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
                                    {{-- <th>{{ __('Berkas') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vacancyConditions as $condition)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $condition->name }} <span class="text-danger">*</span></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                @if (!$isExist)
                                                    @if (count($condition->attachment) != 0)
                                                        <form id="{{ $condition->id }}_form_action"
                                                            action="{{ route('student.continuing-education.attachment', $condition->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input id="{{ $condition->id }}_file" type="file"
                                                                name="file"
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
                                                    @else
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
                                                    @endif
                                                @endif
                                                @if (count($condition->attachment) > 0)
                                                    <a target="_blank"
                                                        href="{{ route('vacancies-participant.get.file', [$condition->attachment[0]->vacancy_attachment_id, $vacancyUser->id]) }}"
                                                        class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
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
                @if (!$isExist && $meetCondition && $vacancyUser->status == 'register' && $vacancyUser->vacancy_detail_id && $vacancyUser->vacancy_detail_id != 0)
                    <form action="{{ route('student.continuing-education.ajukanBerkas', $vacancy->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn mt-4 mb-3">
                            {{ __('Ajukan Berkas Pendaftaran') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var schemas = @json($vacancyDetail);

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const selectSkemaId = document.getElementById('skemaId');
            const selectedSkemaId = @json($detailId);
            if (schemas.length > 0) {
                selectSkemaId.value = selectedSkemaId ? selectedSkemaId : schemas[0].id;
                selectSkemaId.dispatchEvent(new Event('change'));
            }
        });


        function skemaOnChange(e) {
            const value = e.value;
            const selectedData = schemas.find(item => item.id == value);
            const batasUsiaPensiunId = document.getElementById('batasUsiaPensiunId');
            const statusKepegawaanId = document.getElementById('statusKepegawaanId');
            const pembiayaanId = document.getElementById('pembiayaanId');
            const statusContainer = document.getElementById('statusContainer');

            batasUsiaPensiunId.innerHTML = '';
            statusKepegawaanId.innerHTML = '';
            pembiayaanId.innerHTML = '';

            if (selectedData) {
                const passAgeLimit = selectedData.age_limit <= {{ $bup }};
                const passJenjangPendidikanTerakhir = {{ $passJenjangPendidikanTerakhir ? 'true' : 'false' }};
                const vacancyDetailId = {{ $detailId }};

                var html = `
                <td>{{ __('Batas Usia Pensiun') }}</td>
                <td>${selectedData.age_limit}</td>
                <td class="text-center">
                `
                if (passAgeLimit) {
                    html += `<i class="fas fa-check bg-success-subtle text-success p-1 rounded-circle"></i>`
                } else {
                    html += `<i class="fas fa-times bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px; font-size: 16px;"></i>`
                }
                html += `</td>`

                batasUsiaPensiunId.innerHTML = html;


                var yie = ``
                if (passJenjangPendidikanTerakhir && passAgeLimit) {


                    if (!vacancyDetailId || vacancyDetailId == 0) {
                        yie = `<div style="background: #d3ffde;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-success">
                                {{-- @if ($vacancy->status) --}}
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Anda memenuhi semua syarat Pendidikan Lanjutan
                                </p>
                            </div>`;
                        yie += `<form action="{{ route('student.continuing-education.ajukanDaftar', $vacancy->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="schema_id" value="${selectedData.id}">
                                <button type="submit" class="btn mt-4 mb-3">
                                    {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
                                </button>
                            </form>`
                    } else {
                        yie = `<div style="background: #d3ffde;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-success">
                                {{-- @if ($vacancy->status) --}}
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Anda sudah terdaftar, silahkan upload berkas yang diperlukan
                                </p>
                            </div>`;
                    }

                } else {
                    yie = `<div style="background: #ffd3d3;"
                                class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-danger">
                                <p class="mb-0 fs-5 text-dark fw-bold text-center">
                                    Anda belum memenuhi syarat Pendidikan Lanjutan
                                </p>
                            </div>`;
                }

                statusContainer.innerHTML = yie;
            }

            if (selectedData) {
                var html = `
                <td>{{ __('Status Kepegawaian') }}</td>
                <td>${selectedData.employment_status}</td>
                <td></td>
                `

                statusKepegawaanId.innerHTML = html;
            }

            if (selectedData) {
                var html = `
                <td>{{ __('Pembiayaan') }}</td>
                <td>${selectedData.cost_type}</td>
                `

                pembiayaanId.innerHTML = html;
            }
        }
    </script>
@endpush
