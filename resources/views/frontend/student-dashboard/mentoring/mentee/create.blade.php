@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Tambah Mentoring Topik') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.mentee.store') }}" method="POST"
                          class="instructor__profile-form course-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="title">{{ __('Title') }} <code>*</code></label>
                                    <input id="title" name="title" type="text" class="form-control"
                                           placeholder="example" value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }} <code>*</code></label>
                                    <input id="description" name="description" type="text" class="form-control"
                                           placeholder="example" value="{{ old('description') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="purpose">{{ __('Purpose') }} <code>*</code></label>
                                    <input id="purpose" name="purpose" type="text" class="form-control"
                                           placeholder="example" value="{{ old('purpose') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="mentor">{{ __('Mentor') }} <code>*</code></label>
                                    <select name="mentor" id=""
                                            class="form-control select2">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($mentors as $mentor)
                                            <option value="{{ $mentor->id }}">{{ $mentor->name }}
                                                ({{ $mentor->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field">
                                <div class="form-group">
                                    <label for="file">{{ __('Upload Surat Kesediaan Mentor') }}
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
                                               accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="total_session">{{ __('Total Session') }} <code>*</code></label>
                                    <input id="total_session" name="total_session" max="7" min="1" maxlength="7"
                                           minlength="1" type="number" class="form-control"
                                           placeholder="example" value="{{ old('total_session') }}">
                                </div>
                            </div>
                            <div id="session-container" class="col-md-12 mb-4">
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
        document.getElementById('total_session').addEventListener('keyup', function () {
            let total_session = this.value;
            if (total_session === '') {
                total_session = 1;
            }

            if (total_session > 7) {
                total_session = 7;
            }
            if (total_session < 1) {
                total_session = 1;
            }
            let session_container = document.getElementById('session-container');
            let html = ``;
            for (let i = 1; i <= total_session; i++) {
                let tmp = ``;
                tmp += `<div class="my-2 border-bottom border-2 py-4">`

                {{--tmp += `<div class="form-group mb-2">`--}}
                {{--tmp += `<label for="session_${i}">{{ __('Session Name') }}</label>`--}}
                {{--tmp += `<input id="session_${i}" name="session_${i}" type="text" class="form-control" value="{{ __('Session') }} ${i}">`--}}
                {{--tmp += `</div>`--}}

                tmp += `<div class="form-group">`
                tmp += `<label for="session_date_${i}">Tanggal mentoring</label>`
                tmp += `<input id="session_date_${i}" name="session_date_${i}" type="date" class="form-control" placeholder="example" value="{{ old('session_date_${i}') }}">`
                tmp += `</div>`

                tmp += `</div>`
                html += tmp;
            }
            session_container.innerHTML = html;
        })
    </script>
@endpush
