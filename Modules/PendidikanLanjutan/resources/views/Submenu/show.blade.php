@extends('admin.master_layout')
@section('title')
    <title>
        {{ __('Registrant Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Registrant Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.vacancies.verification.index') }}">{{ __('Verification') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Registrant Details') }}</div>
                </div>
            </div>

            <div class="section-header-menu">
                <a href="#detail" class="btn btn-link " role="button">
                    <i class="fa fa-user" style="font-size: 18px;"></i> <br /> {{ __('Details') }}
                </a>
                <span class="separator"> > </span>
                <a href="#verif" class="btn btn-link" role="button">
                    <i class="fa fa-clipboard-check" style="font-size: 18px;"></i> <br /> {{ __('Verification') }}
                </a>
                <span class="separator"> > </span>
                <a href="#assessment"
                    class="btn btn-link {{ $vacancyUser->status === 'assessment' || $vacancyUser->status === 'passed' ? '' : 'disabled' }}"
                    role="button">
                    <i class="fa fa-tasks" style="font-size: 18px;"></i> <br /> {{ __('Assesment') }}
                </a>
                <span class="separator"> > </span>
                <a href="#ptb" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}"
                    role="button">
                    <i class="fa fa-file-contract" style="font-size: 18px;"></i> <br /> {{ __('Contract') }}
                </a>
                <span class="separator"> > </span>
                <a href="#sk" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}"
                    role="button">
                    <i class="fa fa-file-alt" style="font-size: 18px;"></i> <br /> {{ __('SK') }}
                </a>
                <span class="separator"> > </span>
                <a href="#laporan" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}"
                    role="button">
                    <i class="fa fa-file-invoice" style="font-size: 18px;"></i> <br /> {{ __('Report') }}
                </a>
                <span class="separator"> > </span>
                <a href="#perpanjang" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}"
                    role="button">
                    <i class="fa fa-calendar-check" style="font-size: 18px;"></i> <br /> {{ __('Extension') }}
                </a>
            </div>

            <div id="detail" class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Registrant Details') }}</div>
                                <hr>
                                <div class="registrant-info-header ml-2">{{ __('Registrant Information') }}</div>
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employee Id') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->nip }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Date and Place of Birth') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->place_of_birth }},
                                                    {{ $vacancyUser->user->date_of_birth }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employee Name') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Cost Type') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->cost_type }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employment Grade') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->employment_grade }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Last Education Level') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->education_level }},
                                                    {{ $vacancyUser->last_education }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Last Position') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->last_position }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Phone Number') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->phone ?? '-' }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employment Unit') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->instansi->name }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="registrant-info-header ml-2">{{ __('Requirements Document') }}</div>
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>{{ __('File') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    @foreach ($vacancyUserAttachments as $attachment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attachment->vacancyAttachment->name }}</td>
                                            <td>{{ $attachment->category }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm m-1" data-toggle="modal"
                                                    data-target="#pdfModal" title="Lihat Berkas"
                                                    onclick="setPDF('{{ $attachment->vacancyAttachment->name }}','{{ route('vacancies-participant.get.file', [$attachment->vacancy_attachment_id, $attachment->vacancyuser->id]) }}')">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="verif" class="section-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Verification') }}</div>
                                <hr>
                                @if ($vacancyUser->status === 'verification')
                                    <form
                                        action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" id="status">
                                        <div class="col-md-12 mb-2">
                                            <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('reason', \Modules\PendidikanLanjutan\app\Models\VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('status', 'verification')->latest()->first()->draft_notes) }}</textarea>
                                            @error('reason')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mt-3 d-flex justify-content-between">
                                            <div>
                                                <button type="submit" class="btn btn-danger btn-icon icon-left print-btn"
                                                    onclick="document.getElementById('status').value='rejected'"><i
                                                        class="fas fa-times"></i>
                                                    {{ __('Reject') }}</button>
                                                <button type="submit"
                                                    class="btn btn-warning btn-icon icon-left print-btn"
                                                    onclick="document.getElementById('status').value='draft_verification'"><i
                                                        class="fas fa-hourglass"></i>
                                                    {{ __('Draft') }}</button>
                                            </div>
                                            <button type="submit"class="btn btn-primary btn-icon icon-left print-btn"
                                                onclick="document.getElementById('status').value='assessment'"><i
                                                    class="fas fa-check"></i>
                                                {{ __('Verification') }}</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="col-md-12 mb-2">
                                        <div class="alert alert-success alert-has-icon alert-dismissible"
                                            id="verifiedAlert">
                                            <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                            <div class="alert-body">
                                                <div class="alert-title">Lolos Verifikasi!</div>
                                                {!! $sectionLog->verifLogs[0]->description ??
                                                    'Pendaftar telah lolos tahap verifikasi berkas dan akan melanjutkan ke tahap
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                assessment.' !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (
                $vacancyUser->status == 'assessment' ||
                    $vacancyUser->status == 'eligible' ||
                    $vacancyUser->status == 'ineligible' ||
                    $vacancyUser->status === 'activation' ||
                    $vacancyUser->status === 'done')
                <div id="assessment" class="section-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="section-title">{{ __('Assesment') }}</div>
                                    <hr>
                                    @if ($vacancyUser->status === 'assessment')
                                        <form
                                            action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" id="status">
                                            <div class="col-md-12 mb-2">
                                                <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                                <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('reason', \Modules\PendidikanLanjutan\app\Models\VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('status', 'assessment')->latest()->first()->draft_notes) }}</textarea>
                                                @error('reason')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mt-3 d-flex justify-content-between">
                                                <div>
                                                    <button type="submit"
                                                        class="btn btn-danger btn-icon icon-left print-btn"
                                                        onclick="document.getElementById('status').value='ineligible'"><i
                                                            class="fas fa-times"></i>
                                                        {{ __("Didn't Pass") }}</button>
                                                    <button type="submit"
                                                        class="btn btn-warning btn-icon icon-left print-btn"
                                                        onclick="document.getElementById('status').value='draft_assessment'"><i
                                                            class="fas fa-hourglass"></i>
                                                        {{ __('Draft') }}</button>
                                                </div>
                                                <button type="submit"class="btn btn-primary btn-icon icon-left print-btn"
                                                    onclick="document.getElementById('status').value='eligible'"><i
                                                        class="fas fa-check"></i>
                                                    {{ __('Pass') }}</button>
                                            </div>
                                        </form>
                                    @elseif ($vacancyUser->status === 'eligible')
                                        <div class="col-md-12 mb-2">
                                            <div class="alert alert-success alert-has-icon alert-dismissible"
                                                id="verifiedAlert">
                                                <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                                <div class="alert-body">
                                                    <div class="alert-title">Lolos Assesment!</div>
                                                    {!! $sectionLog->assLogs[0]->description ??
                                                        'Pendaftar telah lolos tahap assessment dan akan melanjutkan ke tahap selanjutnya.' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($vacancyUser->status === 'ineligible')
                                        <div class="col-md-12 mb-2">
                                            <div class="alert alert-danger alert-has-icon alert-dismissible"
                                                id="verifiedAlert">
                                                <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                                <div class="alert-body">
                                                    <div class="alert-title">Tidak Lolos Assesment!</div>
                                                    {!! $sectionLog->assLogs[0]->description ??
                                                        'Pendaftar dinyatakan tidak lolos tahap assessment dan tidak dapat melanjutkan ke tahap selanjutnya.' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Lampiran --}}
            @if (
                $vacancyUser->status === 'eligible' ||
                    $vacancyUser->status === 'report' ||
                    $vacancyUser->status === 'extend' ||
                    $vacancyUser->status === 'activation' ||
                    $vacancyUser->status === 'done')
                <div id="sk" class="section-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="section-title">
                                        <div class="d-flex justify-content-between">
                                            {{ __('Draft SK') }}
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <table class="table table-striped table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th data-width="40">#</th>
                                                <th>{{ __('File') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $ath_total = 0;
                                            @endphp
                                            @foreach ($vacancyAttachments as $attachment)
                                                <tr>
                                                    @php
                                                        $ath = \Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment::where(
                                                            'vacancy_attachment_id',
                                                            $attachment->id,
                                                        )
                                                            ->where('vacancy_user_id', $vacancyUser->id)
                                                            ->first();
                                                        if ($ath) {
                                                            if ($ath->status == 'final') {
                                                                $ath_total = $ath_total + 1;
                                                            }
                                                        }
                                                    @endphp
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attachment->name }}</td>
                                                    <td>
                                                        @if ($ath)
                                                            @if ($ath->status == 'draft')
                                                                <span class="badge badge-info">Draft</span>
                                                            @elseif ($ath->status == 'assign')
                                                                <span class="badge badge-primary">Diajukan</span>
                                                            @elseif ($ath->status == 'final')
                                                                <span class="badge badge-success">Final</span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-danger">Belum Tersedia</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- <a href="javascript:void(0);" class="btn btn-warning btn-sm m-1"
                                                            title="Unduh Template">
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </a> --}}
                                                        @if ($vacancyUser->status === 'eligible')
                                                            <button class="btn btn-info btn-sm m-1" data-toggle="modal"
                                                                data-target="#uploadModal"
                                                                title="Unggah {{ $attachment->name }}">
                                                                <i class="fa fa-upload" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                        {{-- @else
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-success btn-sm m-1"
                                                                title="Kirim {{ $attachment->name }}">
                                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                            </a>
                                                        @endif --}}
                                                        @if ($attachment->name != 'Perjanjian Kinerja' && $ath)
                                                            <button class="btn btn-primary px-2 btn-sm m-1"
                                                                data-toggle="modal" data-target="#pdfModal"
                                                                title="Lihat Draft {{ $attachment->name }}"
                                                                onclick="setPDF('Draft {{ $attachment->name }}','{{ route('vacancies-participant.get.draft.file', [$attachment->id, $ath->vacancy_user_id]) }}')">
                                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                        @if ($ath)
                                                            <button class="btn btn-danger px-2 btn-sm m-1"
                                                                data-toggle="modal" data-target="#pdfModal"
                                                                title="Lihat {{ $attachment->name }}"
                                                                onclick="setPDF('{{ $attachment->name }}','{{ route('vacancies-participant.get.file', [$attachment->id, $ath->vacancy_user_id]) }}')">
                                                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($ath_total == count($vacancyAttachments) && $vacancyUser->status == 'eligible')
                                        <div class="col-md-12">
                                            <div class="alert alert-info alert-has-icon alert-dismissible"
                                                id="studyCompletionAlert">
                                                <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                                <div class="alert-body">
                                                    <div class="alert-title">Konfirmasi Kelengkapan Berkas Tugas
                                                        Belajar</div>
                                                    Pastikan berkas telah lengkap. Setelah kelengkapan berkas telah
                                                    dikonfirmasi
                                                    maka peserta akan dapat mengunggah laporan.
                                                    <div class="d-flex justify-content-center">
                                                        <form
                                                            action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" id="status">
                                                            <div class="mt-3">
                                                                <button
                                                                    type="submit"class="btn btn-primary btn-icon icon-left print-btn"
                                                                    onclick="document.getElementById('status').value='report'"><i
                                                                        class="fas fa-check"></i>
                                                                    {{ __('Confirmation') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Laporan --}}
            @if (
                $vacancyUser->status === 'report' ||
                    $vacancyUser->status === 'extend' ||
                    $vacancyUser->status === 'activation' ||
                    $vacancyUser->status === 'done')
                <div id="laporan" class="section-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="section-title">
                                        <div class="d-flex justify-content-between">
                                            {{ __('Report') }}
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <table class="table table-striped table-hover table-md">
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th>{{ __('Semester') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                        @forelse ($vacancyReports as $report)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $report->name }}</td>
                                                <td>{!! $report->getStatusLabel() !!}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        data-toggle="modal" data-target="#pdfModal" title="Lihat Laporan"
                                                        onclick="setPDF('Laporan {{ $report->name }}','{{ route('vacancies-participant.get.report.file', [$report->id]) }}')">
                                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    </button>
                                                    @if ($report->status == 'pending')
                                                        <a href="javascript:void(0);" class="btn btn-success btn-sm m-1"
                                                            title="Verifikasi Laporan" id="verifyButton">
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <h4>Belum ada berkas yang diunggah</h4>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    @if ($vacancyUser->status != 'activation' && $vacancyUser->status != 'done')
                                        <hr>
                                        <div class="col-md-12 mb-2">
                                            <div class="alert alert-primary alert-has-icon alert-dismissible"
                                                id="studyCompletionAlert">
                                                <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                                <div class="alert-body">
                                                    <div class="alert-title">Konfirmasi Penyelesaian Tugas Belajar</div>
                                                    Pastikan laporan telah lengkap dan syarat lainnya telah dipenuhi oleh
                                                    peserta sebelum melakukan konfirmasi bahwa tugas belajar telah selesai
                                                    dilakukan. Setelah melakukan konfirmasi, pegawai akan melanjutkan proses
                                                    untuk unggah berkas aktivasi status kepegawaian.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 text-md-center">
                                            <form
                                                action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="activation">
                                                <button type="submit" target="_blank" href=""
                                                    class="btn btn-primary btn-icon icon-left print-btn"><i
                                                        class="fas fa-check"></i>
                                                    {{ __('Confirmation') }}</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Aktivasi --}}
            @if ($vacancyUser->status === 'activation' || $vacancyUser->status === 'done')
                <div id="aktivasi" class="section-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="section-title">
                                        <div class="d-flex justify-content-between">
                                            {{ __('Reactivation') }}
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <table class="table table-striped table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th data-width="40">#</th>
                                                <th>{{ __('File') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Notes') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $act_total = 0;
                                            @endphp
                                            @forelse ($vacancyActivations as $activation)
                                                @php
                                                    if ($activation->status == 'accepted') {
                                                        $act_total = $act_total + 1;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $activation->name }}</td>
                                                    <td>
                                                        @if ($activation->status == 'review')
                                                            <span class="badge badge-info">Review</span>
                                                        @elseif ($activation->status == 'rejected')
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @elseif ($activation->status == 'accepted')
                                                            <span class="badge badge-success">Disetujui</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $activation->note }}</td>
                                                    <td>
                                                        <button class="btn btn-danger px-2 btn-sm m-1" data-toggle="modal"
                                                            data-target="#pdfModal" title="Lihat {{ $activation->name }}"
                                                            onclick="setPDF('{{ $activation->name }}','{{ route('vacancies-participant.get.activation.file', $activation->id) }}')">
                                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                        </button>
                                                        @if ($vacancyUser->status === 'activation' && $activation->status == 'review')
                                                            <button class="btn btn-info btn-sm m-1" data-toggle="modal"
                                                                data-target="#activationModal{{ $activation->id }}"
                                                                title="Verifikasi {{ $activation->name }}">
                                                                <i class="fa fa-comment" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        <h4>Belum ada berkas yang diunggah</h4>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @if (count($vacancyActivations) > 0 && $act_total == 1 && $vacancyUser->status == 'activation')
                                        <div class="col-md-12">
                                            <div class="alert alert-info alert-has-icon alert-dismissible"
                                                id="studyCompletionAlert">
                                                <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                                <div class="alert-body">
                                                    <div class="alert-title">Konfirmasi Kelengkapan Berkas Aktivasi Ulang
                                                        Status Kepegawaian</div>
                                                    Pastikan berkas telah lengkap. Setelah kelengkapan berkas telah
                                                    dikonfirmasi maka proses Pendidikan Lanjutan peserta telah selesai.
                                                    <div class="d-flex justify-content-center">
                                                        <form
                                                            action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" id="status">
                                                            <div class="mt-3">
                                                                <button
                                                                    type="submit"class="btn btn-primary btn-icon icon-left print-btn"
                                                                    onclick="document.getElementById('status').value='done'"><i
                                                                        class="fas fa-check"></i>
                                                                    {{ __('Done') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Extend --}}
            @if (($vacancyUser->status === 'report' || $vacancyUser->status === 'extend') && $vacancyUser->status != 'done')
                <div id="perpanjang" class="section-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="section-title">
                                        <div class="d-flex justify-content-between">
                                            {{ __('Extension') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <form
                                        action="{{ route('admin.vacancies-participant.update.status', $vacancyUser->id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-md-12 mb-3">
                                            <label>{{ __('File') }} <span class="text-danger">*</span></label>
                                            <input type="file" name="file" class="form-control"
                                                accept="application/pdf" required>
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="status" id="status">
                                        <div class="col-md-12 mb-2">
                                            <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('reason', optional(\Modules\PendidikanLanjutan\app\Models\VacancyLogs::where('vacancy_user_id', $vacancyUser->id)->where('status', 'extend')->latest()->first())->description) }}</textarea>
                                            @error('reason')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mt-3 d-flex justify-content-center">
                                            {{-- <button type="submit" class="btn btn-warning btn-icon icon-left print-btn"
                                                onclick="document.getElementById('status').value='draft_extend'"><i
                                                    class="fas fa-hourglass"></i>
                                                {{ __('Draft') }}</button> --}}
                                            <button type="submit"class="btn btn-primary btn-icon icon-left print-btn"
                                                onclick="document.getElementById('status').value='extend'"><i
                                                    class="fas fa-check"></i>
                                                {{ __('Extend') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <!-- Modal for PDF upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-4" id="uploadModalLabel">{{ __('Upload PDF') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                        action="{{ route('admin.vacancies-participant.upload.file', ['vacancyId' => $vacancyUser->vacancy_id, 'vacancyUserId' => $vacancyUser->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="pdf_upload">{{ __('Choose PDF to Upload') }}</label>
                            <input type="hidden" name="title" id="title" value="">
                            <input type="file" class="form-control" id="pdf_upload" name="file" accept=".pdf">
                        </div>

                        <div id="pdf-preview" class="mt-3" style="display: none;">
                            <label>{{ __('Preview PDF') }}</label><br>
                            <embed id="pdf-preview-upload" src="" type="application/pdf" width="100%"
                                height="400px" />
                        </div>

                        <div class="mt-3 text-center">
                            <button type="submit" class="btn btn-primary">{{ __('Upload PDF') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for PDF preview -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-4" id="pdfModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" style="height: 80vh">
                    <object id="pdfObject" data="" type="application/pdf" width="100%"
                        style="height: 100%"></object>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ml-4" id="verifyModalLabel">Verifikasi Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST"
                    action="{{ route('admin.vacancies-participant.update.report.status', ['vacancyReportId' => $vacancyUser->vacancy_id]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="statusReport" id="statusReport">
                    <div class="modal-body">
                        <!-- <p></p> -->
                        <div id="error-message" class="alert alert-danger" style="display: none;">
                            <strong>Kesalahan!</strong> Alasan penolakan wajib diisi.
                        </div>
                        <div class="mb-3">
                            <label for="verificationMessage" class="form-label">Pesan</label>
                            <textarea class="form-control" id="verificationMessage" name="description" rows="4"
                                placeholder="Tuliskan pesan Anda di sini"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-danger" id="rejectButton"
                            onclick="document.getElementById('statusReport').value='rejected'">Tolak</button>
                        <button type="submit" class="btn btn-success" id="acceptButton"
                            onclick="document.getElementById('statusReport').value='accepted'">Terima</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($vacancyUser->status === 'activation' || $vacancyUser->status === 'done')
        @foreach ($vacancyActivations as $activation)
            <!-- Modal Aktivasi -->
            <div class="modal fade" id="activationModal{{ $activation->id }}" tabindex="-1"
                aria-labelledby="verifyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title ml-4" id="verifyModalLabel">Verifikasi Berkas
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST"
                            action="{{ route('admin.vacancies-participant.update.activation.status', ['id' => $activation->id]) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="activation_status" id="activation_status">
                            <div class="modal-body">
                                <div id="error-message" class="alert alert-danger" style="display: none;">
                                    <strong>Kesalahan!</strong> Alasan penolakan wajib diisi.
                                </div>
                                <div class="mb-3">
                                    <label for="verificationMessage" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="verificationMessage" name="description" rows="4"
                                        placeholder="Tuliskan pesan Anda di sini"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="submit" class="btn btn-danger" id="rejectButton"
                                    onclick="document.getElementById('activation_status').value='rejected'">Tolak</button>
                                <button type="submit" class="btn btn-success" id="acceptButton"
                                    onclick="document.getElementById('activation_status').value='accepted'">Terima</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection

@push('css')
    <style>
        .section-header-menu {
            position: sticky;
            top: 0;
            background-color: #8c9bfe;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .section-header-menu a {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: inline-block;
            background-color: transparent;
        }

        .section-header-menu a.disabled {
            pointer-events: none;
            color: white !important;
            cursor: not-allowed;
        }

        .section-header-menu a i {
            color: white;
        }

        .section-header-menu a:hover {
            background-color: white;
            color: #8c9bfe;
            border-radius: 5px;
        }

        .section-header-menu a:hover i {
            color: #8c9bfe;
        }

        .section-header-menu a:active {
            background-color: white;
            color: #8c9bfe;
        }

        .section-header-menu a:active i {
            color: #8c9bfe;
        }

        .section-header-menu .active {
            background-color: white;
            color: #8c9bfe;
        }

        .section-header-menu .active i {
            color: #8c9bfe;
        }

        .separator {
            color: white;
            margin: 10px 5px;
            font-size: 14px;
            display: inline-block;
        }

        .registrant-info-header {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <script>
        $('a[href^="#"]').on('click', function(event) {
            event.preventDefault();
            var target = $(this).attr('href');

            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            }, 1000);
        });

        $('.section-header-menu a').click(function() {
            $('.section-header-menu a').removeClass('active');
            $(this).addClass('active');
        });
    </script>

    <script>
        document.getElementById('pdf_upload').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var pdfEmbed = document.getElementById('pdf-preview-upload');
                    pdfEmbed.src = event.target.result;
                    document.getElementById('pdf-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#uploadModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var title = button.attr('title');
                $('#title').val(title);
            });

            $('#verifyButton').click(function() {
                $('#verifyModal').modal('show');
            });

            $('#rejectButton').click(function() {
                var message = $('#verificationMessage').val().trim();

                if (message === '') {
                    $('#error-message').show();
                } else {
                    $('#error-message').hide();
                    $('#verifyModal').modal('hide');
                    Swal.fire('Berhasil!', 'Laporan berhasil ditolak.', 'success');
                }
            });

            $('#acceptButton').click(function() {
                $('#verifyModal').modal('hide');
                Swal.fire('Berhasil!', 'Laporan berhasil diverifikasi.', 'success');
            });
        });
    </script>

    <script>
        function setPDF(title, pdfUrl) {
            document.getElementById('pdfModalLabel').textContent = title;
            document.getElementById('pdfObject').setAttribute('data', pdfUrl);
        }
    </script>
@endpush
