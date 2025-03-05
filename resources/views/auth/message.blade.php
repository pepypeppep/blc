@extends('frontend.layouts.master')
@section('meta_title', 'Login'. ' || ' . $setting->app_name)
@section('contents')
<!-- breadcrumb-area -->
<x-frontend.breadcrumb
    :title="__('Login')"
    :links="[
            ['url' => route('home'), 'text' => __('Home')],
            ['url' => route('login'), 'text' => __('Login')],
        ]" />
<!-- breadcrumb-area-end -->

<!-- singUp-area -->
<section class="singUp-area section-py-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="singUp-wrap">
                    <h2 class="title">Anda tidak diperkenankan mengakses aplikasi ini</h2>
                    <p>Akun {{$username}} {{$email}} Tidak Terdaftar di Esurat</p>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- singUp-area-end -->
@endsection