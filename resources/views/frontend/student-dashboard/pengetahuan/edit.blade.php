@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Ubah Pengetahuan') }} &quot;{{ $pengetahuan->title }}&quot;</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form id="update-form" action="{{ route('student.pengetahuan.update', ['slug' => $pengetahuan->slug]) }}"
                        method="POST" class="instructor__profile-form course-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Category" class="me-3">{{ __('Category') }}
                                        <code>*</code></label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="blog"
                                                value="blog" @if ($pengetahuan->category == 'blog') checked @endif>
                                            <label class="form-check-label" for="blog">
                                                {{ __('Blog') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="document"
                                                value="document" @if ($pengetahuan->category == 'document') checked @endif>
                                            <label class="form-check-label" for="document">
                                                {{ __('Dokumen') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="video"
                                                value="video" @if ($pengetahuan->category == 'video') checked @endif>
                                            <label class="form-check-label" for="video">
                                                {{ __('Video') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="enrollment">Pelatihan</label>
                                    <select name="enrollment" class="form-control select2" disabled>
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($completedCourses as $enrollment)
                                            <option value="{{ $enrollment->id }}"
                                                @if ($pengetahuan->enrollment_id == $enrollment->id) selected @endif>
                                                {{ $enrollment->course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="title">{{ __('Title') }} <code>*</code></label>
                                    <input id="title" name="title" type="text" class="form-control"
                                        placeholder="example" value="{{ $pengetahuan->title }}">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="from-group mb-3">
                                    <label class="form-file-manager-label" for="">{{ __('Thumbnail') }}
                                        <code>*</code></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#thumbnail').trigger('click');">
                                            <a data-input="thumbnail" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="thumbnail_name" readonly class="form-control" type="text"
                                            name="thumbnail_name" value="{{ $pengetahuan->thumbnail }}"
                                            onclick="$('#thumbnail').trigger('click');">
                                        <input id="thumbnail" name="thumbnail" class="form-control d-none" type="file"
                                            onchange="$('#thumbnail_name').val(this.files[0].name)"
                                            accept=".jpg, .jpeg, .png">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="is_visible">{{ __('Visibilitas') }}
                                        <code>*</code></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="visibility" id="public"
                                            value="public" @if ($pengetahuan->visibility == 'public') checked @endif>
                                        <label class="form-check-label" for="public">
                                            {{ __('Public') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="visibility" id="internal"
                                            value="internal" @if ($pengetahuan->visibility == 'internal') checked @endif>
                                        <label class="form-check-label" for="internal">
                                            {{ __('Internal') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field">
                                <div class="form-group">
                                    <label for="file">{{ __('Upload Dokumen Materi') }}
                                        <code>*</code></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#file').trigger('click');">
                                            <a data-input="file" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="file_name" readonly class="form-control" type="text"
                                            name="file_name" value="{{ $pengetahuan->file }}"
                                            onclick="$('#file').trigger('click');">
                                        <input id="file" name="file" class="form-control d-none" type="file"
                                            onchange="$('#file_name').val(this.files[0].name)" accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="link-field">
                                <div class="form-group">
                                    <label for="link">{{ __('Link Materi') }}
                                        <code>*</code></label>
                                    <input id="link" name="link" type="url" class="form-control"
                                        placeholder="example" value="{{ $pengetahuan->link }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="description">{{ __('description') }}
                                        <code>*</code></label>
                                    <textarea name="description" class="text-editor form-control summernote">{{ $pengetahuan->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="content">{{ __('Content') }}
                                        <code>*</code></label>
                                    <textarea name="content" class="text-editor form-control summernote">{{ $pengetahuan->content }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="Tags">{{ __('Tags') }}</label>
                                    <select class="select2" name="tags[]" multiple="multiple">
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->name }}"
                                                @if ($pengetahuan->articleTags->contains('name', $tag->name)) selected @endif>
                                                {{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="allow_comments"
                                        id="allow_comments" @if ($pengetahuan->allow_comments) checked @endif>
                                    <label class="form-check-label" for="allow_comments">
                                        {{ __('Allow Comment') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-primary" form="update-form" type="submit">{{ __('Save') }}</button>
                    <form id="delete-form" action="{{ route('student.pengetahuan.destroy', $pengetahuan->slug) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn bg-danger text-white" type="button"
                            onclick="deletePengetahuan()">{{ __('Hapus') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Hide the file upload field and link field by default
            $("#file-upload-field").hide();
            $("#link-field").hide();

            // Show/hide fields based on the selected category
            $("input[name='category']").change(function() {
                if ($("#document").is(":checked")) {
                    $("#file-upload-field").show();
                } else {
                    $("#file-upload-field").hide();
                }

                if ($("#video").is(":checked")) {
                    $("#link-field").show();
                } else {
                    $("#link-field").hide();
                }
            }).trigger('change');
        });

        function deletePengetahuan() {
            swal.fire({
                title: "Apakah kamu yakin ingin menghapus pengetahuan ini?",
                text: "Anda tidak dapat mengembalikan pengetahuan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##5751e1",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            })
        }
    </script>
@endpush
