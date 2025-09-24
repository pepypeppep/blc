@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Daftar Program Pendidikan Lanjutan') }}</h4>
        </div>
        @if (userAuth()->canAccessContinuingEducation())
            <div class="row">
                <div class="col-12">
                    <div class="dashboard__review-table table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th width="50%">{{ __('Program Studi Pendidikan Lanjutan') }}</th>
                                    <th width="20%">{{ __('Tanggal Mulai Pendaftaran') }}</th>
                                    <th width="20%">{{ __('Tanggal Akhir Pendaftaran') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$schedule)
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('Pendaftaran Program Pendidikan Lanjutan belum dibuka') }}</td>
                                    </tr>
                                @endif
                                @forelse ($vacancies as $key => $vacancy)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            ({{ $vacancy->education_level }})
                                            {{ $vacancy->study->name }}
                                            <br>
                                            {{ $vacancy->study->university }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_at)->format('d F Y') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_at)->format('d F Y') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('student.continuing-education.show', $vacancy->id) }}"
                                                class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('Belum ada program pendidikan lanjutan yang ditawarkan') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $vacancies->links() }}
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        {{ __('Saat ini, Pendaftaran Program Pendidikan Lanjutan hanya tersedia untuk PNS') }}</div>
                </div>
            </div>
        @endif
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
