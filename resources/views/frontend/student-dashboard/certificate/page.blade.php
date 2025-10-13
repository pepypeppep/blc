@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Certificate List') }}</h4>
            <div>
                <select name="year" class="form-control px-5" id="year" style="cursor: pointer;"
                    onchange="location.href='{{ request()->url() }}?year='+this.value;">
                    <option value="2025" @selected(request('year') == '2025')>2025</option>
                    <option value="2024" @selected(request('year') == '2024')>2024</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="dashboard__counter-item">
                    <div class="content">
                        <span class="count odometer odometer-auto-theme" data-count="{{ $totalJp }}">
                            <div class="odometer-inside"><span class="odometer-digit"><span
                                        class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                            class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                                    class="odometer-value">{{ $totalJp }}</span></span></span></span></span>
                            </div>
                        </span>
                        <p>JPL Tahun {{ request('year') ?? date('Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="dashboard__counter-item">
                    <div class="content">
                        <span class="count odometer odometer-auto-theme" data-count="{{ $totalJpPerTriwulan[1] ?? 0 }}">
                            <div class="odometer-inside"><span class="odometer-digit"><span
                                        class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                            class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                                    class="odometer-value">{{ $totalJpPerTriwulan[1] ?? 0 }}</span></span></span></span></span>
                            </div>
                        </span>
                        <p>JPL TW I</p>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="dashboard__counter-item">
                    <div class="content">
                        <span class="count odometer odometer-auto-theme" data-count="{{ $totalJpPerTriwulan[2] ?? 0 }}">
                            <div class="odometer-inside"><span class="odometer-digit"><span
                                        class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                            class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                                    class="odometer-value">{{ $totalJpPerTriwulan[2] ?? 0 }}</span></span></span></span></span>
                            </div>
                        </span>
                        <p>JPL TW II</p>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="dashboard__counter-item">
                    <div class="content">
                        <span class="count odometer odometer-auto-theme" data-count="{{ $totalJpPerTriwulan[3] ?? 0 }}">
                            <div class="odometer-inside"><span class="odometer-digit"><span
                                        class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                            class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                                    class="odometer-value">{{ $totalJpPerTriwulan[3] ?? 0 }}</span></span></span></span></span>
                            </div>
                        </span>
                        <p>JPL TW III</p>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="dashboard__counter-item">
                    <div class="content">
                        <span class="count odometer odometer-auto-theme" data-count="{{ $totalJpPerTriwulan[4] ?? 0 }}">
                            <div class="odometer-inside"><span class="odometer-digit"><span
                                        class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                                            class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                                    class="odometer-value">{{ $totalJpPerTriwulan[4] ?? 0 }}</span></span></span></span></span>
                            </div>
                        </span>
                        <p>JPL TW IV</p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('JPL') }}</th>
                                <th>{{ __('Pelaksanaan') }}</th>
                                <th>{{ __('Download') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($certificates as $data)
                                <tr>
                                    <td><strong>{{ $data->title }}</strong> ({{ $data->year }})</td>
                                    <td>{{ ucwords($data->category) }}</td>
                                    <td>{{ $data->jp }}</td>
                                    <td>{{ $data->periode }}</td>
                                    <td>
                                        <a href="{{ $data->url }}" class="btn-action-primary" title="Unduh">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
