@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Certificate List') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($certificates as $data)
                                <tr>
                                    <td><strong>{{ $data->name }}</strong> ({{ $data->date }})</td>
                                    <td>{{ ucwords($data->category) }}</td>
                                    <td>
                                        <a href="{{ $data->url }}" class="btn-action-primary" title="Unduh">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('Tidak ada riwayat') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = new DataTable('.table', {
                language: {
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 to 0 of 0 data",
                    "infoFiltered": "(Disaring dari _MAX_ data)",
                    "emptyTable": "Tidak ada data",
                    "search": "Cari:",
                    "info": "Halaman _PAGE_ dari _PAGES_",
                    "lengthMenu": "Tampilkan _MENU_ entries",
                },
            });
        });
    </script>
@endpush
