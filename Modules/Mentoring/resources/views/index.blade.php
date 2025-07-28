@extends('admin.master_layout')
@section('title')
    <title>{{ __('Mentoring List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Mentoring List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Mentoring List') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="dashboard__review-table">
                            <div class="dashboard__nav-wrap">
                                <ul class="nav nav-tabs" id="statusTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == null ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => '']) }}">
                                            {{ __('All') }} &nbsp;
                                            <span class="badge {{ $status == null ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $totalMentors ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Draft' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Draft']) }}">
                                            {{ __('Draft') }} &nbsp;
                                            <span class="badge {{ $status == 'Draft' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Draft'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Pengajuan' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Pengajuan']) }}">
                                            {{ __('Submission') }} &nbsp;
                                            <span class="badge {{ $status == 'Pengajuan' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Pengajuan'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Proses' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Proses']) }}">
                                            {{ __('Process') }} &nbsp;
                                            <span class="badge {{ $status == 'Proses' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Proses'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Penilaian' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Penilaian']) }}">
                                            {{ __('Evaluation') }} &nbsp;
                                            <span class="badge {{ $status == 'Penilaian' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Penilaian'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Selesai' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Selesai']) }}">
                                            {{ __('Done') }} &nbsp;
                                            <span class="badge {{ $status == 'Selesai' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Selesai'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'Tolak' ? 'active' : '' }}"
                                        href="{{ route('admin.mentoring.index', ['status' => 'Tolak']) }}">
                                            {{ __('Rejected') }} &nbsp;
                                            <span class="badge {{ $status == 'Tolak' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['Tolak'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Mentoring List') }}</h4>
                                <div>
                                    <!-- <a href="" class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Mentor') }}</th>
                                                <th>{{ __('Number of Participants') }}</th>
                                                <th>{{ __('Total Session') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($mentors as $index => $mentor)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a
                                                        href="{{ route('admin.mentoring.show', $mentor->id) }}">{{ $mentor->title }}</a>
                                                </td>
                                                <td>{{ $mentor->mentor->name }}</td>
                                                <td>{{ $mentor->mentee_count}}</td>
                                                <td>
                                                    {{ $mentor->total_session }}
                                                </td>
                                                <td>
                                                    <div class="badge badge-{{ $mentor->stat['color'] }}">{{ $mentor->stat['label'] }}</div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.mentoring.show', $mentor->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Knowledges')" route="" create="no" :message="__('No data found!')"
                                                colspan="9"></x-empty-table>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $mentors->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif
@endpush

@push('css')
    <style>
    </style>
@endpush
