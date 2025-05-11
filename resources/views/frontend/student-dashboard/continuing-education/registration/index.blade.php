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
                                <th>{{ __('Jenjang/Program Studi') }}</th>
                                <th>{{ __('Tahun Tubel') }}</th>
                                <th>{{ __('Mulai') }}</th>
                                <th>{{ __('Berakhir') }}</th>
                                <th>{{ __('Proses') }}</th>
                                <th>{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vacancies as $vacancy)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vacancy->education_level }}/{{ $vacancy->study->name }}</td>
                                    <td>{{ $vacancy->year }}</td>
                                    <td>{{ formatDate($vacancy->open_at) }}</td>
                                    <td>{{ formatDate($vacancy->close_at) }}</td>
                                    <td>
                                        <div class="badge bg-warning">
                                            {{ optional($vacancy->users()->first())->status }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('student.continuing-education.registration.show', $vacancy->users->first()->id) }}"
                                            class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('Tidak ada riwayat pendaftaran program pendidikan lanjutan') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                {{-- {{ $vacancies->links() }} --}}
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
