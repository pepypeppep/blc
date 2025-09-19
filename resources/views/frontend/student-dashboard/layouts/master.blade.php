@extends('frontend.layouts.master')

<!-- meta -->
@section('meta_title', __('Student Dashboard'))
<!-- end meta -->

@section('contents')
    @env('local')
        <input type="text" value="{{ session('access_token') }}" class="w-100" onclick="this.select()">
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
                            <img src="https://asn.bantulkab.go.id/images/simpeg/fotopns/{{ auth()->user()->nip }}.jpg"
                                onerror="this.onerror=null;this.src='{{ route('get.section.asset', [1, 'default_avatar']) . '?module=general' }}';"
                                alt="img">
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
                        @if ($userBadges->isNotEmpty())
                            <ul class="badge-list">
                                @foreach ($userBadges as $badge)
                                    <li>
                                        <img class="badge-img" src="{{ asset($badge->image) }}" alt="img"
                                            data-toggle="tooltip" data-placement="top"
                                            title="{{ $badge->name }} - {{ $badge->pivot->category }}">
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
            background-color: rgb(63, 42, 63);
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

        .badge-img {
            max-width: 70px;
            height: auto;
            border-radius: 5px;
        }

        .dashboard__action a,
        .dashboard__action button {
            font-size: 14px;
            height: 30px;
            line-height: 1;
            padding: 0 14px;
            background: rgba(15, 36, 222, 0.25);
            color: rgba(197, 165, 22, 0.13);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 30px;
            transition: all 0.2s ease;
            border: none;
            outline: none;
            box-shadow: none;
        }

        .dashboard__action a:hover,
        .dashboard__action button:hover {
            background: rgba(15, 36, 222, 0.25);
            color: var(--tg-common-color-white);
        }

        .dashboard__action .btn-action-warning {
            background: rgba(239, 172, 47, 1);
            color: var(--tg-common-color-white);
        }

        .dashboard__action .btn-action-warning:hover {
            background: rgba(239, 172, 47, 0.20);
            color: rgba(239, 172, 47, 1);
        }

        .dashboard__action .btn-action-primary {
            background: rgba(47, 87, 239, 1);
            color: var(--tg-common-color-white);
        }

        .dashboard__action .btn-action-primary:hover {
            background: rgba(47, 87, 239, 0.20);
            color: var(--tg-theme-primary);
        }

        .dashboard__action .btn-action-success {
            background: rgba(40, 167, 69, 1);
            color: var(--tg-common-color-white);
        }

        .dashboard__action .btn-action-success:hover {
            background: rgba(40, 167, 69, 0.20);
            color: rgba(40, 167, 69, 1);
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
