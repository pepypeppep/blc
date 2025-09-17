@extends('admin.master_layout')
@section('title')
    <title>{{ __('Error Report') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Error Report') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Error Report') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.course-report.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <select name="is_approved" id="is_approved" class="form-control">
                                                <option value="">{{ __('Select Status Approval') }}</option>
                                                <option value="approved"
                                                    {{ request('is_approved') == 'approved' ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request('is_approved') == 'rejected' ? 'selected' : '' }}>
                                                    {{ __('In-Active') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request('is_approved') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Error Report List') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Reporter') }}</th>
                                                <th>{{ __('Course') }}</th>
                                                <th>{{ __('Chapter') }}</th>
                                                <th>{{ __('Lesson') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($courseReport as $index => $data)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $data->user->name }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.courses.edit-view', $data->module_model->id) }}"
                                                            target="_blank"
                                                            title="Klik untuk membuka pelatihan">{{ $data->module_model->title }}</a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.courses.edit', [$data->module_model->id, 3]) }}"
                                                            target="_blank"
                                                            title="Klik untuk membuka bab">{{ $data->lesson_model->courseChapter->title }}</a>
                                                    </td>
                                                    <td>{{ $data->lesson_model->title }}</td>
                                                    <td class="course-table-approve">
                                                        <select name=""
                                                            class="form-control course-change-status badge {{ $data->status == 'reported' ? 'badge-danger' : 'badge-success' }}"
                                                            data-id="{{ $data->id }}" style="cursor: pointer;">
                                                            <option @selected($data->status == 'reported') value="reported">
                                                                Dilaporkan
                                                            </option>
                                                            <option @selected($data->status == 'solved') value="solved">
                                                                Selesai
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>{{ $data->description }}</td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Error Report')" route="" create="no"
                                                    :message="__('No data found!')" colspan="9"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $courseReport->links() }}
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

    <script>
        $(".course-change-status").on("change", function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            $.ajax({
                method: "PUT",
                url: base_url + "/admin/course-report/" + id,
                data: {
                    _token: csrf_token,
                },
                success: function(data) {
                    console.log(data);

                    if (data.status == "success") {
                        toastr.success(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status != 200) {
                        toastr.error(xhr.responseJSON.message);
                    }
                },
            });
        });

        function handleDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to delete this Error Report?') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes') }}',
                cancelButtonText: '{{ __('No') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endpush

@push('css')
    <style>
    </style>
@endpush
