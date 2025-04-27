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
                    <h2 class="title"><b>{{ __('Pendidikan Lanjutan') }}</b> <br>
                        <b>{{ __('Ambil kesempatan untuk jenjang pendidikan') }}!</b>
                    </h2>
                    <div class="row">
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
                                <p>{{ __('Formasi dibuka') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="fact__item">
                                <h2 class="count"><span class="odometer"
                                        data-count="{{ $totalInstansiPendidikanLanjutan }}"></h2>
                                <p>{{ __('Instansi') }}</p>
                            </div>
                        </div>
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
