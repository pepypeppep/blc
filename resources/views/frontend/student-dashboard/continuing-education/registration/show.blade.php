@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    @php
        use Modules\PendidikanLanjutan\app\Models\VacancyUser;
    @endphp
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Pendaftaran Tugas Belajar') }}</h4>
            @if ($vacancyUser->status === VacancyUser::STATUS_DONE)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-success">
                        <div class="alert-body">
                            Pencantuman Gelar dapat dilakukan melalui laman SIASN BKN
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="instructor__profile-form-wrap">
                    <div class="row ">
                        <div class="col-xl-8 ">
                            <div class="dashboard__nav-wrap">
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
                                    @if (
                                        $vacancyUser->status === VacancyUser::STATUS_ELIGIBLE ||
                                            $vacancyUser->status === VacancyUser::STATUS_DONE ||
                                            $vacancyUser->status === VacancyUser::STATUS_REPORT ||
                                            $vacancyUser->status === VacancyUser::STATUS_ACTIVATION ||
                                            $vacancyUser->status === VacancyUser::STATUS_EXTEND)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="itemSix-tab" data-bs-toggle="tab"
                                                data-bs-target="#itemSix-tab-pane" type="button" role="tab"
                                                aria-controls="itemSix-tab-pane" aria-selected="false"
                                                tabindex="-1">Lampiran</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="itemSeven-tab" data-bs-toggle="tab"
                                                data-bs-target="#laporan-tab-panel" type="button" role="tab"
                                                aria-controls="laporan-tab-panel" aria-selected="false"
                                                tabindex="-1">Laporan</button>
                                        </li>
                                    @endif
                                    @if ($vacancyUser->status === VacancyUser::STATUS_DONE || $vacancyUser->status === VacancyUser::STATUS_ACTIVATION)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pengaktifan-tab" data-bs-toggle="tab"
                                                data-bs-target="#pengaktifan-tab-pane" type="button" role="tab"
                                                aria-controls="pengaktifan-tab-pane" aria-selected="false"
                                                tabindex="-1">Pengaktifan</button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                @include('frontend.student-dashboard.continuing-education.registration.partials.data-peserta')
                                @include('frontend.student-dashboard.continuing-education.registration.partials.dokumen')
                                @if (
                                    $vacancyUser->status === VacancyUser::STATUS_ELIGIBLE ||
                                        $vacancyUser->status === VacancyUser::STATUS_DONE ||
                                        $vacancyUser->status === VacancyUser::STATUS_REPORT ||
                                        $vacancyUser->status === VacancyUser::STATUS_ACTIVATION ||
                                        $vacancyUser->status === VacancyUser::STATUS_EXTEND)
                                    @include('frontend.student-dashboard.continuing-education.registration.partials.lampiran')
                                    @include('frontend.student-dashboard.continuing-education.registration.partials.laporan')
                                @endif
                                @if ($vacancyUser->status === VacancyUser::STATUS_DONE || $vacancyUser->status === VacancyUser::STATUS_ACTIVATION)
                                    @include('frontend.student-dashboard.continuing-education.registration.partials.pengaktifan')
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <h5 class="mb-4">Timeline</h5>
                            <div class="bsb-timeline-1">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div style="height: 100vh; overflow-y: scroll;" class="p-2">
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
                                                                                <br>
                                                                                @if ($log->status === 'created')
                                                                                    <span
                                                                                        class="badge bg-warning">{{ $log->status }}</span>
                                                                                @elseif($log->status === 'verification')
                                                                                    <span
                                                                                        class="badge bg-info">{{ $log->status }}</span>
                                                                                @elseif($log->status === 'assessment')
                                                                                    <span
                                                                                        class="badge bg-primary">{{ $log->status }}</span>
                                                                                @elseif($log->status === 'eligible')
                                                                                    <span
                                                                                        class="badge bg-success">{{ $log->status }}</span>
                                                                                @elseif($log->status === 'rejected')
                                                                                    <span
                                                                                        class="badge bg-danger">{{ $log->status }}</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge bg-secondary">{{ $log->status }}</span>
                                                                                @endif
                                                                            </h6>
                                                                            <p>{!! $log->description !!}</p>
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

        .active-step::handleDeleteFormSubmitbefore {
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

    <script>
        $(document).ready(function() {
            if (@js($vacancyUser->status) === @js(VacancyUser::STATUS_DONE)) {
                swal.fire({
                    title: 'Pencantuman Gelar',
                    text: 'Pencantuman Gelar dapat dilakukan melalui laman SIASN BKN',
                    icon: 'success',
                    button: 'OK',
                });
            }
        });
    </script>
@endpush
