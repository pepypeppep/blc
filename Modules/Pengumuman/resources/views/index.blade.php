@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Pengumuman') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Pengumuman') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Manage Pengumuman') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Pengumuman List') }}</h4>
                                <div>
                                    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Content') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($pengumumans as $index => $pengumuman)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a
                                                        href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}">{{ $pengumuman->title }}</a>
                                                </td>
                                                <td><p title="{!! clean($pengumuman->content) !!}">{!! clean(truncate($pengumuman->content)) !!}</p></td>
                                                <td>
                                                    <div class="badge badge-{{ $pengumuman->stat['color'] }}">{{ $pengumuman->stat['label'] }}</div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Pengumuman')" route="" create="no" :message="__('No data found!')"
                                                colspan="9"></x-empty-table>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $pengumumans->links() }}
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
