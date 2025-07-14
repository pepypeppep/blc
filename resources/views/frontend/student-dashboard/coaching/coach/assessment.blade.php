@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Coaching\app\Models\Coaching;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="title">{{ __('Assesment') }} "{{ $data->coaching->title }}" - {{ $data->coachee->name }}</h4>
            <a href="{{ route('student.coach.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.coach.penilaian.store', [$data->coaching->id, $data->coachee->id]) }}" method="POST"
                        class="instructor__profile-form course-form" id="penilaian_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="goal_achieved">{{ __('Target/Tujuan Tercapai?') }} <code>*</code></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="goal_achieved" id="goal_achieved_yes"
                                            value="1"
                                            {{ @$data->assessment->goal_achieved == '1' ? 'checked' : (old('goal_achieved') == '1' ? 'checked' : '') }}
                                            {{ $data->coaching->status == coaching::STATUS_DONE ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="goal_achieved_yes">{{ __('Yes') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="goal_achieved" id="goal_achieved_no"
                                            value="0"
                                            {{ @$data->assessment->goal_achieved == '0' ? 'checked' : (old('goal_achieved') == '0' ? 'checked' : '') }}>
                                        <label class="form-check-label" for="goal_achieved_no">{{ __('Tidak') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($data->coaching->status == Coaching::STATUS_DONE)
                                            <h6>{{ __('Catatan Target') }}</h6>
                                            <p>{!! clean(@$data->assessment?->goal_description) !!}</p>
                                        @else
                                            <label for="goal_description">{{ __('Catatan Target') }}
                                                <code>*</code></label>
                                            <textarea name="goal_description" class="text-editor form-control summernote"
                                                {{$data->coaching->status == Coaching::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$data->assessment?->goal_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="discipline_level">{{ __('Tingkat Kedisiplinan') }} <code>*</code></label>

                                    @if ($data->coaching->status != Coaching::STATUS_DONE)
                                    <select class="form-select" name="discipline_level" required>
                                        <option value="" disabled selected>Pilih Nilai</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                {{ @$data->assessment->discipline_level == $i ? 'selected' : (old('discipline_level') == $i ? 'selected' : '') }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @else
                                        {{ @$data->assessment->discipline_level }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($data->coaching->status == Coaching::STATUS_DONE)
                                            <h6>{{ __('Catatan Kedisiplinan') }}</h6>
                                            <p>{!! clean(@$data->assessment?->discipline_description) !!}</p>
                                        @else
                                            <label for="discipline_description">{{ __('Catatan Kedisiplinan') }}
                                                <code>*</code></label>
                                            <textarea name="discipline_description" class="text-editor form-control summernote"
                                                {{ $data->coaching->status == Coaching::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$data->assessment?->discipline_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="teamwork_level">{{ __('Tingkat Kerjasama') }} <code>*</code></label>

                                    @if ($data->coaching->status != Coaching::STATUS_DONE)
                                        <select class="form-select" name="teamwork_level" required>
                                            <option value="" disabled selected>Pilih Nilai</option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}"
                                                    {{ @$data->assessment->teamwork_level == $i ? 'selected' : (old('teamwork_level') == $i ? 'selected' : '') }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    @else
                                        {{ @$data->assessment->teamwork_level }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($data->coaching->status == Coaching::STATUS_DONE)
                                            <h6>{{ __('Catatan Kerjasama') }}</h6>
                                            <p>{!! clean(@$data->assessment?->teamwork_description) !!}</p>
                                        @else
                                            <label for="teamwork_description">{{ __('Catatan Kerjasama') }}
                                                <code>*</code></label>
                                            <textarea name="teamwork_description" class="text-editor form-control summernote"
                                                {{ $data->coaching->status == Coaching::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$data->assessment?->teamwork_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group">
                                    <label for="initiative_level">{{ __('Tingkat Inisiatif') }} <code>*</code></label>

                                    @if ($data->coaching->status != Coaching::STATUS_DONE)
                                        <select class="form-select" name="initiative_level" required>
                                            <option value="" disabled selected>Pilih Nilai</option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}"
                                                    {{ @$data->assessment->initiative_level == $i ? 'selected' : (old('initiative_level') == $i ? 'selected' : '') }}>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    @else
                                        {{ @$data->assessment->initiative_level }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-group mt-4">
                                        @if ($data->coaching->status == Coaching::STATUS_DONE)
                                            <h6>{{ __('Catatan Initiatif') }}</h6>
                                            <p>{!! clean(@$data->assessment?->initiative_description) !!}</p>
                                        @else
                                            <label for="initiative_description">{{ __('Catatan Inisiatif') }}
                                                <code>*</code></label>
                                            <textarea name="initiative_description" class="text-editor form-control summernote"
                                                {{ $data->coaching->status == Coaching::STATUS_DONE ? 'disabled' : '' }}>{{ clean(@$data->assessment?->initiative_description) }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if ($data->coaching->status == Coaching::STATUS_EVALUATION)
                <div class="row">
                    <div class="col-12 justify-content-between d-flex align-items-center">
                        <button type="submit" class="btn btn-danger mt-4 mb-3" form="penilaian_form">
                            {{ __('Simpan Penilaian') }} <i class="fa fa-arrow-right"></i>
                        </button>
                        <button type="button" class="btn btn-primary mt-4 mb-3"
                            onclick="handleKirimPenilaianDanKirimKeKepegawaian(event, {{ $data->coaching->id }}, {{$data->coachee->id}})">
                            {{ __('Kirim Penilaian Ke Kepegawaian') }} <i class="fa fa-arrow-right"></i>
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
        function handleKirimPenilaianDanKirimKeKepegawaian(event, coachingId, coacheeId) {
            event.preventDefault();
            form = document.getElementById('penilaian_form');

            Swal.fire({
                title: '{{ __('Kirim Penilaian') }}',
                text: '{{ __('Nilai yang telah dikirim tidak dapat diubah. Apakah Anda yakin ingin mengirim penilaian ini ke kepegawaian?') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Ya, Kirim') }}',
                cancelButtonText: '{{ __('Batal') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Store original action to restore if needed
                    const originalAction = form.action;

                    // Set new action
                    form.action = "{{ route('student.coach.penilaian.kirim', [$data->coaching->id, $data->coachee->id]) }}";
                    form.submit();
                }
            });
        }
    </script>
@endpush
