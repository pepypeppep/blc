@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Evaluasi') }} "{{ $mentoring->title }}"</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.mentor.evaluasi.store', $mentoring->id) }}" method="POST"
                        class="instructor__profile-form course-form" id="evaluasi_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="target">{{ __('Target/Tujuan Tercapai?') }} <code>*</code></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="target" id="target_yes"
                                            value="1"
                                            {{ @$review->is_target == '1' ? 'checked' : (old('target') == '1' ? 'checked' : '') }}
                                            {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="target_yes">{{ __('Yes') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="target" id="target_no"
                                            value="0"
                                            {{ @$review->is_target == '0' ? 'checked' : (old('target') == '0' ? 'checked' : '') }}>
                                        <label class="form-check-label" for="target_no">{{ __('No') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($mentoring->status == Mentoring::STATUS_DONE)
                                            <h6>{{ __('Catatan Target') }}</h6>
                                            <p>{!! clean(@$review?->target_description) !!}</p>
                                        @else
                                            <label for="target_description">{{ __('Catatan Target') }}
                                                <code>*</code></label>
                                            <textarea name="target_description" class="text-editor form-control summernote"
                                                {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$review?->target_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="tingkat_disiplin">{{ __('Tingkat Kedisiplinan') }} <code>*</code></label>
                                    <div class="form-check form-check-inline d-flex flex-row">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="form-check form-check-inline mr-2">
                                                <input class="form-check-input" type="radio" name="tingkat_disiplin"
                                                    id="disiplin{{ $i }}" value="{{ $i }}"
                                                    {{ @$review->discipline == $i ? 'checked' : (old('tingkat_disiplin') == $i ? 'checked' : '') }}
                                                    {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>
                                                <label class="form-check-label"
                                                    for="disiplin{{ $i }}">{{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($mentoring->status == Mentoring::STATUS_DONE)
                                            <h6>{{ __('Catatan Kedisiplinan') }}</h6>
                                            <p>{!! clean(@$review?->discipline_description) !!}</p>
                                        @else
                                            <label for="disiplin_description">{{ __('Catatan Kedisiplinan') }}
                                                <code>*</code></label>
                                            <textarea name="disiplin_description" class="text-editor form-control summernote"
                                                {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$review?->discipline_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="kerjasama">{{ __('Tingkat Kerjasama') }} <code>*</code></label>
                                    <div class="form-check form-check-inline d-flex flex-row">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="form-check form-check-inline mr-2">
                                                <input class="form-check-input" type="radio" name="kerjasama"
                                                    id="disiplin{{ $i }}" value="{{ $i }}"
                                                    {{ @$review->teamwork == $i ? 'checked' : (old('kerjasama') == $i ? 'checked' : '') }}
                                                    {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>
                                                <label class="form-check-label"
                                                    for="disiplin{{ $i }}">{{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($mentoring->status == Mentoring::STATUS_DONE)
                                            <h6>{{ __('Catatan Kerjasama') }}</h6>
                                            <p>{!! clean(@$review?->teamwork_description) !!}</p>
                                        @else
                                            <label for="kerjasama_description">{{ __('Catatan Kerjasama') }}
                                                <code>*</code></label>
                                            <textarea name="kerjasama_description" class="text-editor form-control summernote"
                                                {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$review?->teamwork_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="inisiatif">{{ __('Tingkat Initiatif') }} <code>*</code></label>
                                    <div class="form-check form-check-inline d-flex flex-row">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="form-check form-check-inline mr-2">
                                                <input class="form-check-input" type="radio" name="inisiatif"
                                                    id="inisiatif{{ $i }}" value="{{ $i }}"
                                                    {{ @$review->initiative == $i ? 'checked' : (old('inisiatif') == $i ? 'checked' : '') }}
                                                    {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>
                                                <label class="form-check-label"
                                                    for="inisiatif{{ $i }}">{{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($mentoring->status == Mentoring::STATUS_DONE)
                                            <h6>{{ __('Catatan Initiatif') }}</h6>
                                            <p>{!! clean(@$review?->teamwork_description) !!}</p>
                                        @else
                                            <label for="inisiatif_description">{{ __('Catatan Initiatif') }}
                                                <code>*</code></label>
                                            <textarea name="inisiatif_description" class="text-editor form-control summernote"
                                                {{ $mentoring->status == Mentoring::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$review?->initiative_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if ($mentoring->status == Mentoring::STATUS_EVALUATION)
                    <div class="row">
                        <div class="col-12 justify-content-between d-flex align-items-center">
                            <button type="submit" class="btn btn-danger mt-4 mb-3" form="evaluasi_form">
                                {{ __('Simpan Evaluasi') }} <i class="fa fa-arrow-right"></i>
                            </button>
                            <button type="button" class="btn btn-primary mt-4 mb-3"
                                onclick="handleKirimEvaluasiDanKirimKeKepegawaian(event, {{ $mentoring->id }})">
                                {{ __('Kirim Evaluasi Ke Kepegawaian') }} <i class="fa fa-arrow-right"></i>
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
        function handleKirimEvaluasiDanKirimKeKepegawaian(event, id) {
            event.preventDefault();
            form = document.getElementById('evaluasi_form');

            Swal.fire({
                title: '{{ __('Kirim Evaluasi') }}',
                text: '{{ __('Apakah Anda yakin ingin mengirim evaluasi ini ke kepegawaian?') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Ya, Kirim') }}',
                cancelButtonText: '{{ __('Batal') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Store original action to restore if needed
                    const originalAction = form.action;

                    // Set new action
                    form.action = "{{ route('student.mentor.evaluasi.kirim', $mentoring->id) }}";
                    form.submit();
                }
            });
        }
    </script>
@endpush
