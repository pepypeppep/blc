@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Daftar Program Pendidikan Lanjutan') }}</h4>
        </div>
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
                            @forelse ($vacancies as $key => $vacancy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $vacancy->education_level }}/{{ $vacancy->study->name }}</td>
                                    <td>{{ $vacancy->year }}</td>
                                    <td>{{ formatDate($vacancy->open_at) }}</td>
                                    <td>{{ formatDate($vacancy->close_at) }}</td>
                                    <td>
                                        <div class="badge bg-warning">{{ $vacancy->users()->first()->status }}</div>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('student.continuing-education.registration.show', $vacancy->users()->first()->id) }}"
                                            class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>

                </div>
                {{-- {{ $vacancies->links() }} --}}
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
