@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Penilaian Mentor') }} "{{ $mentoring->title }}"</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.mentee.feedback.store', $mentoring->id) }}" method="POST"
                        class="instructor__profile-form course-form" id="evaluasi_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Kemampuan Membimbing') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="mentoring_ability" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('mentoring_ability') == $i ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->mentoring_ability ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Ketepatan Waktu dan Kehadiran') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="punctuality_attendance" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ (old('punctuality_attendance') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->punctuality_attendance ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Penggunaan Metode dan Media Pembimbing') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="method_media_usage" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ (old('method_media_usage') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->method_media_usage ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Sikap dan Perilaku') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="attitude_behavior" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ (old('attitude_behavior') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->attitude_behavior ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Pemberian Inspirasi') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="inspirational_ability" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ (old('inspirational_ability') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->inspirational_ability ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="motivational_ability">{{ __('Pemberian Motivasi') }} <code>*</code></label>
                                    @if (!$feedback)
                                    <select class="form-select" name="motivational_ability" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 100; $i++)
                                            <option value="{{ $i }}"
                                                {{ (old('motivational_ability') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ $feedback->motivational_ability ?? '-'}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="feedback_description">{{ __('Catatan/Saran') }} <code>*</code></label>
                                    @if ($feedback)
                                        <p>{!! clean($feedback?->feedback_description) !!}</p>
                                    @else
                                        <textarea name="feedback_description" class="text-editor form-control summernote"
                                            {{ $feedback ? 'disabled' : '' }}>{{ clean($feedback?->feedback_description) }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if (!empty($mentoring->final_report) && !$feedback)
                    <div class="row">
                        <div class="col-12 justify-content-between d-flex align-items-center">
                            <button type="button" class="btn btn-primary mt-4 mb-3"
                                onclick="handleKirimFeedback(event, {{ $mentoring->id }})">
                                {{ __('Simpan Feedback') }} <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleKirimFeedback(event, id) {
            event.preventDefault();
            form = document.getElementById('evaluasi_form');

            Swal.fire({
                title: '{{ __('Kirim Feedback') }}',
                text: '{{ __('Apakah Anda yakin ingin mengirim penilaian mentor ini?') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Ya, Kirim') }}',
                cancelButtonText: '{{ __('Batal') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const originalAction = form.action;

                    form.action = "{{ route('student.mentee.feedback.store', $mentoring->id) }}";
                    form.submit();
                }
            });
        }
    </script>
@endpush
