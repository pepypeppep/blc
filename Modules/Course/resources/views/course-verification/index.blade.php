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
                                <form action="{{ route('admin.vacancies.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-2 form-group">
                                            <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                class="form-control" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <select name="start_date" id="start_date" class="form-control">
                                                <option value="">{{ __('Start Date') }}</option>
                                                <option value="1" {{ request('start_date') == '1' ? 'selected' : '' }}>
                                                    {{ __('Yes') }}
                                                </option>
                                                <option value="0"
                                                    {{ request('is_popular') == '0' ? 'selected' : '' }}>
                                                    {{ __('No') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <select name="end_date" id="end_date" class="form-control">
                                                <option value="">{{ __('End Date') }}</option>
                                                <option value="1" {{ request('end_date') == '1' ? 'selected' : '' }}>
                                                    {{ __('Yes') }}
                                                </option>
                                                <option value="0" {{ request('end_date') == '0' ? 'selected' : '' }}>
                                                    {{ __('No') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('Select Status') }}</option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                    {{ __('In-Active') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
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
                                        <div class="col-md-2 form-group">
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
                                <h4>{{ __('Registrant List') }}</h4>
                                <div>
                                    <!-- <a href="" class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <button type="button" id="acceptAll" class="btn btn-primary btn-md m-2">
                                        <i class="fa fa-check" aria-hidden="true"></i> Terima Semua
                                    </button>
                                    <button type="button" id="rejectAll" class="btn btn-danger btn-md m-2">
                                        <i class="fa fa-times" aria-hidden="true"></i> Tolak Semua
                                    </button>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="1%"><input type="checkbox" id="selectAll"></th>
                                                <th width="1%">{{ __('#') }}</th>
                                                <th width="20%">{{ __('Employee Id') }} / {{ __('Name') }}</th>
                                                <th width="15%">{{ __('Employment Position') }}</th>
                                                <th width="5%">{{ __('Employment Level') }}</th>
                                                <th width="20%">{{ __('Employment Unit') }}</th>
                                                <th width="5%">{{ __('Status') }}</th>
                                                <th width="20%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($enrollmentUsers as $enrollmentUser)
                                                <tr data-user-id="{{ $enrollmentUser->user->id }}">

                                                    <td><input type="checkbox" class="userCheckbox" value="{{ $enrollmentUser->user->id }}"></td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $enrollmentUser->user->nip }} / {{ $enrollmentUser->user->name }}</td>
                                                    <td>Jabatan / Pangkat</td>
                                                    <td>III/B</td>
                                                    <td>Dinas Komunikasi dan Informatika</td>
                                                    <td>
                                                        @if ($enrollmentUser->has_access === 1)
                                                            <span class="badge badge-success">Diterima</span>
                                                        @else
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm m-1 updateStatus" data-id="{{ $enrollmentUser->user->id }}" data-status="1">
                                                            <i class="fa fa-check" aria-hidden="true"></i> Terima
                                                        </button>
                                                        <button class="btn btn-danger btn-sm m-1 updateStatus" data-id="{{ $enrollmentUser->user->id }}" data-status="0">
                                                            <i class="fa fa-times" aria-hidden="true"></i> Tolak
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">{{ __('No vacancies found!') }}</td>
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
    <x-admin.delete-modal />
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

    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            async function updateEnrollment(userIds, status) {
                if (userIds.length === 0) {
                    return toastr.warning("Pilih minimal 1 peserta.", "Warning");
                }

                try {
                    let response = await fetch("{{ route('admin.course.updateEnrollmentStatus') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ user_ids: userIds, status: status })
                    });

                    let data = await response.json();

                    toastr.success(data.message, "Success");

                    userIds.forEach(userId => {
                        let row = document.querySelector(`tr[data-user-id='${userId}']`);
                        if (row) {
                            let badge = row.querySelector(".badge");
                            badge.className = status ? "badge badge-success" : "badge badge-danger";
                            badge.textContent = status ? "Diterima" : "Ditolak";
                        }
                    });

                } catch (error) {
                    toastr.error("Terjadi kesalahan, coba lagi.", "Error");
                }
            }

            document.querySelectorAll(".updateStatus").forEach(button => {
                button.addEventListener("click", function () {
                    let userId = this.dataset.id;
                    let status = this.dataset.status == "1" ? 1 : 0;
                    updateEnrollment([userId], status);
                });
            });

            document.getElementById("acceptAll").addEventListener("click", function () {
                let selectedUsers = Array.from(document.querySelectorAll(".userCheckbox:checked"))
                    .map(checkbox => checkbox.value);
                updateEnrollment(selectedUsers, 1);
            });

            document.getElementById("rejectAll").addEventListener("click", function () {
                let selectedUsers = Array.from(document.querySelectorAll(".userCheckbox:checked"))
                    .map(checkbox => checkbox.value);
                updateEnrollment(selectedUsers, 0);
            });

            document.getElementById("selectAll").addEventListener("change", function () {
                let isChecked = this.checked;
                document.querySelectorAll(".userCheckbox").forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });

        });
    </script>
@endpush


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
