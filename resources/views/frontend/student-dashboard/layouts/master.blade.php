@extends('frontend.layouts.master')

<!-- meta -->
@section('meta_title', __('Student Dashboard'))
<!-- end meta -->

@section('contents')
@env('local')
    {{ dump(session('access_token')) }}
@endenv
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('')" :links="[]" />
    <!-- breadcrumb-area-end -->

    <!-- dashboard-area -->
    <section class="dashboard__area section-pb-120">
        <div class="container">
            <div class="dashboard__top-wrap">
                <div class="dashboard__top-bg" data-background="{{ asset('frontend/img/frontend-cover.png') }}"></div>
                <div class="dashboard__instructor-info">
                    <div class="dashboard__instructor-info-left">
                        <div class="thumb">
                            {{-- <img src="{{ asset('frontend/img/frontend-avatar.png') }}" alt="img"> --}}
                            <img src="https://asn.bantulkab.go.id/images/simpeg/fotopns/{{ auth()->user()->nip }}.jpg" alt="img">
                        </div>
                        <div class="content">
                            <h4 class="title">{{ auth()->user()->name }}</h4>
                            <ul class="list-wrap">
                                <li>
                                    {{ auth()->user()->nip }}
                                </li>
                                {{-- <li>
                                    {{ auth()->user()->nip }}
                                </li> --}}
                                {{-- @if (auth()->user()->email)
                                <li>
                                    <img src="{{ asset('frontend/img/icons/envelope.svg') }}" alt="img"
                                        class="injectable">
                                    {{ auth()->user()->email }}
                                </li>
                                @endif --}}
                                @if (auth()->user()->phone)
                                    <li>
                                        <img src="{{ asset('frontend/img/icons/phone.svg') }}" alt="img"
                                            class="injectable">
                                        {{ auth()->user()->phone }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="content-badges">
                        @php
                            $userBadges = userAuth()->badges ?? collect();
                        @endphp
                        @if($userBadges->isNotEmpty())
                            <ul class="badge-list">
                                @foreach($userBadges as $badge)
                                    <li>
                                        <img class="badge-img" src="{{ asset($badge->image) }}" alt="img" data-toggle="tooltip" data-placement="top" title="{{ $badge->name }} - {{ $badge->pivot->category }}">
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.student-dashboard.layouts.sidebar')
                </div>
                <div class="col-lg-9">
                    @yield('dashboard-contents')
                </div>
            </div>
        </div>
    </section>
    <!-- dashboard-area-end -->
@endsection
@push('styles')
    <style>
        .content-badges {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 10px;
            background-color:rgb(63, 42, 63);
            border-radius: 15px;
            overflow: hidden;
        }

        .badge-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .badge-img{
            max-width: 70px;
            height: auto;
            border-radius: 5px;
        }

    </style>
@endpush
@push('scripts')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
