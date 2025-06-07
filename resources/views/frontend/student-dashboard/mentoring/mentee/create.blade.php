@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Tambah Tema Mentoring') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.mentee.store') }}" method="POST"
                        class="tema-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="title">{{ __('Title') }} <code>*</code></label>
                                    <input id="title" name="title" type="text" class="form-control"
                                        placeholder="example" required value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="main_issue">{{ __('Main Issue') }}<code>*</code></label>
                                    <textarea id="main_issue" name="main_issue" class="text-editor form-control summernote">{{ old('main_issue') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="purpose">{{ __('Purpose') }}<code>*</code></label>
                                    <textarea id="purpose" name="purpose" class="text-editor form-control summernote">{{ old('purpose') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="alert alert-info">
                                    <strong>Ketentuan Pelaksanaan Pertemuan Mentoring:</strong><br>
                                    - Wajib melaksanakan minimal <strong>3 kali pertemuan</strong>.<br>
                                    - Dalam satu bulan hanya diperbolehkan maksimal <strong>2 pertemuan</strong>.<br>
                                    - Setiap pertemuan berdurasi <strong>2 JPL (90 menit)</strong>.
                                </div>
                                @if ($errors->has('sessions'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('sessions') }}
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="total_session">{{ __('Total Session') }}<code>*</code></label>
                                    <input id="total_session" name="total_session" type="number" class="form-control"
                                        placeholder="e.g. 3" required value="{{ old('total_session', 3) }}" min="3">
                                    <div id="session-warning" class="text-danger mt-1" style="display: none;">
                                        Jumlah pertemuan minimal 3 kali. Silakan perbarui input Anda.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="session-datetime-group" style="{{ old('total_session', 3) >= 3 ? '' : 'display: none;' }}">
                                <div class="form-group">
                                    <label id="session-datetime-label">{{ __('Session Datetime') }}<code>*</code></label>
                                    <div id="session-wrapper" aria-labelledby="session-datetime-label" data-old-sessions='@json(old("sessions", []))'>
                                        @php
                                            $totalSessions = old('total_session', 3);
                                            $sessions = old('sessions', []);
                                        @endphp
                                        @for ($i = 0; $i < $totalSessions; $i++)
                                            <div class="input-group mb-2">
                                                <span class="input-group-text text-dark">Pertemuan {{ $i + 1 }}</span>
                                                <input type="datetime-local" name="sessions[]" class="form-control" 
                                                    value="{{ $sessions[$i] ?? '' }}" required>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="mentor">{{ __('Mentor') }}<code>*</code></label>
                                    <select name="mentor" id="mentor" class="form-control select2" required>
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($mentors as $mentor)
                                            <option value="{{ $mentor->id }}"@if (old('mentor') == $mentor->id) selected @endif>{{ $mentor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2 mb-4" id="file-upload-field">
                                <div class="form-group">
                                    <label for="file">{{ __('Upload Mentor Availability Letter') }}
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
                                            accept=".pdf" required>
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
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalSessionInput = document.getElementById('total_session');
        const sessionGroup = document.getElementById('session-datetime-group');
        const sessionWarning = document.getElementById('session-warning');
        const sessionWrapper = document.getElementById('session-wrapper');

        const oldSessions = JSON.parse(sessionWrapper.dataset.oldSessions || '[]');

        function generateSessionInputs(total) {
            sessionWrapper.innerHTML = '';

            for (let i = 0; i < total; i++) {
                const inputGroup = document.createElement('div');
                inputGroup.className = 'input-group mb-2';

                const span = document.createElement('span');
                span.className = 'input-group-text text-dark';
                span.innerText = `Pertemuan ${i + 1}`;

                const input = document.createElement('input');
                input.type = 'datetime-local';
                input.name = 'sessions[]';
                input.className = 'form-control';
                input.required = true;

                // Set old value if available
                if (oldSessions[i]) {
                    input.value = oldSessions[i];
                }

                inputGroup.appendChild(span);
                inputGroup.appendChild(input);
                sessionWrapper.appendChild(inputGroup);
            }
        }

        function updateSessionsVisibility() {
            const total = parseInt(totalSessionInput.value);

            if (!isNaN(total) && total >= 3) {
                sessionGroup.style.display = 'block';
                sessionWarning.style.display = 'none';

                // Cuma generate ulang kalau tidak ada old value
                if (oldSessions.length === 0) {
                    generateSessionInputs(total);
                }
            } else {
                sessionGroup.style.display = 'none';
                sessionWarning.style.display = 'block';
                sessionWrapper.innerHTML = '';
            }
        }

        totalSessionInput.addEventListener('input', function () {
            oldSessions.length = 0; // Clear old values if user changes input
            updateSessionsVisibility();
            generateSessionInputs(parseInt(totalSessionInput.value));
        });

        updateSessionsVisibility();
    });
</script>
@endpush
