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
                                        placeholder="e.g. 3" required value="{{ old('total_session', 3) }}" min="3" max="24">
                                    <div id="session-warning" class="text-danger mt-1" style="display: none;">
                                        Jumlah pertemuan minimal 3 kali dan maksimal 24 kali. Silakan perbarui input Anda.
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
                                                <input type="text" name="sessions[]" class="form-control datetimepicker"
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr(".datetimepicker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            altInput: true,
            altFormat: "l, d F Y - H:i",
            locale: "id"
        });

        const totalSessionInput = document.getElementById('total_session');
        const sessionGroup = document.getElementById('session-datetime-group');
        const sessionWarning = document.getElementById('session-warning');
        const sessionWrapper = document.getElementById('session-wrapper');
        const oldSessions = JSON.parse(sessionWrapper.dataset.oldSessions || '[]');

        let flatpickrInstances = [];

        function destroyFlatpickrs() {
            flatpickrInstances.forEach(fp => fp.destroy());
            flatpickrInstances = [];
        }

        function generateSessionInputs(total) {
            sessionWrapper.innerHTML = '';
            destroyFlatpickrs();

            for (let i = 0; i < total; i++) {
                const inputGroup = document.createElement('div');
                inputGroup.className = 'input-group mb-2';

                const span = document.createElement('span');
                span.className = 'input-group-text text-dark';
                span.innerText = `Pertemuan ${i + 1}`;

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'sessions[]';
                input.className = 'form-control datetimepicker';
                input.required = true;

                // Set old value if available
                if (oldSessions[i]) {
                    input.value = oldSessions[i];
                }

                inputGroup.appendChild(span);
                inputGroup.appendChild(input);
                sessionWrapper.appendChild(inputGroup);
            }

            setupFlatpickrs();
        }

        function setupFlatpickrs() {
            const inputs = sessionWrapper.querySelectorAll('.datetimepicker');
            let previousDate = null;

            inputs.forEach((input, index) => {
                const instance = flatpickr(input, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    altInput: true,
                    altFormat: "l, d F Y - H:i",
                    locale: "id",
                    minDate: previousDate ? new Date(previousDate.getTime() + 60000) : null, // +1 menit dari sebelumnya
                    onChange: function (selectedDates) {
                        if (selectedDates.length > 0) {
                            const selectedDate = selectedDates[0];

                            // Jika pertemuan pertama, batasi semua input ke tahun yang sama
                            if (index === 0) {
                                const year = selectedDate.getFullYear();
                                const maxDateInYear = new Date(year, 11, 31, 23, 59);

                                for (let j = index + 1; j < flatpickrInstances.length; j++) {
                                    const nextFp = flatpickrInstances[j];
                                    const nextMinDate = new Date(selectedDate.getTime() + 60000);

                                    nextFp.set('minDate', nextMinDate);
                                    nextFp.set('maxDate', maxDateInYear);
                                }
                            } else {
                                // Jika bukan pertemuan pertama, hanya set minDate untuk input berikutnya
                                const nextInput = inputs[index + 1];
                                if (nextInput && flatpickrInstances[index + 1]) {
                                    flatpickrInstances[index + 1].set('minDate', new Date(selectedDate.getTime() + 60000));
                                }
                            }
                        }
                    }
                });

                if (input.value) {
                    const parsed = instance.parseDate(input.value, "Y-m-d H:i");
                    if (parsed) {
                        previousDate = parsed;
                    }
                }

                flatpickrInstances.push(instance);
            });
        }

        function updateSessionsVisibility() {
            const total = parseInt(totalSessionInput.value);

            if (!isNaN(total) && total >= 3 && total <= 24) {
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
                destroyFlatpickrs();
            }
        }

        totalSessionInput.addEventListener('input', function () {
            oldSessions.length = 0; // Clear old values if user changes input
            
            const total = parseInt(totalSessionInput.value);
            if (!isNaN(total) && total >= 3 && total <= 24){
                generateSessionInputs(total);
            } 
            updateSessionsVisibility();
        });

        updateSessionsVisibility();
    });
</script>
@endpush
