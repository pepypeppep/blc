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
                                <th>{{ __('NIP') }}</th>
                                <th>{{ __('Nama Pegawai') }}</th>
                                <th>{{ __('Jabatan / Pangkat') }}</th>
                                <th>{{ __('Gol') }}</th>
                                <th>{{ __('Unit Kerja') }}</th>
                                <th>{{ __('Jenjang/Program Studi') }}</th>
                                <th>{{ __('Tahun Tubel') }}</th>
                                <th>{{ __('Proses') }}</th>
                                <th>{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse  --}}
                            <tr>
                                <td>1</td>
                                <td>199308172019022001</td>
                                <td>Nabila Sholihah, S.Kom</td>
                                <td>Pranata Komputer Pertama / Penata Muda </td>
                                <td>III/b</td>
                                <td>Dinas Komunikasi dan Informatika Kabupaten Bantul</td>
                                <td>S2/Informatika</td>
                                <td>2025</td>
                                <td>
                                    <div class="badge bg-warning">{{ __('Pending') }}</div>
                                </td>

                                <td class="text-center">
                                    @php
                                        $id = 1;
                                    @endphp
                                    <a href="{{ route('student.continuing-education.registration.show', $id) }}"
                                        class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
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
