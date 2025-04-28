<section class="newsletter__area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="newsletter__img-wrap">
                    <img src="{{ asset($newsletterSection?->global_content?->image) }}" alt="img">
                    <img src="{{ asset('frontend/img/others/newsletter_shape01.png') }}" alt="img" data-aos="fade-up"
                        data-aos-delay="400">
                    <img src="{{ asset('frontend/img/others/newsletter_shape02.png') }}" alt="img"
                        class="alltuchtopdown">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="newsletter__content">
                    <h2 class="title">
                        <b>{{ __('Ambil kesempatan untuk melanjutkan pendidikan') }}!</b>
                    </h2>
                    <div class="row">
                        @if (now() > $schedule->start_at)
                        <div class="col-lg-4 col-sm-6">
                            <div class="fact__item">
                                <h2 class="count"><span class="odometer" data-count="{{ $totalPendidikanLanjutan }}">
                                </h2>
                                <p>{{ __('Lowongan') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="fact__item">
                                <h2 class="count"><span class="odometer"
                                        data-count="{{ $totalFormasiPendidikanLanjutan }}"></h2>
                                <p>{{ __('Formasi') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="fact__item">
                                <h2 class="count"><span class="odometer"
                                        data-count="{{ $totalInstansiPendidikanLanjutan }}"></h2>
                                <p>{{ __('Instansi') }}</p>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-12 col-sm-12">
                            <div class="fact__item" style="text-align: left;">
                                <p style="width: fit-content; animation: moveLeftRight 1s infinite alternate cubic-bezier(.5, 0, .5, 1);"
                                    class="font-weight-bold"><i class="fas fa-arrow-alt-circle-right"></i> <span style="text-decoration: underline;">Lowongan Pendidikan Lanjutan akan segera dibuka.</span></p>
                                    <style>
                                        @keyframes moveLeftRight {
                                            0% {
                                                transform: translateX(0);
                                            }
                                            100% {
                                                transform: translateX(10px);
                                            }
                                        }
                                    </style>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="newsletter__shape">
        <img src="{{ asset('frontend/img/others/newsletter_shape03.png') }}" alt="img" data-aos="fade-left"
            data-aos-delay="400">
    </div>
</section>
