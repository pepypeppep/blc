@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Mentoring') }} "{{ $mentoring->title }}"</h4>
            @if ($mentoring->status == Mentoring::STATUS_REJECT)
                <h6>Alasan Penolakan: </h6>
                <div class="mt-2 alert alert-danger" role="alert">
                    {{ $mentoring->reason }}
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
                            <h6>{{ __('Surat Kesediaan Mentor') }}</h6>
                            <a href="{{ route('student.mentee.surat-kesediaan', $mentoring->id) }}"
                                target="_blank">{{ $mentoring->mentor_availability_letter }}</a>
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
                        @if ($mentoring->status === Mentoring::STATUS_PROCESS || $mentoring->status === Mentoring::STATUS_DONE)
                            @if (\Carbon\Carbon::parse($session->mentoring_date) < \Carbon\Carbon::now())
                                @if ($session->status == 'pending')
                                    @include('frontend.student-dashboard.mentoring.mentee.partials.session-submit')
                                @else
                                    @include('frontend.student-dashboard.mentoring.mentee.partials.session-done')
                                @endif
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @if ($mentoring->status == Mentoring::STATUS_DRAFT)
            <div class="row">
                <div class="col-12 text-end">
                    <form action="{{ route('student.mentee.ajukan', $mentoring->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn mt-4 mb-3">
                            {{ __('Ajukan Mentoring') }} <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
