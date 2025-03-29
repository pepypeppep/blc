@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    @php
        use Modules\PendidikanLanjutan\app\Models\VacancyUser;
    @endphp
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
                                        $vacancy->status === VacancyUser::STATUS_ELIGIBLE ||
                                            $vacancy->status === VacancyUser::STATUS_DONE ||
                                            $vacancy->status === VacancyUser::STATUS_REPORT ||
                                            $vacancy->status === VacancyUser::STATUS_EXTEND)
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
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                @include('frontend.student-dashboard.continuing-education.registration.partials.data-peserta')
                                @include('frontend.student-dashboard.continuing-education.registration.partials.dokumen')
                                @if (
                                    $vacancy->status === VacancyUser::STATUS_ELIGIBLE ||
                                        $vacancy->status === VacancyUser::STATUS_DONE ||
                                        $vacancy->status === VacancyUser::STATUS_REPORT ||
                                        $vacancy->status === VacancyUser::STATUS_EXTEND)
                                    @include('frontend.student-dashboard.continuing-education.registration.partials.lampiran')
                                    @include('frontend.student-dashboard.continuing-education.registration.partials.laporan')
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-1">

                        </div>
                        <div class="col-xl-3 p-3">
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
