@extends('frontend.layouts.master')
@section('meta_title', 'FAQ || ' . $setting->app_name)
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb title="Frequently Asked Questions" :links="[['url' => route('home'), 'text' => __('Home')], ['url' => '', 'text' => 'Frequently Asked Questions']]" />
    <!-- breadcrumb-area-end -->

    <section class="about-area-three section-py-120">
        <div class="container">
            <div class="card singUp-wrap custom-page-body">
                <div class="card-body">
                    <div class="faq__content w-100">
                        <div class="faq__wrap">
                            <div class="accordion" id="accordionExample">
                                @foreach ($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $faq->id }}" aria-expanded="true"
                                                aria-controls="collapse{{ $faq->id }}">
                                                {{ $faq?->question }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $faq->id }}"
                                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p>
                                                    {{ $faq?->answer }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('home') }}"
                            class="btn btn-primary custom-page-button mt-4">{{ __('Go to Home') }}</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
