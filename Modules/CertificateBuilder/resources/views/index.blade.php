@extends('admin.master_layout')
@section('title')
    <title>{{ __('Certificate Builder') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Certificate Builder') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Builder') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Certificate List') }}</h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">{{ __('Add New') }}</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Background') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($certificates as $certificate)
                                                <tr>
                                                    <td>{{ $certificate->id }}</td>
                                                    <td>{{ $certificate->description }}</td>
                                                    <td>
                                                        <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                                            alt="" style="width: 50%; height: auto;">
                                                    </td>
                                                    <td>{{ $certificate->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('admin.certificate-builder.edit', $certificate->id) }}"
                                                                class="btn btn-primary mr-2">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form
                                                                action="{{ route('admin.certificate-builder.destroy', $certificate->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('{{ __('Are you sure to delete this certificate?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $certificates->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ __('Upload Certificate') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.certificate-builder.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <center>
                            <div class="form-group">
                                <label for="">{{ __('Front Image') }} <code>( 930px * 600px )
                                        *</code></label>
                                <div id="image-preview-background" class="image-preview w-100">
                                    <label for="image-upload-background"
                                        id="image-label-background">{{ __('Image') }}</label>
                                    <input type="file" name="background" id="image-upload-background" accept="image/*"
                                        required>
                                </div>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload-background",
            preview_box: "#image-preview-background",
            label_field: "#image-label-background",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        })
        $.uploadPreview({
            input_field: "#image-upload-background2",
            preview_box: "#image-preview-background2",
            label_field: "#image-label-background2",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        })
    </script>
@endpush
