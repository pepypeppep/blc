<section class="newsletter__area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="newsletter__img-wrap">
                    <img src="{{ route('get.section.asset',[$newsletterSection?->id,'image']) }}" alt="img">
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
                    <div class="courses__item-bottom">
                    <div class="button">
                        <a href="{{ route('student.continuing-education') }}" class="already-enrolled-btn"
                            data-id="">
                            <span class="text">Lihat Pendidikan Lanjutan</span>
                            <i class="flaticon-arrow-right"></i>
                        </a>
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
