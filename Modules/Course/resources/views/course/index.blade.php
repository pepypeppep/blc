@extends('admin.master_layout')
@section('title')
    <title>{{ __('Courses') }}</title>
@endsection
@use('App\Models\Course')
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="">{{ __('Courses') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Courses List') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.course.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                class="form-control" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <input type="text" autocomplete="off" name="date"
                                                value="{{ request()->get('date') }}" class="form-control datepicker"
                                                placeholder="{{ __('Date') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="from-group">
                                                <select class="select2 form-group category" name="category">
                                                    <option value="">{{ __('Category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <optgroup label="{{ $category->translation?->name }}">
                                                            @foreach ($category->subCategories as $subCategory)
                                                                <option value="{{ $subCategory->id }}"
                                                                    {{ request()->get('category') == $subCategory->id ? 'selected' : '' }}>
                                                                    {{ $subCategory->translation?->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <select name="instructor" id="instructor" class="form-control select2">

                                                <option value="">{{ __('Instructor') }}</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        {{ request()->get('instructor') == $instructor->id ? 'selected' : '' }}>
                                                        {{ $instructor->name }} ({{ $instructor->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <select name="approve_status" id="" class="form-control">
                                                <option value="">{{ __('Approval Status') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'pending' ? 'selected' : '' }}
                                                    value="pending">{{ __('Pending') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'approved' ? 'selected' : '' }}
                                                    value="approved">{{ __('Approved') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'rejected' ? 'selected' : '' }}
                                                    value="rejected">{{ __('Rejected') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('Status') }}</option>
                                                <option {{ request()->get('status') == 'active' ? 'selected' : '' }}
                                                    value="active">{{ __('Published') }}</option>
                                                <option {{ request()->get('status') == 'inactive' ? 'selected' : '' }}
                                                    value="inactive">{{ __('Unpublished') }}</option>
                                                <option {{ request()->get('status') == 'draft' ? 'selected' : '' }}
                                                    value="draft">{{ __('Drafted') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group">
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
                                        <div class="col-md-3 form-group">
                                            <select name="par_page" id="par-page" class="form-control">
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
                                <h4>{{ __('Courses List') }}</h4>
                                @if (checkAdminHasPermission('course.create') && checkAdminHasPermission('course.store'))
                                    <div>
                                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary"> <i
                                                class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th class="course-table-title">{{ __('Title') }}</th>
                                                <th class="text-center">{{ __('Enrolled Students') }}</th>
                                                <th class="text-center">{{ __('Join Request') }}</th>
                                                <th class="text-center">{{ __('Status') }}</th>
                                                @if (checkAdminHasPermission('course.verify'))
                                                    <th class="course-table-approve text-center">{{ __('Approve') }}</th>
                                                @endif
                                                @if (checkAdminHasPermission('course.edit') ||
                                                        checkAdminHasPermission('course.delete') ||
                                                        checkAdminHasPermission('course.create'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($courses as $course)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="course-table-title">
                                                        <a
                                                            href="{{ route('course.show', $course->slug) }}"><span>{{ truncate($course->title) }}</span></a>
                                                        <br>
                                                        <small>{{ $course->category->translation->name ?? '' }}</small>
                                                        <br>
                                                        <small>Akses:
                                                            @if ($course->access == 'private')
                                                                <span class="text-warning font-weight-bold"
                                                                    title="Akses Internal">{{ __('Private') }}</span>
                                                            @else
                                                                <span class="text-success font-weight-bold"
                                                                    title="Akses Publik">{{ __('Public') }}</span>
                                                            @endif
                                                            |
                                                            Tipe Kursus: <span
                                                                class="text-dark font-weight-bold">{{ $course->type == 'course' ? 'Kursus' : ucwords($course->type) }}</span>
                                                            |
                                                            JPL: <span
                                                                class="text-dark font-weight-bold">{{ $course->jp }}</span>

                                                        </small>
                                                    </td>
                                                    <td class="text-center">{{ $course->enrollments_count }}</td>
                                                    <td class="text-center">
                                                        {{ $course->enrollments_pending_count }}
                                                        <br>
                                                        @if ($course->enrollments_pending_count > 0)
                                                            <a href="{{ route('admin.course-verification.index', $course->id) }}"
                                                                class="btn btn-sm btn-primary">Verifikasi</a>
                                                        @endif
                                                    </td>

                                                    <td class="course-table-approve">
                                                        <select name=""
                                                            class="form-control course-change-publish-status"
                                                            data-id="{{ $course->id }}">
                                                            <option @selected($course->status == 'active') value="active">
                                                                <span
                                                                    class="badge badge-success">{{ __('Published') }}</span>
                                                            </option>
                                                            <option @selected($course->status == 'inactive') value="inactive">
                                                                <span
                                                                    class="badge badge-danger">{{ __('Unpublished') }}</span>
                                                            </option>
                                                            <option @selected($course->status == 'is_draft') value="is_draft">
                                                                <span
                                                                    class="badge badge-warning">{{ __('Draft') }}</span>
                                                            </option>
                                                        </select>
                                                    </td>

                                                    @if (checkAdminHasPermission('course.verify'))
                                                        <td class="course-table-approve">
                                                            <select name=""
                                                                class="form-control course-change-status"
                                                                data-id="{{ $course->id }}"
                                                                data-current="{{ $course->is_approved }}">
                                                                <option @selected($course->is_approved == 'pending') value="pending">
                                                                    {{ __('Pending') }}</option>
                                                                <option @selected($course->is_approved == 'approved') value="approved">
                                                                    {{ __('Approved') }}</option>
                                                                <option @selected($course->is_approved == 'rejected') value="rejected">
                                                                    {{ __('Rejected') }}</option>
                                                            </select>
                                                        </td>
                                                    @endif

                                                    @if (checkAdminHasPermission('course.edit') ||
                                                            checkAdminHasPermission('course.delete') ||
                                                            checkAdminHasPermission('course.create'))
                                                        <td class="text-center">
                                                            <div>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-primary dropdown-toggle"
                                                                        type="button" id="dropdownMenu2"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenu2">
                                                                        @if (checkAdminHasPermission('course.create') && checkAdminHasPermission('course.store'))
                                                                            <a href="{{ route('admin.courses.duplicate', $course->id) }}"
                                                                                class="dropdown-item">{{ __('Duplicate') }}</a>
                                                                        @endif
                                                                        @if (checkAdminHasPermission('course.edit') && checkAdminHasPermission('course.update'))
                                                                            <a href="{{ route('admin.courses.edit-view', $course->id) }}"
                                                                                class="dropdown-item"
                                                                                target="_blank">{{ __('Edit') }}</a>
                                                                        @endif
                                                                        @if (checkAdminHasPermission('course.delete'))
                                                                            @if ($course->is_approved != Course::ISAPPROVED_APPROVED || getAdminAuthRole() === 'Super Admin')
                                                                                <a onclick="deleteCourse(event, {{ $course->id }})"
                                                                                    class="dropdown-item text-danger">{{ __('Delete') }}</a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Couse Filter')" route="admin.course-filter.create"
                                                    create="no" :message="__('No data found!')" colspan="11"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $courses->links() }}
                                </div>
                            </div>
                            <form id="delete-form" action="#" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/js/default/courses.js') }}"></script>
    <script src="{{ asset('backend/js/sweetalert.js') }}"></script>
@endpush

@push('js')
    <script>
        function deleteCourse(event, id) {
            swal.fire({
                title: "Apakah kamu yakin ingin menghapus pelatihan ini?",
                text: "Anda tidak dapat mengembalikan pelatihan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##5751e1",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').action =
                        "{{ route('admin.courses.destroy', ':id') }}".replace(':id', id);
                    document.getElementById('delete-form').submit();
                }
            })
        }
    </script>
@endpush
