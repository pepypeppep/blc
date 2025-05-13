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
                                <th width="30%">{{ __('Program Studi Pendidikan Lanjutan') }}</th>
                                <th>{{ __('Batas Usia Pendaftaran') }}</th>
                                <th width="20%">{{ __('Tanggal Mulai Pendaftaran') }}</th>
                                <th width="20%">{{ __('Tanggal Penutupan Pendaftaran') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vacancies as $key => $vacancy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $vacancy->study->name }}</td>
                                    <td>{{ $vacancy->age_limit }}
                                        {{-- @if ($vacancy->details->isNotEmpty())
                                            {{ $vacancy->details->first()->value_type ?? '-' }}
                                        @else
                                            {{ '-' }}
                                        @endif --}}
                                    </td>
                                    <td>{{ $vacancy->open_at ? \Carbon\Carbon::parse($vacancy->open_at)->format('d F Y') : '-' }}
                                    </td>
                                    <td>{{ $vacancy->close_at ? \Carbon\Carbon::parse($vacancy->close_at)->format('d F Y') : '-' }}
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
                                    <td colspan="7" class="text-center">{{ __('Pendaftaran Program Pendidikan Lanjutan belum dibuka') }}</td>
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
                <div class="alert alert-warning">{{ __('Saat ini, Pendaftaran Program Pendidikan Lanjutan hanya tersedia untuk PNS') }}</div>
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
