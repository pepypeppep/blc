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
                                <h4>{{ __('Participant List') }}</h4>
                                <div>
                                    <!-- <a href="" class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('#') }}</th>
                                                <th width="30%" class="course-table-title">{{ __('Employee Id') }} /
                                                    {{ __('Name') }}</th>
                                                <th width="15%">{{ __('Employment Position') }}</th>
                                                <th width="15%">{{ __('Employment Level') }}</th>
                                                <th width="15%">{{ __('Employment Unit') }}</th>
                                                <th width="10%">{{ __('Education Level') }} / {{ __('Study') }}</th>
                                                <th width="10%">{{ __('Year') }}</th>
                                                <th width="10%">{{ __('Cost Type') }}</th>
                                                <th width="10%">{{ __('Status') }}</th>
                                                <th width="10%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vacancyUsers as $vacancyUser)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $vacancyUser->user->nip }} / {{ $vacancyUser->user->name }}
                                                    </td>
                                                    <td>Jabatan/Pangkat</td>
                                                    <td>{{ optional($vacancyUser->vacancy)->employment_grade }}</td>
                                                    <td>Unit Kerja</td>
                                                    <td>{{ optional($vacancyUser->vacancy)->education_level }} /
                                                        {{ optional(optional($vacancyUser->vacancy)->study)->name }}</td>
                                                    <td>{{ optional($vacancyUser->vacancy)->year }}</td>
                                                    <td>{{ optional($vacancyUser->vacancy)->cost_type }}</td>
                                                    <td>
                                                        @if ($vacancyUser->status === 'created')
                                                            <span
                                                                class="badge badge-warning">{{ $vacancyUser->status }}</span>
                                                        @elseif($vacancyUser->status === 'verification')
                                                            <span
                                                                class="badge badge-info">{{ $vacancyUser->status }}</span>
                                                        @elseif($vacancyUser->status === 'assessment')
                                                            <span
                                                                class="badge badge-primary">{{ $vacancyUser->status }}</span>
                                                        @elseif($vacancyUser->status === 'eligible')
                                                            <span
                                                                class="badge badge-success">{{ $vacancyUser->status }}</span>
                                                        @elseif($vacancyUser->status === 'rejected')
                                                            <span
                                                                class="badge badge-danger">{{ $vacancyUser->status }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-secondary">{{ $vacancyUser->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($vacancyUser->status === 'verification')
                                                            <a href="{{ route('admin.vacancies.verification.show', $vacancyUser->id) }}"
                                                                class="btn btn-primary btn-sm m-1"
                                                                title="Verifikasi Pendaftar">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        @elseif($vacancyUser->status === 'assessment')
                                                            <a href="{{ route('admin.vacancies.assessment.show', $vacancyUser->id) }}#assessment"
                                                                class="btn btn-primary btn-sm m-1"
                                                                title="Assesment Pendaftar">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        @elseif($vacancyUser->status === 'eligible')
                                                            <a href="{{ route('admin.vacancies.sk.show', $vacancyUser->id) }}#sk"
                                                                class="btn btn-primary btn-sm m-1" title="SK Pendaftar">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
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

    <script>
        function setModalData(url, itemName) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('modal-item-name').textContent = 'Lowongan ' + itemName;
        }

        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/vacancies/') }}' + "/" + id)
        }
        "use strict"

        function changeStatus(id) {
            var isDemo = "{{ env('PROJECT_MODE') ?? 1 }}"
            if (isDemo == 0) {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/vacancies/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(xhr, status, err) {
                    console.log(err);
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                    })
                }
            });
        }
    </script>
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
