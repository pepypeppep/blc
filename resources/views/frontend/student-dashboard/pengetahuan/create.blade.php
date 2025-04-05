@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Tambah Pengetahuan') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.pengetahuan.store') }}" method="POST"
                        class="instructor__profile-form course-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Category" class="me-3">{{ __('Category') }}
                                        <code>*</code></label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="blog"
                                                value="blog"
                                                @if (old('category') == 'blog') checked @else checked @endif>
                                            <label class="form-check-label" for="blog">
                                                {{ __('Blog') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="document"
                                                value="document" @if (old('category') == 'document') checked @endif>
                                            <label class="form-check-label" for="document">
                                                {{ __('Dokumen') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="category" id="video"
                                                value="video" @if (old('category') == 'video') checked @endif>
                                            <label class="form-check-label" for="video">
                                                {{ __('Video') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="enrollment">Pelatihan
                                        <code>*</code></label>
                                    <select name="enrollment" class="form-control select2">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($enrollments as $enrollment)
                                            <option value="{{ $enrollment->id }}"
                                                @if (old('enrollment') == $enrollment->id) selected @endif>
                                                {{ $enrollment->course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="title">{{ __('Title') }} <code>*</code></label>
                                    <input id="title" name="title" type="text" class="form-control"
                                        placeholder="example" value="{{ old('title') }}">
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
                                            name="thumbnail_name" value="#"
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
                                            value="public" @if (old('visibility') == 'public') checked @else checked @endif>
                                        <label class="form-check-label" for="public">
                                            {{ __('Public') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="visibility" id="internal"
                                            value="internal" @if (old('visibility') == 'internal') checked @endif>
                                        <label class="form-check-label" for="internal">
                                            {{ __('Internal') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field">
                                <div class="form-group">
                                    <label for="file">{{ __('Upload Dokument Materi') }}
                                        <code>*</code></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#file').trigger('click');">
                                            <a data-input="file" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="file_name" readonly class="form-control" type="text"
                                            name="file_name" value="#" onclick="$('#file').trigger('click');">
                                        <input id="file" name="file" class="form-control d-none" type="file"
                                            onchange="$('#file_name').val(this.files[0].name)"
                                            accept=".pdf, .docx, .pptx">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="link-field">
                                <div class="form-group">
                                    <label for="link">{{ __('Link Materi') }}
                                        <code>*</code></label>
                                    <input id="link" name="link" type="url" class="form-control"
                                        placeholder="example" value="{{ old('link') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="content">{{ __('Content') }}
                                        <code>*</code></label>
                                    <textarea name="content" class="text-editor form-control summernote">
@if (old('content'))
{{ old('content') }}
@else
#
@endif
</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="Tags">{{ __('Tags') }}
                                        <code>*</code></label>
                                    <select class="form-control select2" id="Tags" name="Tags[]"
                                        multiple="multiple" style="width: 100%;">
                                        <option value="">{{ __('Select One') }}</option>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="allow_comments"
                                        id="allow_comments" value="yes" checked>
                                    <label class="form-check-label" for="allow_comments">
                                        {{ __('Allow Comments') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                </div>
                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </form>
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
    </script>
@endpush
