@extends('admin.master_layout')
@section('title')
    <title>{{ __('Vacancy List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Vacancy List') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Vacancy List') }}</div>
                </div>
            </div>
            <div class="section-body">

            </div>
        </section>
    </div>
@endsection
