@extends('admin.master_layout')
@section('title')
    <title>{{ __($submenu) }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($submenu) }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __($submenu) }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.vacancies.master.schedule.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                class="form-control" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <select name="order_by" id="order_by" class="form-control">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="1" {{ request('order_by') == '1' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="0" {{ request('order_by') == '0' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <select name="par-page" id="par-page" class="form-control">
                                                <option value="">{{ __('Per Page') }}</option>
                                                <option value="10" {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('10') }}
                                                </option>
                                                <option value="50" {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('50') }}
                                                </option>
                                                <option value="100"
                                                    {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('100') }}
                                                </option>
                                                <option value="all"
                                                    {{ 'all' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Master Jadwal') }}</h4>
                                <div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#tambahJadwalModal">{{ __('Add New') }}</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="1%">{{ __('#') }}</th>
                                                <th width="10%">{{ __('Start Date') }}</th>
                                                <th width="10%">{{ __('End Date') }}</th>
                                                <th width="1%">{{ __('Year') }}</th>
                                                {{-- <th width="20%">{{ __('Description') }}</th> --}}
                                                <th width="1%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($schedules as $sc)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $sc->start_at }}</td>
                                                    <td>{{ $sc->end_at }}</td>
                                                    <td>{{ $sc->year }}</td>
                                                    {{-- <td>{{ $sc->description }}</td> --}}
                                                    <td>
                                                        <button type="button" data-toggle="modal"
                                                        data-target="#ubahJadwalModal{{ $sc->id }}" class="btn btn-warning btn-sm m-1" title="Ubah">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        {{ __('No data found!') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{-- @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $posts->onEachSide(0)->links() }}
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahJadwalModal" tabindex="-1" role="dialog" aria-labelledby="tambahJadwalModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                    <h5 class="modal-title" id="tambahJadwalModal">
                        {{ __('Tambah Jadwal') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.vacancies.master.schedule.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __('Start Date') }} <code>*</code></label>
                            <input type="date" name="start_at" class="form-control" required>
                            @error('start_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('End Date') }} <code>*</code></label>
                            <input type="date" name="end_at" class="form-control" required>
                            @error('end_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Year') }} <code>*</code></label>
                            <input type="number" name="year" class="form-control" required>
                            @error('year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="">{{ __('Description') }} <code>*</code></label>
                            <input type="text" name="description" class="form-control" required>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Tambah') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($schedules as $sc)
    <!-- Modal -->
    <div class="modal fade" id="ubahJadwalModal{{ $sc->id }}" tabindex="-1" role="dialog" aria-labelledby="ubahJadwalModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                    <h5 class="modal-title" id="ubahJadwalModal">
                        {{ __('Tambah Jadwal') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.vacancies.master.schedule.update', $sc->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __('Start Date') }} <code>*</code></label>
                            <input type="date" name="start_at" class="form-control" value="{{ \Carbon\Carbon::parse($sc->start_at)->format('Y-m-d') }}" required>
                            @error('start_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('End Date') }} <code>*</code></label>
                            <input type="date" name="end_at" class="form-control" value="{{ \Carbon\Carbon::parse($sc->end_at)->format('Y-m-d') }}" required>
                            @error('end_at')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Year') }} <code>*</code></label>
                            <input type="number" name="year" class="form-control" value="{{ $sc->year }}" disabled>
                            @error('year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="">{{ __('Description') }} <code>*</code></label>
                            <input type="text" name="description" class="form-control" required>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Ubah') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@push('js')
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
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
