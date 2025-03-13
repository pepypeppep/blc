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
                    <h2>{{$message}}</h2>
                    <p>Akun {{$username}} {{$email}} Tidak Terdaftar di Esurat</p>
                    {{-- logout button --}}
                    <div class="tg-button-wrap">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

        
                        <a href="javascript:;" class="dropdown-item has-icon d-flex align-items-center text-danger"
                        onclick="event.preventDefault(); $('#logout-form').trigger('submit');">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- singUp-area-end -->
@endsection