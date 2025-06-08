@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Mentoring') }} "{{ $mentoring->title }}"</h4>
            @if ($mentoring->status == Mentoring::STATUS_EVALUATION)
                <div class="mt-2 alert alert-warning" role="alert">
                    <strong>{{ __('Silahkan Menyelesaikan Evaluasi melalui tautan berikut') }}</strong>
                    <br>
                    <a
                        href="{{ route('student.mentor.evaluasi', $mentoring->id) }}"><span>{{ __('Evaluasi Sekarang') }}</span></a>
                </div>
            @endif
            @if ($mentoring->status == Mentoring::STATUS_DONE)
                <div class="mt-2 alert alert-info" role="alert">
                    <strong>{{ __('Evaluasi telah selesai, jika ingin melihat hasilnya silahkan klik tautan berikut') }}</strong>
                    <br>
                    <a
                        href="{{ route('student.mentor.evaluasi', $mentoring->id) }}"><span>{{ __('Lihat Evaluasi') }}</span></a>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div>
                            <h6 class="title">{{ __('Title') }}</h6>
                            <p>{{ $mentoring->title }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div>
                            <h6 class="description">{{ __('Description') }}</h6>
                            <span>{{ $mentoring->description }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div>
                            <h6 class="purpose">{{ __('Purpose') }}</h6>
                            <span>{{ $mentoring->purpose }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div>
                            <h6 class="mentor">{{ __('Mentor') }}</h6>
                            <span>{{ $mentoring->mentor->name }}({{ $mentoring->mentor->email }})</span>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <h6 for="file">{{ __('Surat Kesediaan Mentor') }}
                            </h6>
                            <span>{{ $mentoring->mentor_availability_letter ? $mentoring->mentor_availability_letter : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <h4 class="title">{{ __('Sessions') }}</h4>
                @foreach ($mentoring->mentoringSessions as $key => $session)
                    <div class="my-2 border-bottom border-2 py-4">
                        <h5>{{ __('Session') }} {{ \Carbon\Carbon::parse($session->mentoring_date)->format('d M Y') }}
                        </h5>
                        @if ($mentoring->status == Mentoring::STATUS_PROCESS || $mentoring->status == Mentoring::STATUS_DONE)
                            @if (
                                $session->activity &&
                                    $session->description &&
                                    $session->image &&
                                    (!$session->mentoring_note && !$session->mentoring_instructions) &&
                                    $mentoring->status == 'Proses')
                                @include('frontend.student-dashboard.mentoring.mentor.partials.session-submit')
                            @elseif ($mentoring->status == 'Proses' && $session->mentoring_note && $session->mentoring_instructions)
                                @include('frontend.student-dashboard.mentoring.mentor.partials.session-done')
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @if ($mentoring->status == Mentoring::STATUS_SUBMISSION)
            <div class="row">
                <div class="col-12 justify-content-between d-flex align-items-center">
                    <button type="button" onclick="handleTolakMentoring(event, {{ $mentoring->id }})"
                        class="btn btn-danger mt-4 mb-3">
                        {{ __('Tolak Mentoring') }} <i class="fa fa-arrow-right"></i>
                    </button>
                    <form action="{{ route('student.mentor.approve', $mentoring->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn mt-4 mb-3">
                            {{ __('Lanjutkan Proses Mentoring') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function handleTolakMentoring(event, id) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin menolak mentoring ini?',
                text: "Silakan berikan alasan penolakan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Totak!',
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penolakan',
                inputValidator: (value) => {
                    if (!value || value.trim() === "") {
                        return 'Alasan penolakan wajib diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value || '';
                    $.ajax({
                        method: "post",
                        url: base_url + "/student/mentor/" + id + "/reject",
                        data: {
                            _token: csrf_token,
                            reason: notes
                        },
                        success: function(data) {
                            if (data.status === "success") {
                                toastr.success(data.message);
                                window.location.href = base_url + "/student/mentor";
                            }
                        },
                        error: function(xhr, status, error) {
                            const message = xhr.responseJSON?.message || '-';
                            toastr.error(message);
                        },
                    });
                } else {
                    swal.fire('Penolakan dibatalkan', '', 'error');
                }
            })
        };
    </script>
@endpush
